<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function show($id)
    {
        $user = User::findOrFail($id);

        // Hitung jumlah konten
        $karya = $user->works()->where('type', 'karya')->get();
        $mading = $user->works()->where('type', 'mading')->get();

        $allContents = $karya->merge($mading);

        return view('profile.show', compact('user', 'karya', 'mading', 'allContents'));
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);

        if ($user->id !== Auth::id()) {
            abort(403, 'Anda tidak diizinkan mengedit profil ini.');
        }

        return view('profile.edit', compact('user'));
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        if ($user->id !== Auth::id()) {
            abort(403, 'Tidak diizinkan.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'bio' => 'nullable|string|max:500',
            'profile_photo' => 'nullable|image|max:2048',
        ]);

        // Update data
        $user->update([
            'name' => $request->name,
            'bio' => $request->bio,
        ]);

        // Handle foto profil
        if ($request->hasFile('profile_photo')) {
            // Hapus foto lama jika ada
            if ($user->profile_photo) {
                Storage::disk('public')->delete($user->profile_photo);
            }

            // Simpan foto baru
            $path = $request->file('profile_photo')->store('profile_photos', 'public');
            $user->update(['profile_photo' => $path]);
        }

        return redirect('/profile/' . $user->id)->with('success', 'Profil berhasil diperbarui!');
    }
}