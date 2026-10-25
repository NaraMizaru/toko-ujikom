@extends('layout.app')
@section('title', 'Pembayaran')

@section('content')
    <div class="container py-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="mb-1">Pembayaran</h2>
                <p class="text-muted mb-0">Upload bukti pembayaran untuk menyelesaikan pesanan.</p>
            </div>
            <span class="badge bg-primary fs-6">{{ count($itemIds) }} item</span>
        </div>

        <div class="row g-4">
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white fw-semibold">Produk yang Dibayar</div>
                    <div class="list-group list-group-flush">
                        @foreach ($items as $item)
                            <div class="list-group-item py-3">
                                <div class="row g-3 align-items-center">
                                    <div class="col-3 col-md-2">
                                        <img src="{{ asset($item->produk->foto) }}"
                                            alt="{{ $item->produk->name }}" class="img-fluid rounded"
                                            style="height: 72px; width: 100%; object-fit: cover;">
                                    </div>
                                    <div class="col-9 col-md-5">
                                        <h6 class="mb-1">{{ $item->produk->name }}</h6>
                                        <p class="text-muted small mb-0">
                                            Rp. {{ number_format($item->harga_satuan, 0, ',', '.') }}
                                            &times; {{ $item->qty }}
                                        </p>
                                    </div>
                                    <div class="col-md-5 text-md-end">
                                        <span class="fw-semibold">
                                            Subtotal Rp. {{ number_format($item->subtotal, 0, ',', '.') }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card border-0 shadow-sm sticky-summary">
                    <div class="card-body">
                        <h5 class="card-title">Ringkasan Pembayaran</h5>
                        <div class="d-flex justify-content-between mb-3">
                            <span class="text-muted">Total</span>
                            <span class="fw-bold text-primary fs-5">
                                Rp. {{ number_format($totalHarga, 0, ',', '.') }}
                            </span>
                        </div>
                        <hr>

                        <form action="{{ route('proses.bayar') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @foreach ($itemIds as $itemId)
                                <input type="hidden" name="item_ids[]" value="{{ $itemId }}">
                            @endforeach

                            <label for="buktiTransaksi" class="form-label fw-semibold">Bukti Pembayaran</label>
                            <input type="file" name="bukti_transaksi" id="buktiTransaksi"
                                class="form-control @error('bukti_transaksi') is-invalid @enderror"
                                accept="image/jpeg,image/png" required>
                            @error('bukti_transaksi')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Format JPG, JPEG, atau PNG. Maksimal 2MB.</div>

                            <button class="btn btn-success w-100 mt-3">Upload Bukti</button>
                        </form>

                        <a href="{{ route('index.keranjang') }}" class="btn btn-outline-secondary w-100 mt-2">
                            Kembali ke Keranjang
                        </a>

                        <p class="text-muted small mt-3 mb-0">
                            Silakan lakukan pembayaran ke bank berikut, lalu unggah bukti pembayaran.
                            Kami akan mengonfirmasi pesanan kamu.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
