@extends('layout.app')
@section('title', 'Keranjang')

@section('content')
    <div class="container py-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="mb-1">Keranjang Belanja</h2>
                <p class="text-muted mb-0">Pilih barang yang ingin dibayar sekarang.</p>
            </div>
            @if ($transaksi && !$transaksi->items->isEmpty())
                <span class="badge bg-primary fs-6">{{ $transaksi->items->count() }} item</span>
            @endif
        </div>

        @if (!$transaksi || $transaksi->items->isEmpty())
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center py-5">
                    <h4>Keranjang masih kosong</h4>
                    <p class="text-muted">Yuk pilih produk terlebih dahulu.</p>
                    <a href="{{ route('index.home') }}" class="btn btn-primary">Belanja Sekarang</a>
                </div>
            </div>
        @else
            <form id="checkoutForm" action="{{ route('index.bayar') }}" method="GET"></form>

            <div class="row g-4">
                <div class="col-lg-8">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-white d-flex flex-column flex-sm-row justify-content-between gap-2">
                            <div class="form-check mb-0">
                                <input class="form-check-input" type="checkbox" id="pilihSemua">
                                <label class="form-check-label fw-semibold" for="pilihSemua">Pilih Semua</label>
                            </div>
                            <span class="text-muted small">Centang item yang ingin dibayar</span>
                        </div>

                        <div class="list-group list-group-flush">
                            @foreach ($transaksi->items as $item)
                                <div class="list-group-item py-3">
                                    <div class="row g-3 align-items-center">
                                        <div class="col-auto">
                                            <input type="checkbox" name="items[]" value="{{ $item->id }}"
                                                class="form-check-input item-checkbox" form="checkoutForm"
                                                aria-label="Pilih {{ $item->produk->name }}">
                                        </div>
                                        <div class="col-3 col-md-2">
                                            <img src="{{ asset($item->produk->foto) }}"
                                                alt="{{ $item->produk->name }}" class="img-fluid rounded"
                                                style="height: 72px; width: 100%; object-fit: cover;">
                                        </div>
                                        <div class="col-9 col-md-4">
                                            <h6 class="mb-1">{{ $item->produk->name }}</h6>
                                            <p class="text-muted small mb-0">
                                                Harga Rp. {{ number_format($item->harga_satuan, 0, ',', '.') }}
                                            </p>
                                            <p class="fw-semibold mb-0">
                                                Subtotal Rp. {{ number_format($item->subtotal, 0, ',', '.') }}
                                            </p>
                                        </div>
                                        <div class="col-12 col-md-5">
                                            <div class="d-flex flex-column flex-sm-row gap-2 justify-content-md-end">
                                                <form action="{{ route('update.keranjang', $item->id) }}" method="POST"
                                                    class="d-flex gap-2">
                                                    @csrf
                                                    @method('PUT')
                                                    <label for="qty-{{ $item->id }}"
                                                        class="visually-hidden">Jumlah</label>
                                                    <input type="number" name="qty" id="qty-{{ $item->id }}"
                                                        class="form-control" value="{{ $item->qty }}" min="1"
                                                        max="99" style="max-width: 90px;" required>
                                                    <button class="btn btn-outline-warning">Ubah</button>
                                                </form>
                                                <form action="{{ route('hapus.keranjang', $item->id) }}" method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button class="btn btn-outline-danger w-100">Hapus</button>
                                                </form>
                                            </div>
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
                            <h5 class="card-title">Ringkasan</h5>
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">Total item</span>
                                <span>{{ $transaksi->items->sum('qty') }} pcs</span>
                            </div>
                            <div class="d-flex justify-content-between mb-3">
                                <span class="text-muted">Total harga</span>
                                <span class="fw-semibold">
                                    Rp. {{ number_format($transaksi->total_harga, 0, ',', '.') }}
                                </span>
                            </div>
                            <hr>
                            <h5 class="d-flex justify-content-between">
                                <span>Total</span>
                                <span class="text-primary">
                                    Rp. {{ number_format($transaksi->total_harga, 0, ',', '.') }}
                                </span>
                            </h5>
                            <button type="submit" form="checkoutForm" class="btn btn-success w-100 mt-3">
                                Bayar Item Terpilih
                            </button>
                            <a href="{{ route('index.home') }}" class="btn btn-outline-secondary w-100 mt-2">
                                Lanjut Belanja
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection

@push('js')
    <script>
        const pilihSemua = document.getElementById('pilihSemua');

        if (pilihSemua) {
            const itemCheckboxes = document.querySelectorAll('.item-checkbox');

            pilihSemua.addEventListener('change', function () {
                itemCheckboxes.forEach(function (checkbox) {
                    checkbox.checked = pilihSemua.checked;
                });
            });

            itemCheckboxes.forEach(function (checkbox) {
                checkbox.addEventListener('change', function () {
                    pilihSemua.checked = Array.from(itemCheckboxes).every(function (item) {
                        return item.checked;
                    });
                });
            });
        }
    </script>
@endpush
