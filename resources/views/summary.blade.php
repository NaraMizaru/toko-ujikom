@extends('layout.app')
@section('title', 'Summary')

@section('content')
    <div class="container py-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="mb-1">Riwayat Transaksi</h2>
                <p class="text-muted mb-0">Daftar transaksi yang sudah kamu bayar.</p>
            </div>
            <span class="badge bg-primary fs-6">{{ $transaksis->count() }} transaksi</span>
        </div>

        @forelse ($transaksis as $transaksi)
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white d-flex flex-column flex-sm-row justify-content-between gap-2">
                    <div>
                        <span class="text-muted small d-block">Invoice</span>
                        <span class="fw-semibold">{{ $transaksi->kode }}</span>
                    </div>
                    <div class="text-sm-end">
                        <span class="badge bg-{{ $transaksi->statusColor() }} mb-1">
                            {{ $transaksi->statusLabel() }}
                        </span>
                        <span class="text-muted small d-block">
                            {{ $transaksi->created_at->format('d-m-Y H:i') }}
                        </span>
                    </div>
                </div>

                <div class="card-body p-0">
                    @if ($transaksi->status === 'dibayar')
                        <div class="alert alert-warning rounded-0 border-start-0 border-end-0 mb-0">
                            Bukti pembayaran sudah diunggah dan sedang menunggu verifikasi admin.
                        </div>
                    @elseif ($transaksi->status === 'diterima')
                        <div class="alert alert-success rounded-0 border-start-0 border-end-0 mb-0">
                            Pembayaran sudah diterima dan diverifikasi admin.
                        </div>
                    @elseif ($transaksi->status === 'ditolak')
                        <div class="alert alert-danger rounded-0 border-start-0 border-end-0 mb-0">
                            Pembayaran ditolak. Admin akan menghubungi kamu melalui nomor HP yang terdaftar.
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Produk</th>
                                    <th class="text-end">Harga</th>
                                    <th class="text-center">Qty</th>
                                    <th class="text-end">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($transaksi->items as $item)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center gap-2">
                                                <img src="{{ asset($item->produk->foto) }}"
                                                    alt="{{ $item->produk->name }}" class="rounded"
                                                    style="width: 48px; height: 48px; object-fit: cover;">
                                                <span class="fw-semibold">{{ $item->produk->name }}</span>
                                            </div>
                                        </td>
                                        <td class="text-end">
                                            Rp. {{ number_format($item->harga_satuan, 0, ',', '.') }}
                                        </td>
                                        <td class="text-center">{{ $item->qty }}</td>
                                        <td class="text-end">
                                            Rp. {{ number_format($item->subtotal, 0, ',', '.') }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="3" class="text-end">Total</th>
                                    <th class="text-end text-primary">
                                        Rp. {{ number_format($transaksi->total_harga, 0, ',', '.') }}
                                    </th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>

                @if ($transaksi->bukti_transaksi)
                    <div class="card-footer bg-white">
                        <a href="{{ route('transaksi.bukti', $transaksi) }}" target="_blank"
                            class="btn btn-sm btn-outline-primary">
                            Lihat Bukti Pembayaran
                        </a>
                    </div>
                @endif
            </div>
        @empty
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center py-5">
                    <h4>Belum ada transaksi</h4>
                    <p class="text-muted">Transaksi yang sudah dibayar akan muncul di sini.</p>
                    <a href="{{ route('index.home') }}" class="btn btn-primary">Mulai Belanja</a>
                </div>
            </div>
        @endforelse
    </div>
@endsection
