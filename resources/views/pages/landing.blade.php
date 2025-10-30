<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bakti Nusantara 666 - Mading & Karya Siswa</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

    <!-- Google Fonts: Poppins -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

    {{-- Css link --}}
    <link href="{{ asset('css/landing.css') }}" rel="stylesheet">


</head>
<body>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark fixed-top">
        <div class="container">
            <a class="navbar-brand" href="/">
                <i class="fas fa-school"></i> Bakti Nusantara 666
            </a>
            <div class="ms-auto d-flex align-items-center">
                @if(Auth::check())
                    <a href="/dashboard" class="btn btn-outline-light btn-sm">
                        <i class="fas fa-home me-1"></i> Dashboard
                    </a>
                @else
                    <a href="{{ route('login') }}" class="btn btn-outline-light btn-sm">
                        <i class="fas fa-sign-in-alt me-1"></i> Login
                    </a>
                @endif
                <a href="{{ route('osis.index') }}" class="btn btn-outline-light btn-sm">
                    <i class="fas fa-sign-in-alt me-1"></i> Osis
                </a>
                {{-- <a href="{{ route('login') }}" class="btn btn-outline-light btn-sm">
                    <i class="fas fa-sign-in-alt me-1"></i> Login
                </a> --}}
            </div>
        </div>
    </nav>

    <!-- Filter Buttons -->
    <div class="filter-container">
        <a href="?type=all" class="filter-btn {{ request('type') == 'all' || !request('type') ? 'active' : '' }}">Semua</a>
        <a href="?type=mading" class="filter-btn {{ request('type') == 'mading' ? 'active' : '' }}">Mading Digital</a>
        <a href="?type=karya" class="filter-btn {{ request('type') == 'karya' ? 'active' : '' }}">Karya Siswa</a>
    </div>

    <!-- Content -->
    <div class="container">
        <h2>
            @if(request('type') == 'mading')
                Mading Digital
            @elseif(request('type') == 'karya')
                Karya Siswa
            @else
                Semua Konten
            @endif
        </h2>

        <div class="row g-4">
            @forelse($works as $index => $work)
                @php
                    $show = false;
                    $currentType = request('type');

                    if (!$currentType || $currentType === 'all') {
                        $show = true;
                    } elseif ($currentType === 'karya' && $work->type === 'karya') {
                        $show = true;
                    } elseif ($currentType === 'mading' && $work->type === 'mading') {
                        $show = true;
                    }
                @endphp

                @if($show)
                    <div class="col-md-4 col-12 work-card" style="animation-delay: {{ $index * 0.1 }}s;">
                        <div class="card">
                            <img src="{{ $work->thumbnail_path ? asset('storage/' . $work->thumbnail_path) : $work->icon }}"
                                 class="card-img-top"
                                 alt="{{ $work->title }}">

                            <div class="card-body">
                                <h5 class="card-title" title="{{ $work->title }}">
                                    {{ Str::limit($work->title, 40) }}
                                </h5>
                                <p class="card-text">
                                    {{ Str::limit($work->description, 60) }}
                                </p>
                                <small class="d-block text-muted mb-1">
                                    Oleh: <strong>{{ $work->user->name }}</strong>
                                </small>
                                <small class="text-secondary">
                                    {{ strtoupper($work->file_type) }}
                                </small>
                                <a href="{{ route('work.show', $work->id) }}" class="btn btn-outline-primary btn-sm mt-2">
                                    <i class="fas fa-eye me-1"></i> Lihat
                                </a>
                            </div>
                        </div>
                    </div>
                @endif
            @empty
                <div class="col-12 text-center text-muted">
                    <p>Tidak ada konten yang tersedia.</p>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        <div class="d-flex justify-content-center mt-4">
            {{ $works->appends(request()->query())->links() }}
        </div>
    </div>

    <!-- Footer -->
    <footer class="footer">
        <p>&copy; 2025 Bakti Nusantara 666. Semua karya siswa dilindungi.</p>
    </footer>

    <script src="{{ asset('javascript/landing.js') }}"></script>
</body>
</html>