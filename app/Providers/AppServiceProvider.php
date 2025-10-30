<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    // ... isi AppServiceProvider ...
}

// ===============================================
// Bagian ini mungkin ada di bawah AppServiceProvider
// DAN HARUS DIHAPUS SEPENUHNYA
// ===============================================

class RouteServiceProvider extends ServiceProvider // <--- HAPUS MULAI DARI SINI
{
    public const HOME = '/home'; // atau '/dashboard'
    // ... semua fungsi di dalamnya ...
<<<<<<< HEAD
}
=======
}
>>>>>>> 39a5d80d32e69356f1a2609e696d0a6746fb67a5
