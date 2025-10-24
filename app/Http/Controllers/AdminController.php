<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function index()
    {
        if (!Auth::check() || !Auth::user()->isAdmin()) {
            return redirect('/')->with('error', 'Akses ditolak.');
        }

        // Ambil guru dan siswa secara terpisah
        $gurus = User::withCount('works')
            ->where('role', 'guru')
            ->orderBy('name')
            ->get();

        $siswas = User::withCount('works')
            ->where('role', 'siswa')
            ->orderBy('name')
            ->get();

        return view('admin.index', compact('gurus', 'siswas'));
    }
    public function create()
{
    if (!Auth::user()->isAdmin()) {
        abort(403);
    }
    return view('admin.create');
}

public function store(Request $request)
{
    if (!Auth::user()->isAdmin()) {
        abort(403);
    }

    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users',
        'nis' => 'required|unique:users',
        'role' => 'required|in:guru,siswa',
        'password' => 'required|min:6',
    ]);

    User::create([
        'name' => $request->name,
        'email' => $request->email,
        'nis' => $request->nis,
        'role' => $request->role,
        'password' => Hash::make($request->password),
    ]);

    return redirect()->route('admin.index')->with('success', 'User berhasil ditambahkan.');
}

public function edit($id)
{
    if (!Auth::user()->isAdmin()) {
        abort(403);
    }
    $user = User::findOrFail($id);
    return view('admin.edit', compact('user'));
}

public function update(Request $request, $id)
{
    if (!Auth::user()->isAdmin()) {
        abort(403);
    }

    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email,' . $id,
        'nis' => 'required|unique:users,nis,' . $id,
        'role' => 'required|in:guru,siswa',
    ]);

    $user = User::findOrFail($id);
    $user->update($request->only(['name', 'email', 'nis', 'role']));

    return redirect()->route('admin.index')->with('success', 'User berhasil diperbarui.');
}

public function destroy($id)
{
    if (!Auth::user()->isAdmin()) {
        abort(403);
    }

    $user = User::findOrFail($id);
    // Opsional: cegah hapus diri sendiri
    if ($user->id === Auth::id()) {
        return redirect()->back()->with('error', 'Tidak bisa menghapus akun sendiri.');
    }

    $user->delete();
    return redirect()->route('admin.index')->with('success', 'User berhasil dihapus.');
}
}