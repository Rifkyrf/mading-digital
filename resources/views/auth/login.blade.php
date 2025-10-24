@extends('layouts.login')

@section('title', 'Login Siswa & Guru')

@section('content')
<style>
    /* Google Font */
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@500;600;700&display=swap');

    body {
        font-family: 'Poppins', sans-serif;
    }

    .bg-gradient-primary-to-light {
        background: linear-gradient(135deg, #2563eb, #dbeafe);
    }

    .login-card {
        background: white;
        border-radius: 20px;
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        overflow: hidden;
        transition: transform 0.4s ease, box-shadow 0.4s ease;
        max-width: 450px;
    }

    .login-card:hover {
        transform: translateY(-12px);
        box-shadow: 0 25px 50px rgba(0, 0, 0, 0.15);
    }

    .card-header {
        background: linear-gradient(135deg, #1d4ed8, #1e40af);
        color: white;
        padding: 1.8rem 1.5rem;
        text-align: center;
        font-weight: 700;
        font-size: 1.4rem;
        position: relative;
        overflow: hidden;
        letter-spacing: 0.5px;
    }

    .card-header::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
        animation: shine 3s infinite;
    }

    @keyframes shine {
        0% { left: -100%; }
        100% { left: 100%; }
    }

    .form-control {
        border: 2px solid #e2e8f0;
        border-radius: 12px;
        padding: 14px 16px;
        font-size: 1rem;
        transition: all 0.3s ease;
        font-weight: 500;
    }

    .form-control:focus {
        border-color: #2563eb;
        box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.2);
        outline: none;
    }

    .form-label {
        font-weight: 600;
        margin-bottom: 0.5rem;
        color: #1e293b;
        font-size: 0.95rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .btn-primary {
        background: linear-gradient(135deg, #2563eb, #1d4ed8);
        border: none;
        padding: 16px 28px;
        font-size: 1.1rem;
        font-weight: 600;
        border-radius: 12px;
        transition: all 0.3s ease;
        cursor: pointer;
        width: 100%;
        letter-spacing: 0.5px;
        color: white;
    }

    .btn-primary:hover {
        background: linear-gradient(135deg, #1d4ed8, #1e40af);
        transform: scale(1.03);
        box-shadow: 0 8px 20px rgba(37, 99, 235, 0.4);
    }

    /* CUSTOM ERROR MESSAGE */
    .error-message {
        color: #dc2626;
        font-size: 0.95rem;
        font-weight: 600;
        margin-top: 0.5rem;
        padding: 0.5rem 1rem;
        background: #fef2f2;
        border-left: 4px solid #dc2626;
        border-radius: 0 8px 8px 0;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        animation: shake 0.5s ease-in-out;
    }

    @keyframes shake {
        0%, 100% { transform: translateX(0); }
        25% { transform: translateX(-5px); }
        75% { transform: translateX(5px); }
    }

    /* Fade-in animasi */
    .fade-in {
        opacity: 0;
        transform: translateY(30px);
        transition: opacity 0.8s ease, transform 0.8s ease;
    }

    .fade-in.active {
        opacity: 1;
        transform: translateY(0);
    }

    /* Responsif */
    @media (max-width: 576px) {
        .login-card {
            margin: 1rem;
            width: 90%;
        }
        .card-header {
            font-size: 1.2rem;
            padding: 1.5rem 1rem;
        }
        .btn-primary {
            padding: 14px 24px;
            font-size: 1rem;
        }
    }

    /* Aksesibilitas: Kontras Tinggi */
    .form-control,
    .form-label,
    .btn-primary {
        color: #1e293b; /* teks gelap untuk kontras tinggi */
    }

    .btn-primary {
        color: white !important;
    }

    /* Logo Branding */
    .brand-logo {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.75rem;
        font-weight: 700;
        font-size: 1.5rem;
        text-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }

    .brand-logo i {
        font-size: 1.8rem;
        color: #fbbf24;
    }
</style>

<div class="row justify-content-center align-items-center vh-100">
    <div class="col-12">
        <div class="login-card fade-in mx-auto">
            <div class="card-header">
                <div class="brand-logo">
                    <i class="fas fa-graduation-cap"></i>
                    Bakti Nusantara 666
                </div>
            </div>
            <div class="card-body p-4">
                <form method="POST" action="{{ route('login') }}" novalidate>
                    @csrf

                    <div class="mb-4">
                        <label for="nis" class="form-label"><i class="fas fa-id-card"></i> NIS</label>
                        <input type="text" name="nis" id="nis" class="form-control" value="{{ old('nis') }}" required autofocus>
                        @error('nis')
                            <div class="error-message">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="password" class="form-label"><i class="fas fa-lock"></i> Password</label>
                        <input type="password" name="password" id="password" class="form-control" required>
                        @error('password')
                            <div class="error-message">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-sign-in-alt me-2"></i> Login
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        const card = document.querySelector('.fade-in');
        card.classList.add('active');

        // Validasi real-time untuk pesan error custom
        const form = document.querySelector('form');
        const inputs = form.querySelectorAll('input[required]');

        inputs.forEach(input => {
            input.addEventListener('invalid', function(e) {
                e.preventDefault();
                if (!this.value) {
                    this.classList.add('is-invalid');
                }
            });

            input.addEventListener('input', function() {
                if (this.value) {
                    this.classList.remove('is-invalid');
                }
            });
        });
    });
</script>
@endsection