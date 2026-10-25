@extends('layout.app')
@section('title', 'Detail Transaksi')

@section('content')
    <div class="container py-5">
        <nav aria-label="breadcrumb" class="mb-3">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.transaksi.index') }}">Transaksi</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ $transaksi->kode }}</li>
            </ol>
        </nav>

        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
            <div>
                <h2 class="mb-1">{{ $transaksi->kode }}</h2>
                <p class="text-muted mb-0">{{ $transaksi->created_at->format('d-m-Y H:i') }}</p>
            </div>
            <span class="badge bg-{{ $transaksi->statusColor() }} fs-6">
                {{ $transaksi->statusLabel() }}
            </span>
        </div>

        <div class="row g-4">
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white fw-semibold">Data Customer</div>
                    <div class="card-body">
                        <h5 class="mb-1">{{ $transaksi->user->name }}</h5>
                        <p class="text-muted mb-3">{{ $transaksi->user->email }}</p>

                        <span class="text-muted small d-block">Nomor HP</span>
                        @if ($transaksi->user->no_hp)
                            <p class="fw-semibold mb-2">{{ $transaksi->user->no_hp }}</p>
                            <div class="d-flex gap-2">
                                <a href="tel:{{ $transaksi->user->no_hp }}"
                                    class="btn btn-sm btn-outline-secondary">Telepon</a>
                                @if ($transaksi->user->whatsappNumber())
                                    <a href="https://wa.me/{{ $transaksi->user->whatsappNumber() }}"
                                        target="_blank" rel="noopener" class="btn btn-sm btn-success">WhatsApp</a>
                                @endif
                            </div>
                        @else
                            <p class="text-danger mb-0">Nomor HP belum diisi.</p>
                        @endif
                    </div>
                </div>

                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white fw-semibold">Bukti Pembayaran</div>
                    <div class="card-body">
                        @if ($transaksi->bukti_transaksi)
                            <a href="{{ route('transaksi.bukti', $transaksi) }}" target="_blank">
                                <img src="{{ route('transaksi.bukti', $transaksi) }}"
                                    alt="Bukti pembayaran {{ $transaksi->kode }}" class="img-fluid rounded border">
                            </a>
                            <a href="{{ route('transaksi.bukti', $transaksi) }}" target="_blank"
                                class="btn btn-sm btn-outline-primary w-100 mt-3">Buka Gambar</a>
                        @else
                            <div class="alert alert-warning mb-0">Bukti pembayaran belum tersedia.</div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="col-lg-8">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white fw-semibold">Detail Produk</div>
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
                    <div class="card-footer bg-white">
                        @if ($transaksi->status === 'dibayar')
                            <div class="d-flex flex-column flex-sm-row gap-2">
                                <form action="{{ route('admin.transaksi.terima', $transaksi) }}" method="POST"
                                    onsubmit="return confirm('Terima pembayaran ini?')">
                                    @csrf
                                    <button type="submit" class="btn btn-success">Terima Pembayaran</button>
                                </form>
                                <form action="{{ route('admin.transaksi.tolak', $transaksi) }}" method="POST"
                                    onsubmit="return confirm('Tolak pembayaran ini? Status akan menjadi final.')">
                                    @csrf
                                    <button type="submit" class="btn btn-danger">Tolak Pembayaran</button>
                                </form>
                            </div>
                        @elseif ($transaksi->status === 'diterima')
                            <div class="alert alert-success mb-0">Pembayaran sudah diterima.</div>
                        @elseif ($transaksi->status === 'ditolak')
                            <div class="alert alert-danger mb-0">
                                Pembayaran ditolak. Hubungi customer melalui nomor HP yang tersedia.
                            </div>
                        @endif
                    </div>
                </div>

                <a href="{{ route('admin.transaksi.index') }}" class="btn btn-outline-secondary mt-3">
                    &larr; Kembali ke Daftar Transaksi
                </a>
            </div>
        </div>
    </div>
@endsection
