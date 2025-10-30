<div class="sidebar" id="sidebar">
    <div class="text-center mb-4 px-3">
        <h5 class="mb-0">Bakti Nusantara 666</h5>
    </div>

    @if(Auth::check())
        <a href="/dashboard"><i class="fas fa-home"></i> Beranda</a>
        <a href="/upload"><i class="fas fa-upload"></i> Upload Karya</a>

        @if(Auth::user()->isAdmin() || Auth::user()->isGuru())
        <a href="{{ route('osis.manage') }}"><i class="fas fa-users-cog"></i> Kelola OSIS</a>
    @endif

        @if(Auth::user()->isAdmin())
            <a href="/admin"><i class="fas fa-cog"></i> Admin Panel</a>
        @endif

        @if(Auth::user()->isGuru() || Auth::user()->isAdmin())
            <a href="/guru"><i class="fas fa-chalkboard-teacher"></i> Guru Dashboard</a>
        @endif
    @else
        <a href="/"><i class="fas fa-home"></i> Beranda</a>
        <a href="/login"><i class="fas fa-sign-in-alt"></i> Login</a>
    @endif
</div>