@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
<div class="container-fluid py-4">
    <h2><i class="fas fa-cog"></i> Admin Panel</h2>
    <p class="text-muted">Kelola data guru dan siswa</p>
    <a href="{{ route('admin.create') }}" class="btn btn-success mb-4">
        <i class="fas fa-plus-circle"></i> Tambah User Baru
    </a>

    <!-- Tabel Guru -->
    <div class="card shadow-sm mb-5">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0"><i class="fas fa-chalkboard-teacher"></i> Daftar Guru</h5>
        </div>

        <div class="card-body">
            @if($gurus->isEmpty())
                <p class="text-muted">Tidak ada guru terdaftar.</p>
            @else
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Nama</th>
                                <th>NIS/NIP</th>
                                <th>Karya Diunggah</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($gurus as $guru)
                            <tr>
                                <td>{{ $guru->name }}</td>
                                <td>{{ $guru->nis }}</td>
                                <td><span class="badge bg-info">{{ $guru->works_count }}</span></td>
                                <td>
                                    <a href="{{ route('admin.edit', $guru->id) }}" class="btn btn-sm btn-warning"><i class="fas fa-edit"></i></a>
                                    <form action="{{ route('admin.destroy', $guru->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Hapus guru ini?')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>

    <!-- Tabel Siswa -->
    <div class="card shadow-sm">
        <div class="card-header bg-success text-white">
            <h5 class="mb-0"><i class="fas fa-user-graduate"></i> Daftar Siswa</h5>
        </div>
        <div class="card-body">
            @if($siswas->isEmpty())
                <p class="text-muted">Tidak ada siswa terdaftar.</p>
            @else
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Nama</th>
                                <th>NIS/NIP</th>
                                <th>Karya Diunggah</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($siswas as $siswa)
                            <tr>
                                <td>{{ $siswa->name }}</td>
                                <td>{{ $siswa->nis }}</td>
                                <td>
                                    <span class="badge bg-info">{{ $siswa->works_count }}</span>
                                </td>
                                <td>
                                    <a href="{{ route('admin.edit', $siswa->id) }}" class="btn btn-sm btn-warning"><i class="fas fa-edit"></i></a>
                                    <form action="{{ route('admin.destroy', $siswa->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Hapus siswa ini?')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection