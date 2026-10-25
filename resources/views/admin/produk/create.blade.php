@extends('layout.app')
@section('title', 'Tambah Produk')

@section('content')
    <div class="container py-5">
        <nav aria-label="breadcrumb" class="mb-3">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('admin.produk.index') }}">Produk</a></li>
                <li class="breadcrumb-item active" aria-current="page">Tambah</li>
            </ol>
        </nav>

        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white fw-semibold">Form Tambah Produk</div>
                    <div class="card-body p-4">
                        <form action="{{ route('admin.produk.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf

                            <div class="mb-3">
                                <label for="name" class="form-label fw-semibold">Nama Produk</label>
                                <input type="text" name="name" id="name"
                                    class="form-control @error('name') is-invalid @enderror"
                                    value="{{ old('name') }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="kategori_id" class="form-label fw-semibold">Kategori</label>
                                <select name="kategori_id" id="kategori_id"
                                    class="form-select @error('kategori_id') is-invalid @enderror" required>
                                    <option value="">Pilih kategori</option>
                                    @foreach ($kategoris as $kategori)
                                        <option value="{{ $kategori->id }}"
                                            @selected(old('kategori_id') == $kategori->id)>
                                            {{ $kategori->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('kategori_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="harga" class="form-label fw-semibold">Harga</label>
                                <input type="number" name="harga" id="harga"
                                    class="form-control @error('harga') is-invalid @enderror"
                                    value="{{ old('harga') }}" min="0" required>
                                @error('harga')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="foto" class="form-label fw-semibold">Foto Produk</label>
                                <input type="file" name="foto" id="foto"
                                    class="form-control @error('foto') is-invalid @enderror"
                                    accept="image/jpeg,image/png" required>
                                @error('foto')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">Format JPG, JPEG, atau PNG. Maksimal 2MB.</div>
                            </div>

                            <button type="submit" class="btn btn-primary">Simpan Produk</button>
                            <a href="{{ route('admin.produk.index') }}" class="btn btn-outline-secondary">Batal</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
