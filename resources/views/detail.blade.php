@extends('layout.app')
@section('title', 'Detail Produk')

@section('content')
    <div class="container py-5">
        <nav aria-label="breadcrumb" class="mb-3">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('index.home') }}">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ $produk->name }}</li>
            </ol>
        </nav>

        <div class="row g-4">
            <div class="col-lg-5">
                <div class="card border-0 shadow-sm">
                    <img src="{{ asset($produk->foto) }}" class="card-img-top detail-img"
                        alt="{{ $produk->name }}">
                </div>
            </div>

            <div class="col-lg-7">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body p-4">
                        <span class="badge bg-secondary mb-2">{{ $produk->kategori->name }}</span>
                        <h2 class="mb-3">{{ $produk->name }}</h2>
                        <p class="fs-3 fw-bold text-primary">
                            Rp. {{ number_format($produk->harga, 0, ',', '.') }}
                        </p>
                        <hr>
                        <h5>Keterangan</h5>
                        <p class="text-muted">
                            Ini merupakan detail dari barang yang dijual. Silakan pelajari dengan saksama.
                            Barang yang sudah dibeli tidak dapat dikembalikan lagi.
                        </p>

                        @auth
                            @if (auth()->user()->isAdmin())
                                <div class="alert alert-warning mt-4">
                                    Admin tidak dapat membeli produk. Gunakan dashboard untuk mengelola produk.
                                </div>
                                <a href="{{ route('admin.produk.edit', $produk->id) }}" class="btn btn-primary">
                                    Edit Produk
                                </a>
                            @else
                                <form action="{{ route('post.keranjang', $produk->id) }}" method="POST" class="mt-4">
                                    @csrf
                                    <label for="qty" class="form-label fw-semibold">Jumlah</label>
                                    <div class="row g-2">
                                        <div class="col-4 col-sm-3">
                                            <input type="number" name="qty" id="qty"
                                                class="form-control @error('qty') is-invalid @enderror"
                                                value="{{ old('qty', 1) }}" min="1" max="99" required>
                                            @error('qty')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-8 col-sm-9">
                                            <button type="submit" class="btn btn-success w-100">Tambah ke Keranjang</button>
                                        </div>
                                    </div>
                                </form>
                            @endif
                        @else
                            <div class="alert alert-info mt-4 mb-0">
                                Silakan <a href="{{ route('login') }}" class="alert-link">login</a> terlebih dahulu
                                untuk membeli produk ini.
                            </div>
                        @endauth
                    </div>
                </div>
            </div>
        </div>

        <a href="{{ route('index.home') }}" class="btn btn-outline-secondary mt-4">&larr; Kembali ke Home</a>
    </div>
@endsection
