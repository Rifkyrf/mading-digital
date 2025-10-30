<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OSIS - Bakti Nusantara 666</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

    <!-- Google Fonts: Poppins -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            padding-top: 80px;
            background-color: #f9f9f9;
        }
        .navbar {
            background-color: #1e3a8a;
        }

        /* 7 Inti OSIS - Zigzag */
        .zigzag-card {
            display: flex;
            align-items: center;
            gap: 2rem;
            margin-bottom: 3rem;
            background: white;
            padding: 1.5rem;
            border-radius: 12px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.08);
        }
        .zigzag-card:nth-child(even) {
            flex-direction: row-reverse;
            text-align: right;
        }
        .zigzag-card img {
            width: 180px;
            height: 220px;
            object-fit: cover;
            border-radius: 8px;
            border: 2px solid #3b82f6;
        }
        .zigzag-card .info h4 {
            margin-bottom: 0.3rem;
            color: #1e3a8a;
            font-size: 1.3rem;
        }
        .zigzag-card .info p {
            margin: 0;
            color: #475569;
            font-weight: 600;
        }

        /* Sekbid Cards */
        .sekbid-card img {
            width: 100%;
            height: 200px;
            object-fit: cover;
            border-radius: 8px;
            border: 1px solid #cbd5e1;
        }
        .sekbid-card .card-body h5 {
            font-size: 1.1rem;
            color: #1e3a8a;
        }
        .sekbid-card .card-body p {
            color: #475569;
            font-size: 0.95rem;
        }

        .footer {
            text-align: center;
            padding: 1.5rem;
            margin-top: 3rem;
            background: #f1f5f9;
            color: #64748b;
        }
    </style>
</head>
<body>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark fixed-top">
        <div class="container">
            <a class="navbar-brand" href="{{ route('home') }}">
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

    <div class="container mt-4">

        <!-- 7 Inti OSIS -->
        <h2 class="text-center mb-5">7 Inti Struktur OSIS</h2>
        @foreach($intiOsis as $member)
            <div class="zigzag-card">
                <img src="{{ $member['photo'] }}" alt="{{ $member['name'] }}">
                <div class="info">
                    <h4>{{ $member['name'] }}</h4>
                    <p>{{ $member['role'] }}</p>
                </div>
            </div>
        @endforeach

        <!-- Garis pemisah -->
        <hr class="my-5">

        <!-- Sekbid 1–10 -->
        <h3 class="text-center mb-4">Sekretariat Bidang (Sekbid 1–10)</h3>
        <div class="row g-4">
            @foreach($sekbid as $member)
                <div class="col-md-4 col-sm-6">
                    <div class="card sekbid-card shadow-sm">
                        <img src="{{ $member['photo'] }}" alt="{{ $member['name'] }}">
                        <div class="card-body text-center">
                            <h5>{{ $member['name'] }}</h5>
                            <p class="mb-0">{{ $member['role'] }}</p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

    </div>

    <!-- Footer -->
    <footer class="footer">
        <p>&copy; 2025 Bakti Nusantara 666. OSIS Resmi Sekolah.</p>
    </footer>

</body>
</html>