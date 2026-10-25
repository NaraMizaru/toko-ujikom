@extends('layout.app')
@section('title', 'Home')

@section('content')
    <div class="container py-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="mb-1">Daftar Produk</h2>
                <p class="text-muted mb-0">Pilih produk yang ingin kamu beli.</p>
            </div>
        </div>

        @if ($produks->isEmpty())
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center py-5">
                    <h4>Belum ada produk</h4>
                    <p class="text-muted mb-0">Produk akan muncul di sini setelah ditambahkan.</p>
                </div>
            </div>
        @else
            <div class="row g-4">
                @foreach ($produks as $item)
                    <div class="col-12 col-sm-6 col-lg-4 col-xl-3">
                        <div class="card h-100 border-0 shadow-sm">
                            <img src="{{ asset($item->foto) }}" class="card-img-top product-img"
                                alt="{{ $item->name }}">
                            <div class="card-body d-flex flex-column">
                                <span class="badge bg-secondary align-self-start mb-2">{{ $item->kategori->name }}</span>
                                <h5 class="card-title line-clamp-2">{{ $item->name }}</h5>
                                <p class="card-text text-primary fw-bold mt-auto">
                                    Rp. {{ number_format($item->harga, 0, ',', '.') }}
                                </p>
                                <a href="{{ route('index.detail', $item->id) }}"
                                    class="btn btn-primary w-100">Lihat Detail</a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
@endsection
