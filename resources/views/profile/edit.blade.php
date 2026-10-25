@extends('layout.app')
@section('title', 'Profil')

@section('content')
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-7">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white d-flex justify-content-between align-items-center">
                        <span class="fw-semibold">Profil Saya</span>
                        <span class="badge bg-{{ $user->isAdmin() ? 'dark' : 'primary' }}">
                            {{ ucfirst($user->role) }}
                        </span>
                    </div>
                    <div class="card-body p-4">
                        <p class="text-muted">
                            Nomor HP wajib diisi sebelum checkout. Admin akan menghubungi nomor ini jika ada
                            kendala pembayaran.
                        </p>

                        <form action="{{ route('profile.update') }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="mb-3">
                                <label for="name" class="form-label fw-semibold">Nama</label>
                                <input type="text" name="name" id="name"
                                    class="form-control @error('name') is-invalid @enderror"
                                    value="{{ old('name', $user->name) }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="email" class="form-label fw-semibold">Email</label>
                                <input type="email" id="email" class="form-control" value="{{ $user->email }}" disabled>
                                <div class="form-text">Email tidak dapat diubah.</div>
                            </div>

                            <div class="mb-3">
                                <label for="no_hp" class="form-label fw-semibold">Nomor HP</label>
                                <input type="text" name="no_hp" id="no_hp"
                                    class="form-control @error('no_hp') is-invalid @enderror"
                                    value="{{ old('no_hp', $user->no_hp) }}" placeholder="Contoh: 081234567890"
                                    maxlength="20" required>
                                @error('no_hp')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                            <a href="{{ route('index.home') }}" class="btn btn-outline-secondary">Kembali</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
