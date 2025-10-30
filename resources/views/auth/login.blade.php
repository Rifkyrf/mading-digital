@extends('layouts.login')

@section('title', 'Login Siswa & Guru')

@section('content')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap');

    body {
        font-family: 'Poppins', sans-serif;
        margin: 0;
        padding: 0;
        background-color: #f8fafc;
    }

    .school-bg {
        height: 100vh;
        background-color: #1e40af;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        text-align: center;
        padding: 2rem;
    }

    .school-bg h2 {
        font-weight: 600;
        font-size: 1.8rem;
        line-height: 1.4;
        margin: 0;
    }

    .school-bg p {
        font-size: 1.1rem;
        opacity: 0.9;
        margin-top: 1rem;
        max-width: 320px;
    }

    .login-card {
        background: white;
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.06);
        width: 100%;
        max-width: 420px;
        overflow: hidden;
        transition: transform 0.3s ease;
    }

    .login-card:hover {
        transform: translateY(-2px);
    }

    .card-header {
        background: white;
        padding: 1.5rem;
        text-align: center;
        border-bottom: 1px solid #f1f5f9;
    }

    .brand-logo {
        font-size: 1.4rem;
        font-weight: 600;
        color: #1e40af;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.75rem;
    }

    .brand-logo i {
        color: #3b82f6;
    }

    .card-body {
        padding: 2rem;
    }

    .form-label {
        font-weight: 500;
        font-size: 0.95rem;
        color: #334155;
        margin-bottom: 0.5rem;
        display: flex;
        align-items: center;
        gap: 0.4rem;
    }

    .form-control {
        border: 1px solid #cbd5e1;
        border-radius: 10px;
        padding: 12px 16px;
        font-size: 1rem;
        font-weight: 500;
        color: #1e293b;
        background-color: #f8fafc;
        transition: border-color 0.25s ease, box-shadow 0.25s ease;
    }

    .form-control:focus {
        outline: none;
        border-color: #3b82f6;
        background-color: white;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.15);
    }

    .form-control::placeholder {
        color: #94a3b8;
    }

    .btn-login {
        background: #3b82f6;
        border: none;
        border-radius: 10px;
        padding: 14px;
        font-size: 1rem;
        font-weight: 600;
        color: white;
        width: 100%;
        transition: background-color 0.25s ease, transform 0.2s ease;
    }

    .btn-login:hover {
        background: #2563eb;
        transform: translateY(-1px);
    }

    .error-message {
        color: #ef4444;
        font-size: 0.875rem;
        margin-top: 0.4rem;
        padding: 0.5rem;
        background: #fef2f2;
        border-radius: 8px;
        border-left: 3px solid #f87171;
        display: flex;
        align-items: center;
        gap: 0.4rem;
    }

    .login-mode {
        text-align: center;
        margin-bottom: 1.5rem;
    }

    .login-mode-btn {
        font-size: 0.85rem;
        padding: 4px 12px;
        border-radius: 20px;
        border: none;
        background: #f1f5f9;
        color: #64748b;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .login-mode-btn.active {
        background: #dbeafe;
        color: #1e40af;
        font-weight: 600;
    }

    .login-mode-btn.guest.active {
        background: #cffafe;
        color: #0e7490;
    }

    #internal-toggle {
        text-align: center;
        margin-bottom: 1.25rem;
    }

    #internal-toggle small {
        font-size: 0.875rem;
        cursor: pointer;
        color: #3b82f6;
        text-decoration: underline;
    }

    .register-link {
        text-align: center;
        margin-top: 1.25rem;
        font-size: 0.95rem;
        color: #64748b;
    }

    .register-link a {
        color: #3b82f6;
        text-decoration: none;
        font-weight: 600;
        transition: color 0.2s ease;
    }

    .register-link a:hover {
        color: #1d4ed8;
        text-decoration: underline;
    }

    @media (max-width: 768px) {
        .school-bg {
            display: none;
        }
        .login-card {
            margin: 1rem;
            width: 90%;
        }
        .card-body {
            padding: 1.5rem;
        }
    }
</style>

