@extends('layouts.app')
@section('title', 'Edit User')
@section('content')
<div class="container py-4">
    <h2><i class="fas fa-user-edit"></i> Edit User</h2>
    <form action="{{ route('admin.update', $user->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label>Nama Lengkap</label>
            <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
        </div>
        <div class="mb-3">
            <label>Email</label>
            <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required>
        </div>
        <div class="mb-3">
            <label>NIS/NIP</label>
            <input type="text" name="nis" class="form-control" value="{{ old('nis', $user->nis) }}" required>
        </div>
        <div class="mb-3">
            <label>Role</label>
            <select name="role" class="form-control" required>
                <option value="guru" {{ (old('role', $user->role) == 'guru') ? 'selected' : '' }}>Guru</option>
                <option value="siswa" {{ (old('role', $user->role) == 'siswa') ? 'selected' : '' }}>Siswa</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Perbarui</button>
        <a href="{{ route('admin.index') }}" class="btn btn-secondary">Batal</a>
    </form>
</div>
@endsection