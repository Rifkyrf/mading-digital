@extends('layouts.app')

@section('title', 'Edit Anggota OSIS')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Edit Anggota: {{ $member->name }}</h2>
        <a href="{{ route('osis.manage') }}" class="btn btn-secondary">Kembali</a>
    </div>

    <div class="card p-4 shadow-sm">
        <form method="POST" action="{{ route('osis.update', $member) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label class="form-label">Nama Lengkap</label>
                <input type="text" name="name" class="form-control" value="{{ old('name', $member->name) }}" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Jabatan</label>
                <input type="text" name="role" class="form-control" value="{{ old('role', $member->role) }}" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Tipe Anggota</label>
                <select name="type" class="form-control" required>
                    <option value="inti" {{ (old('type', $member->type) == 'inti') ? 'selected' : '' }}>7 Inti OSIS</option>
                    <option value="sekbid" {{ (old('type', $member->type) == 'sekbid') ? 'selected' : '' }}>Sekbid</option>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Foto Saat Ini</label><br>
                <img src="{{ $member->photo_url }}" style="height: 150px; object-fit: cover; border-radius: 8px;">
            </div>

            <div class="mb-3">
                <label class="form-label">Ganti Foto (Opsional)</label>
                <input type="file" name="photo" class="form-control" accept="image/*">
                <div class="form-text">Biarkan kosong jika tidak ingin mengganti.</div>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Perbarui
                </button>
                <a href="{{ route('osis.manage') }}" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection