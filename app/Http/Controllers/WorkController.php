<?php

namespace App\Http\Controllers;

use App\Models\Work;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException; // <-- Tambahkan ini

class WorkController extends Controller
{
    /**
     * Tampilkan daftar karya (dashboard)
     */
    public function index()
    {
        $works = Work::with('user')->latest()->paginate(12);
        return view('works.index', compact('works'));
    }

    /**
     * Tampilkan form upload — untuk modal atau halaman biasa
     */
    public function create()
    {
        $types = [
            'karya' => 'Karya Siswa',
            'mading' => 'Mading Digital',
        ];

        if (Auth::check() && (Auth::user()->isGuru() || Auth::user()->isAdmin())) {
            $types += [
                'berita' => 'Berita Sekolah',
                'pengumuman' => 'Pengumuman',
                'pelajaran' => 'Materi Pelajaran',
            ];
        }

        // Jika dipanggil via AJAX/modal, return partial
        if (request()->ajax()) {
            return view('works._form_upload', compact('types'));
        }

        return view('works.upload', compact('types'));
    }

    /**
     * Simpan karya baru
     */
    public function store(Request $request)
    {
        if (!Auth::check()) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json(['success' => false, 'message' => 'Silakan login terlebih dahulu.'], 401);
            }
            return redirect('/login')->with('error', 'Akses ditolak: Silakan login.');
        }

        $allowedTypes = ['karya', 'mading'];
        if (Auth::user()->isGuru() || Auth::user()->isAdmin()) {
            $allowedTypes = array_merge($allowedTypes, ['berita', 'pengumuman', 'pelajaran']);
        }

        try {
            $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'nullable|string|max:1000',
                'file' => 'required|file|max:512000', // 500MB
                'thumbnail' => 'nullable|image|max:2048', // 2MB
                'type' => ['required', Rule::in($allowedTypes)],
            ], [
                'file.max' => 'File utama maksimal 500MB.',
                'thumbnail.max' => 'Thumbnail maksimal 2MB.',
            ]);
        } catch (ValidationException $e) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validasi gagal.',
                    'errors' => $e->errors()
                ], 422);
            }
            throw $e;
        }

        $file = $request->file('file');
        $originalPath = $file->store('uploads', 'public');
        $extension = strtolower($file->getClientOriginalExtension());
        $mimeType = $file->getMimeType();

        $thumbnailPath = null;
        if ($request->hasFile('thumbnail')) {
            $thumbnailPath = $request->file('thumbnail')->store('thumbnails', 'public');
        }

        // Placeholder thumbnail untuk video (opsional)
        if (!$thumbnailPath && in_array($extension, ['mp4', 'webm', 'mov', 'avi'])) {
            $thumbnailPath = 'placeholders/video.jpg';
        }

        $contentType = $this->determineContentType($extension);

        $work = Work::create([
            'title' => $request->title,
            'description' => $request->description,
            'file_path' => $originalPath,
            'file_type' => $extension,
            'content_type' => $contentType,
            'mime_type' => $mimeType,
            'user_id' => Auth::id(),
            'thumbnail_path' => $thumbnailPath,
            'type' => $request->type,
        ]);

        Log::info('Karya diunggah', [
            'work_id' => $work->id,
            'user_id' => Auth::id(),
            'title' => $work->title,
            'type' => $work->type,
        ]);

        // PERBAIKAN: Gunakan expectsJson() untuk deteksi yang lebih reliable
        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => '🎉 Karya berhasil diunggah!',
                'work_id' => $work->id
            ]);
        }

        return redirect('/dashboard')->with('success', '🎉 Karya berhasil diunggah!');
    }

    public function edit($id)
    {
        $work = Work::with('user')->findOrFail($id);

        if ($work->user_id !== Auth::id() && !Auth::user()->isAdmin()) {
            return redirect('/dashboard')->with('error', 'Tidak diizinkan.');
        }

        $types = [
            'karya' => 'Karya Siswa',
            'mading' => 'Mading Digital',
        ];

        if (Auth::user()->isGuru() || Auth::user()->isAdmin()) {
            $types['berita'] = 'Berita Sekolah';
            $types['pengumuman'] = 'Pengumuman';
            $types['pelajaran'] = 'Materi Pelajaran';
        }

        return view('works.edit', compact('work', 'types'));
    }

    /**
     * Tampilkan form edit — untuk modal
     */
    public function editForm(Work $work)
    {
        if (!Auth::check()) {
            abort(403);
        }

        // Hanya pemilik atau admin yang bisa edit
        if ($work->user_id !== Auth::id() && !Auth::user()->isAdmin()) {
            abort(403, 'Akses ditolak.');
        }

        $types = [
            'karya' => 'Karya Siswa',
            'mading' => 'Mading Digital',
        ];

        if (Auth::user()->isGuru() || Auth::user()->isAdmin()) {
            $types += [
                'berita' => 'Berita Sekolah',
                'pengumuman' => 'Pengumuman',
                'pelajaran' => 'Materi Pelajaran',
            ];
        }

        return view('works._form_edit', compact('work', 'types'));
    }

    /**
     * Update karya
     */
    public function update(Request $request, Work $work)
    {
        if (!Auth::check()) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json(['success' => false, 'message' => 'Silakan login.'], 401);
            }
            return redirect('/login');
        }

        // Hanya pemilik atau admin yang bisa update
        if ($work->user_id !== Auth::id() && !Auth::user()->isAdmin()) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json(['success' => false, 'message' => 'Tidak diizinkan.'], 403);
            }
            return redirect('/dashboard')->with('error', 'Tidak diizinkan.');
        }

        $allowedTypes = ['karya', 'mading'];
        if (Auth::user()->isGuru() || Auth::user()->isAdmin()) {
            $allowedTypes = array_merge($allowedTypes, ['berita', 'pengumuman', 'pelajaran']);
        }

        $rules = [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'type' => ['required', Rule::in($allowedTypes)],
        ];

        // Jika ada file baru, tambahkan validasi
        if ($request->hasFile('file')) {
            $rules['file'] = 'required|file|max:512000';
        }
        if ($request->hasFile('thumbnail')) {
            $rules['thumbnail'] = 'nullable|image|max:2048';
        }

        try {
            $request->validate($rules, [
                'file.max' => 'File maksimal 500MB.',
                'thumbnail.max' => 'Thumbnail maksimal 2MB.',
            ]);
        } catch (ValidationException $e) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validasi gagal.',
                    'errors' => $e->errors()
                ], 422);
            }
            throw $e;
        }

        // Update data dasar
        $work->update([
            'title' => $request->title,
            'description' => $request->description,
            'type' => $request->type,
        ]);

        // Jika ada file baru
        if ($request->hasFile('file')) {
            // Hapus file lama (opsional)
            if ($work->file_path && Storage::disk('public')->exists($work->file_path)) {
                Storage::disk('public')->delete($work->file_path);
            }

            $newFile = $request->file('file');
            $filePath = $newFile->store('uploads', 'public');
            $extension = strtolower($newFile->getClientOriginalExtension());

            $work->update([
                'file_path' => $filePath,
                'file_type' => $extension,
                'content_type' => $this->determineContentType($extension),
                'mime_type' => $newFile->getMimeType(),
            ]);
        }

        // Jika ada thumbnail baru
        if ($request->hasFile('thumbnail')) {
            if ($work->thumbnail_path && Storage::disk('public')->exists($work->thumbnail_path)) {
                Storage::disk('public')->delete($work->thumbnail_path);
            }
            $thumbPath = $request->file('thumbnail')->store('thumbnails', 'public');
            $work->update(['thumbnail_path' => $thumbPath]);
        }

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json(['success' => true, 'message' => '🎉 Berhasil diperbarui!']);
        }

        return redirect()->route('work.show', $work->id)->with('success', '🎉 Berhasil diperbarui!');
    }


    /**
     * Tampilkan detail karya (publik)
     */
    public function show($id)
    {
        $work = Work::with('user')->findOrFail($id);
        $comments = $work->comments()->with('user')->latest()->get();
        $userLiked = Auth::check() && $work->likes()->where('user_id', Auth::id())->exists();

        return view('works.show', compact('work', 'comments', 'userLiked'));
    }

    // untuk guest
    public function showg($id)
    {
        $work = Work::with('user')->findOrFail($id);
        $comments = $work->comments()->with('user')->latest()->get();
        $userLiked = Auth::check() && $work->likes()->where('user_id', Auth::id())->exists();

        return view('works.showg', compact('work', 'comments', 'userLiked'));
    }

    /**
     * Untuk modal detail
     */
    public function showModal(Work $work)
    {
        $work->loadMissing('user');
        $likesCount = $work->likes()->count();
        $comments = $work->comments()->with('user')->latest()->limit(3)->get();
        $userLiked = Auth::check() && $work->likes()->where('user_id', Auth::id())->exists();

        return view('works._modal_content', compact('work', 'userLiked', 'comments', 'likesCount'));
    }

    /**
     * Helper: Tentukan tipe konten
     */
    private function determineContentType($extension)
    {
        $map = [
            'image' => ['jpg', 'jpeg', 'png', 'gif', 'webp'],
            'video' => ['mp4', 'webm', 'mov', 'avi', 'mkv', 'flv'],
            'document' => ['pdf', 'doc', 'docx', 'ppt', 'pptx', 'xls', 'xlsx', 'txt'],
            'code' => ['py', 'js', 'html', 'css', 'php', 'java', 'cpp', 'json', 'xml', 'yml', 'md'],
        ];

        foreach ($map as $type => $exts) {
            if (in_array($extension, $exts)) {
                return $type;
            }
        }

        return 'file';
    }

    /**
     * Hapus karya
     */
    public function destroy(Work $work)
    {
        if (!Auth::check()) {
            return redirect()->back()->with('error', 'Silakan login.');
        }

        // Hanya pemilik atau admin yang bisa hapus
        if ($work->user_id !== Auth::id() && !Auth::user()->isAdmin()) {
            return redirect()->back()->with('error', 'Tidak diizinkan. Hanya pemilik atau admin yang bisa menghapus.');
        }

        // Hapus file
        if ($work->file_path && Storage::disk('public')->exists($work->file_path)) {
            Storage::disk('public')->delete($work->file_path);
        }
        if ($work->thumbnail_path && Storage::disk('public')->exists($work->thumbnail_path)) {
            Storage::disk('public')->delete($work->thumbnail_path);
        }

        $work->delete();

        return redirect('/dashboard')->with('success', 'Karya berhasil dihapus!');
    }
}