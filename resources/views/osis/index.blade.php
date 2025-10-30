@extends('layouts.app')
{{-- Atau copy-paste head & navbar seperti landing jika belum pakai layout --}}

@section('content')
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OSIS - Bakti Nusantara 666</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        .zigzag-card {
            margin-bottom: 2rem;
            display: flex;
            align-items: center;
            gap: 2rem;
        }
        .zigzag-card:nth-child(even) {
            flex-direction: row-reverse;
            text-align: right;
        }
        .zigzag-card img {
            width: 120px;
            height: 120px;
            object-fit: cover;
            border-radius: 50%;
            border: 3px solid #0d6efd;
        }
        .zigzag-card .info {
            flex: 1;
        }
        .zigzag-card h5 {
            margin-bottom: 0.3rem;
        }
        .zigzag-card p {
            margin-bottom: 0;
            color: #6c757d;
        }
    </style>
</head>
<body>

    <!-- Navbar (copy dari landing) -->
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
                <a href="{{ route('home') }}" class="btn btn-outline-light btn-sm">
                    <i class="fas fa-home me-1"></i> Beranda
                </a>
            </div>
        </div>
    </nav>

    <div class="container mt-5 pt-5">
        <h2 class="text-center mb-5">Struktur Inti OSIS</h2>

        @foreach($osisMembers as $index => $member)
            <div class="zigzag-card">
                <img src="{{ $member['photo'] }}" alt="{{ $member['name'] }}">
                <div class="info">
                    <h5>{{ $member['name'] }}</h5>
                    <p>{{ $member['role'] }}</p>
                </div>
            </div>
        @endforeach
    </div>

    <footer class="footer text-center py-3 mt-5" style="background: #f8f9fa; margin-top: 4rem;">
        <p>&copy; 2025 Bakti Nusantara 666. OSIS Resmi Sekolah.</p>
    </footer>

</body>
</html>
@endsection