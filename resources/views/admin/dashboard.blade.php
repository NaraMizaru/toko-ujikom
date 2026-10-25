@extends('layout.app')
@section('title', 'Dashboard Admin')

@section('content')
    <div class="container py-5">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
            <div>
                <h2 class="mb-1">Dashboard Admin</h2>
                <p class="text-muted mb-0">Pantau pembayaran dan kelola data toko.</p>
            </div>
            <a href="{{ route('admin.transaksi.index') }}" class="btn btn-primary">Lihat Semua Transaksi</a>
        </div>

        <div class="row g-3 mb-4">
            <div class="col-6 col-lg-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <span class="text-muted small d-block">Menunggu Verifikasi</span>
                        <span class="fs-3 fw-bold text-warning">{{ $menunggu }}</span>
                    </div>
                </div>
            </div>
            <div class="col-6 col-lg-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <span class="text-muted small d-block">Diterima</span>
                        <span class="fs-3 fw-bold text-success">{{ $diterima }}</span>
                    </div>
                </div>
            </div>
            <div class="col-6 col-lg-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <span class="text-muted small d-block">Ditolak</span>
                        <span class="fs-3 fw-bold text-danger">{{ $ditolak }}</span>
                    </div>
                </div>
            </div>
            <div class="col-6 col-lg-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <span class="text-muted small d-block">Total Produk</span>
                        <span class="fs-3 fw-bold text-primary">{{ $produk }}</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <span class="fw-semibold">Transaksi Menunggu Verifikasi</span>
                <a href="{{ route('admin.transaksi.index', ['status' => 'dibayar']) }}"
                    class="btn btn-sm btn-outline-primary">Lihat Semua</a>
            </div>
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Invoice</th>
                            <th>Customer</th>
                            <th>No HP</th>
                            <th class="text-end">Total</th>
                            <th>Tanggal</th>
                            <th class="text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($transaksis as $transaksi)
                            <tr>
                                <td class="fw-semibold">{{ $transaksi->kode }}</td>
                                <td>{{ $transaksi->user->name }}</td>
                                <td>{{ $transaksi->user->no_hp ?? '-' }}</td>
                                <td class="text-end">
                                    Rp. {{ number_format($transaksi->total_harga, 0, ',', '.') }}
                                </td>
                                <td>{{ $transaksi->created_at->format('d-m-Y H:i') }}</td>
                                <td class="text-end">
                                    <a href="{{ route('admin.transaksi.show', $transaksi) }}"
                                        class="btn btn-sm btn-primary">Verifikasi</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">
                                    Tidak ada transaksi yang menunggu verifikasi.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
