@extends('layouts.app')

@section('title', 'Tambah Anggota OSIS')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Tambah Anggota Baru</h2>
        <a href="{{ route('osis.manage') }}" class="btn btn-secondary">Kembali</a>
    </div>

    <div class="card p-4 shadow-sm">
        <form method="POST" action="{{ route('osis.store') }}" enctype="multipart/form-data">
            @csrf

            <div class="mb-3">
                <label class="form-label">Nama Lengkap</label>
                <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Jabatan</label>
                <input type="text" name="role" class="form-control" value="{{ old('role') }}" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Tipe Anggota</label>
                <select name="type" class="form-control" required>
                    <option value="">-- Pilih --</option>
                    <option value="inti" {{ old('type') == 'inti' ? 'selected' : '' }}>7 Inti OSIS</option>
                    <option value="sekbid" {{ old('type') == 'sekbid' ? 'selected' : '' }}>Sekbid</option>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Foto (Opsional)</label>
                <input type="file" name="photo" class="form-control" accept="image/*">
                <div class="form-text">Format: JPG, PNG. Maks: 2MB.</div>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Simpan
                </button>
                <a href="{{ route('osis.manage') }}" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection