@extends('layout.app')
@section('title', 'Verifikasi Transaksi')

@section('content')
    <div class="container py-5">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
            <div>
                <h2 class="mb-1">Verifikasi Transaksi</h2>
                <p class="text-muted mb-0">Periksa bukti pembayaran lalu terima atau tolak transaksi.</p>
            </div>
            <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary">Kembali ke Dashboard</a>
        </div>

        <div class="d-flex flex-wrap gap-2 mb-3">
            <a href="{{ route('admin.transaksi.index') }}"
                class="btn btn-sm {{ ! $status ? 'btn-primary' : 'btn-outline-primary' }}">Semua</a>
            <a href="{{ route('admin.transaksi.index', ['status' => 'dibayar']) }}"
                class="btn btn-sm {{ $status === 'dibayar' ? 'btn-warning' : 'btn-outline-warning' }}">
                Menunggu ({{ $counts['dibayar'] }})
            </a>
            <a href="{{ route('admin.transaksi.index', ['status' => 'diterima']) }}"
                class="btn btn-sm {{ $status === 'diterima' ? 'btn-success' : 'btn-outline-success' }}">
                Diterima ({{ $counts['diterima'] }})
            </a>
            <a href="{{ route('admin.transaksi.index', ['status' => 'ditolak']) }}"
                class="btn btn-sm {{ $status === 'ditolak' ? 'btn-danger' : 'btn-outline-danger' }}">
                Ditolak ({{ $counts['ditolak'] }})
            </a>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Invoice</th>
                            <th>Customer</th>
                            <th>No HP</th>
                            <th class="text-end">Total</th>
                            <th>Status</th>
                            <th>Tanggal</th>
                            <th class="text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($transaksis as $transaksi)
                            <tr>
                                <td class="fw-semibold">{{ $transaksi->kode }}</td>
                                <td>{{ $transaksi->user->name }}</td>
                                <td>
                                    @if ($transaksi->user->no_hp)
                                        <a href="tel:{{ $transaksi->user->no_hp }}">
                                            {{ $transaksi->user->no_hp }}
                                        </a>
                                    @else
                                        -
                                    @endif
                                </td>
                                <td class="text-end">
                                    Rp. {{ number_format($transaksi->total_harga, 0, ',', '.') }}
                                </td>
                                <td>
                                    <span class="badge bg-{{ $transaksi->statusColor() }}">
                                        {{ $transaksi->statusLabel() }}
                                    </span>
                                </td>
                                <td>{{ $transaksi->created_at->format('d-m-Y H:i') }}</td>
                                <td class="text-end">
                                    <a href="{{ route('admin.transaksi.show', $transaksi) }}"
                                        class="btn btn-sm btn-outline-primary">Detail</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted py-4">
                                    Belum ada transaksi.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
