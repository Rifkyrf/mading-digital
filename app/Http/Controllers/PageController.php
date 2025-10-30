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
    public function osis()
    {
        $intiOsis = [
            [
                'name' => 'Andi Pratama',
                'role' => 'Ketua Umum',
                'photo' => 'https://via.placeholder.com/200x250?text=Andi+Pratama'
            ],
            [
                'name' => 'Budi Santoso',
                'role' => 'Wakil Ketua',
                'photo' => 'https://via.placeholder.com/200x250?text=Budi+Santoso'
            ],
            [
                'name' => 'Citra Dewi',
                'role' => 'Sekretaris',
                'photo' => 'https://via.placeholder.com/200x250?text=Citra+Dewi'
            ],
            [
                'name' => 'Dedi Kurniawan',
                'role' => 'Bendahara',
                'photo' => 'https://via.placeholder.com/200x250?text=Dedi+Kurniawan'
            ],
            [
                'name' => 'Eka Putri',
                'role' => 'Kadiv Humas',
                'photo' => 'https://via.placeholder.com/200x250?text=Eka+Putri'
            ],
            [
                'name' => 'Fajar Ramadhan',
                'role' => 'Kadiv Acara',
                'photo' => 'https://via.placeholder.com/200x250?text=Fajar+Ramadhan'
            ],
            [
                'name' => 'Gita Anggraini',
                'role' => 'Kadiv Keamanan',
                'photo' => 'https://via.placeholder.com/200x250?text=Gita+Anggraini'
            ],
        ];

            // Sekbid 1–10 (bisa diisi nanti dengan data asli)
    $sekbid = [];
    for ($i = 1; $i <= 10; $i++) {
        $sekbid[] = [
            'name' => 'Nama Sekbid ' . $i,
            'role' => 'Jabatan Sekbid ' . $i,
            'photo' => 'https://via.placeholder.com/180x220?text=Sekbid+' . $i
        ];
    }

    return view('pages.osis', compact('intiOsis', 'sekbid'));
    }
}