@extends('layouts.app')

@section('title', 'Kelola OSIS')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Kelola Struktur OSIS</h2>
        <a href="{{ route('osis.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Tambah Anggota
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <!-- 7 Inti -->
    <h4 class="mt-5">7 Inti OSIS</h4>
    <div class="row g-3">
        @foreach($inti as $member)
            <div class="col-md-4">
                <div class="card">
                    <img src="{{ $member->photo_url }}" class="card-img-top" style="height: 200px; object-fit: cover;">
                    <div class="card-body">
                        <h5>{{ $member->name }}</h5>
                        <p class="text-muted">{{ $member->role }}</p>
                        <div class="d-flex gap-2">
                            <a href="{{ route('osis.edit', $member) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                            <form action="{{ route('osis.destroy', $member) }}" method="POST" onsubmit="return confirm('Hapus?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger">Hapus</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Sekbid -->
    <h4 class="mt-5">Sekbid 1–10</h4>
    <div class="row g-3">
        @foreach($sekbid as $member)
            <div class="col-md-4">
                <div class="card">
                    <img src="{{ $member->photo_url }}" class="card-img-top" style="height: 200px; object-fit: cover;">
                    <div class="card-body">
                        <h5>{{ $member->name }}</h5>
                        <p class="text-muted">{{ $member->role }}</p>
                        <div class="d-flex gap-2">
                            <a href="{{ route('osis.edit', $member) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                            <form action="{{ route('osis.destroy', $member) }}" method="POST" onsubmit="return confirm('Hapus?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger">Hapus</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection