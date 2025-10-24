<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User; // Pastikan ini benar

class SearchController extends Controller
{
    public function users(Request $request)
    {
        $query = $request->get('q', '');

        // Validasi: minimal 2 karakter
        if (strlen($query) < 2) {
            return response()->json([]);
        }

        // Query case-insensitive dan cari di name & email
        $users = User::whereRaw('LOWER(name) LIKE ?', ['%' . strtolower($query) . '%'])
            ->orWhereRaw('LOWER(email) LIKE ?', ['%' . strtolower($query) . '%'])
            ->select('id', 'name', 'profile_photo')
            ->limit(8)
            ->get();

        // Jika tidak ada hasil, kirim array kosong
        if ($users->isEmpty()) {
            return response()->json([]);
        }

        return response()->json($users);
    }
}