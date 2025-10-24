@extends('layouts.app')

@section('title', $user->name . ' - Profil')

@section('content')
<div class="container-fluid py-3">
    <div class="row">

        <!-- Sidebar Profil (Lebih Ringkas) -->
        <div class="col-lg-2 mb-4">
            <div class="text-center">
                <!-- Foto Profil -->
                <img src="{{ $user->profile_photo_url }}"
                     alt="Foto Profil"
                     class="rounded-circle mb-2"
                     style="width: 80px; height: 80px; object-fit: cover; border: 3px solid #0d47a1;">

                <!-- Nama -->
                <h6 class="mb-0">{{ $user->name }}</h6>
                <small class="text-muted">{{ $user->nis }}</small>

                <!-- Role Badge -->
                <div class="mt-1">
                    <span class="badge bg-primary">{{ ucfirst($user->role) }}</span>
                </div>

                <!-- Tombol Edit -->
                @if(Auth::check() && Auth::id() === $user->id)
                <div class="mt-2 text-center">
                    <a href="{{ route('profile.edit', $user->id) }}" class="text-decoration-none" title="Edit Profil">
                        <i class="fas fa-cog text-primary" style="font-size: 1.2rem;"></i>
                    </a>
                </div>
                @endif

                <!-- Statistik Konten -->
                <hr class="my-3">
                <div class="text-start small">
                    <div><strong>{{ $user->content_type_count['karya'] }}</strong> Karya</div>
                    <div><strong>{{ $user->content_type_count['mading'] }}</strong> Mading</div>
                </div>
            </div>
        </div>

        <!-- Konten Utama (Postingan) -->
        <div class="col-lg-10">
            <!-- Tab Navigation -->
            <ul class="nav nav-tabs mb-3 border-0">
                <li class="nav-item">
                    <a class="nav-link active small" data-bs-toggle="tab" href="#semua">Semua</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link small" data-bs-toggle="tab" href="#karya">Karya Siswa</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link small" data-bs-toggle="tab" href="#mading">Mading Digital</a>
                </li>
            </ul>

            <!-- Daftar Postingan (Grid Lebih Rapat) -->
            <div class="tab-content">
                <div class="tab-pane fade show active" id="semua">
                    @include('profile._content_list', ['contents' => $allContents])
                </div>
                <div class="tab-pane fade" id="karya">
                    @include('profile._content_list', ['contents' => $karya])
                </div>
                <div class="tab-pane fade" id="mading">
                    @include('profile._content_list', ['contents' => $mading])
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

<style>
    .nav-tabs .nav-link {
        font-size: 0.85rem;
        padding: 0.5rem 1rem;
        color: #555;
    }
    .nav-tabs .nav-link.active {
        background: #0d47a1;
        color: white;
    }
    .card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }

    /* Hover effect for gear icon */
    .fa-cog:hover {
        color: #0056b3;
        transform: scale(1.1);
        transition: all 0.2s ease;
    }
</style>