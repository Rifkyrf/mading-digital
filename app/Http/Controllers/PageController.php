<?php

namespace App\Http\Controllers;
use App\Models\Work;
use Illuminate\Http\Request;

class PageController extends Controller
{
    public function landing()
    {
        // Ambil semua karya, dengan relasi user, paginasi 12 per halaman
        $works = Work::with('user')->latest()->paginate(12);

        return view('pages.landing', compact('works'));
    }
}