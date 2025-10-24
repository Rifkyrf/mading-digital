<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // Tampilkan form login
    public function showLoginForm()
    {
        return view('auth.login');
    }

    // Proses login
    public function login(Request $request)
    {
        $request->validate([
            'nis' => 'required|string',
            'password' => 'required|string',
        ]);

        // Cari user berdasarkan NIS
        $user = User::where('nis', $request->nis)->first();

        if ($user && Hash::check($request->password, $user->password)) {
            Auth::login($user); // ✅ login dengan objek user
            $request->session()->regenerate();
            return redirect('/dashboard')->with('success', 'Login berhasil! Selamat datang, ' . $user->name);
        }

        return back()->withErrors([
            'nis' => 'NIS atau password salah.',
        ])->withInput($request->only('nis'));
    }

    // Logout
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}