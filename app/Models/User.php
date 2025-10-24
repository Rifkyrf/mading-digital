<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Models\Work;

class User extends Authenticatable
{
    protected $fillable = [
        'name',
        'email',
        'nis',
        'password',
        'role',
        'profile_photo', // tambahkan jika belum ada
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    // Relasi ke karya
    public function works()
    {
        return $this->hasMany(Work::class);
    }

    // Cek role
    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isGuru()
    {
        return $this->role === 'guru';
    }

    public function isSiswa()
    {
        return $this->role === 'siswa';
    }

    // Aksesori: URL foto profil
    public function getProfilePhotoUrlAttribute()
    {
        // Jika user upload foto, gunakan itu
        if ($this->profile_photo) {
            return asset('storage/' . $this->profile_photo);
        }

        // Jika tidak, gunakan placeholder dari ui-avatars.com
        // Gunakan sprintf untuk hindari error parsing string
        return sprintf(
            'https://ui-avatars.com/api/?name=%s&background=0d47a1&color=fff&size=128',
            urlencode($this->name)
        );
    }

    // Hitung jumlah konten per tipe
    public function getContentTypeCountAttribute()
    {
        return [
            'karya' => $this->works()->where('type', 'karya')->count(),
            'mading' => $this->works()->where('type', 'mading')->count(),
            'berita' => $this->works()->where('type', 'berita')->count(),
        ];
    }
}