<div class="row g-0 vh-100">
    <!-- Kolom Kiri -->
    <div class="col-md-6 d-none d-md-flex school-bg">
        <div>
            <h2>SMK Bakti Nusantara 666</h2>
            <p>Mencetak Generasi Unggul dan Berkarakter</p>
        </div>
    </div>

    <!-- Kolom Kanan: Form Login -->
    <div class="col-md-6 d-flex align-items-center justify-content-center">
        <div class="login-card">
            <div class="card-header">
                <div class="brand-logo">
                    <i class="fas fa-graduation-cap"></i>
                    Bakti Nusantara 666
                </div>
            </div>
            <div class="card-body">
                <!-- Toggle Mode -->
                <div class="login-mode">
                    <button type="button" class="login-mode-btn active" data-mode="internal">
                        Siswa / Guru
                    </button>
                    <button type="button" class="login-mode-btn" data-mode="guest">
                        Guest
                    </button>
                </div>

                <!-- Toggle NIS vs Email -->
                <div id="internal-toggle">
                    <small id="switch-to-email">Bosen masuk pakai NIS? Gunakan email, yok!</small>
                    <small id="switch-to-nis" style="display: none;">Kembali ke NIS</small>
                </div>

                <form method="POST" action="{{ route('login') }}" novalidate>
                    @csrf

                    <div class="mb-4">
                        <label for="identifier" class="form-label">
                            <i class="fas fa-id-card"></i> <span id="label-text">NIS</span>
                        </label>
                        <input
                            type="text"
                            name="identifier"
                            id="identifier"
                            class="form-control"
                            value="{{ old('identifier') }}"
                            placeholder="Masukkan NIS Anda"
                            required
                            autofocus
                        >
                        <input type="hidden" name="login_as" id="login_as" value="internal_nis">
                        @error('identifier')
                            <div class="error-message">
                                <i class="fas fa-exclamation-circle"></i> {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="password" class="form-label">
                            <i class="fas fa-lock"></i> Password
                        </label>
                        <input
                            type="password"
                            name="password"
                            id="password"
                            class="form-control"
                            placeholder="••••••••"
                            required
                        >
                        @error('password')
                            <div class="error-message">
                                <i class="fas fa-exclamation-circle"></i> {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn-login">
                            <i class="fas fa-sign-in-alt me-2"></i> Masuk
                        </button>
                    </div>
                </form>

                <div class="register-link">
                    Belum punya akun? <a href="{{ route('register') }}">Daftar sebagai Guest</a>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const modeButtons = document.querySelectorAll('.login-mode-btn');
    const switchToEmail = document.getElementById('switch-to-email');
    const switchToNis = document.getElementById('switch-to-nis');
    const internalToggle = document.getElementById('internal-toggle');

    const labelIcon = document.querySelector('.form-label i');
    const labelText = document.getElementById('label-text');
    const identifierInput = document.getElementById('identifier');
    const loginAsInput = document.getElementById('login_as');

    let useEmailForInternal = false;

    modeButtons.forEach(button => {
        button.addEventListener('click', function () {
            modeButtons.forEach(btn => btn.classList.remove('active'));
            this.classList.add('active');

            const mode = this.getAttribute('data-mode');

            if (mode === 'internal') {
                internalToggle.style.display = 'block';
                if (useEmailForInternal) {
                    updateInternalMode('email');
                } else {
                    updateInternalMode('nis');
                }
            } else {
                internalToggle.style.display = 'none';
                updateGuestMode();
            }
        });
    });

    switchToEmail.addEventListener('click', function () {
        useEmailForInternal = true;
        updateInternalMode('email');
    });

    switchToNis.addEventListener('click', function () {
        useEmailForInternal = false;
        updateInternalMode('nis');
    });

    function updateInternalMode(type) {
        if (type === 'email') {
            labelIcon.className = 'fas fa-envelope';
            labelText.textContent = 'Email';
            identifierInput.placeholder = 'Masukkan email Anda';
            loginAsInput.value = 'internal_email';
            switchToEmail.style.display = 'none';
            switchToNis.style.display = 'inline';
        } else {
            labelIcon.className = 'fas fa-id-card';
            labelText.textContent = 'NIS';
            identifierInput.placeholder = 'Masukkan NIS Anda';
            loginAsInput.value = 'internal_nis';
            switchToEmail.style.display = 'inline';
            switchToNis.style.display = 'none';
        }
    }

    function updateGuestMode() {
        labelIcon.className = 'fas fa-envelope';
        labelText.textContent = 'Email';
        identifierInput.placeholder = 'Masukkan email Anda';
        loginAsInput.value = 'guest';
    }

    // Default: internal mode with NIS
    updateInternalMode('nis');
});
</script>
@endsection