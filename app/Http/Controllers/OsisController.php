<?php

namespace App\Http\Controllers;

use App\Models\OsisMember;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class OsisController extends Controller
{
    // Tampilkan halaman kelola OSIS (admin/guru)
    public function manage()
    {
        $inti = OsisMember::where('type', 'inti')->orderBy('order')->get();
        $sekbid = OsisMember::where('type', 'sekbid')->orderBy('order')->get();

        return view('osis.manage', compact('inti', 'sekbid'));
    }

    // Tampilkan form tambah
    public function create()
    {
        return view('osis.create');
    }

    // Simpan data baru
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'role' => 'required|string|max:100',
            'type' => 'required|in:inti,sekbid',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $data = $request->only(['name', 'role', 'type']);

        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')->store('osis', 'public');
        }

        // Atur order otomatis: ambil max order + 1
        $maxOrder = OsisMember::where('type', $request->type)->max('order');
        $data['order'] = ($maxOrder ?? 0) + 1;

        OsisMember::create($data);

        return redirect()->route('osis.manage')->with('success', 'Anggota OSIS berhasil ditambahkan!');
    }

    // Tampilkan form edit
    public function edit(OsisMember $member)
    {
        return view('osis.edit', compact('member'));
    }

    // Update data
    public function update(Request $request, OsisMember $member)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'role' => 'required|string|max:100',
            'type' => 'required|in:inti,sekbid',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $data = $request->only(['name', 'role', 'type']);

        // Jika ganti foto
        if ($request->hasFile('photo')) {
            // Hapus foto lama jika ada
            if ($member->photo) {
                Storage::disk('public')->delete($member->photo);
            }
            $data['photo'] = $request->file('photo')->store('osis', 'public');
        }

        $member->update($data);

        return redirect()->route('osis.manage')->with('success', 'Data berhasil diperbarui!');
    }

    // Hapus data
    public function destroy(OsisMember $member)
    {
        if ($member->photo) {
            Storage::disk('public')->delete($member->photo);
        }
        $member->delete();

        return redirect()->route('osis.manage')->with('success', 'Data berhasil dihapus!');
    }
}