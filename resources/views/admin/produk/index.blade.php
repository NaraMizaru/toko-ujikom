@extends('layout.app')
@section('title', 'Kelola Produk')

@section('content')
    <div class="container py-5">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
            <div>
                <h2 class="mb-1">Kelola Produk</h2>
                <p class="text-muted mb-0">Tambah, ubah, atau hapus produk toko.</p>
            </div>
            <a href="{{ route('admin.produk.create') }}" class="btn btn-primary">Tambah Produk</a>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Foto</th>
                            <th>Nama Produk</th>
                            <th>Kategori</th>
                            <th class="text-end">Harga</th>
                            <th class="text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($produks as $produk)
                            <tr>
                                <td>
                                    <img src="{{ asset($produk->foto) }}" alt="{{ $produk->name }}" class="rounded"
                                        style="width: 64px; height: 64px; object-fit: cover;">
                                </td>
                                <td class="fw-semibold">{{ $produk->name }}</td>
                                <td>{{ $produk->kategori->name }}</td>
                                <td class="text-end">
                                    Rp. {{ number_format($produk->harga, 0, ',', '.') }}
                                </td>
                                <td class="text-end">
                                    <div class="d-flex justify-content-end gap-2">
                                        <a href="{{ route('admin.produk.edit', $produk) }}"
                                            class="btn btn-sm btn-outline-warning">Edit</a>
                                        <form action="{{ route('admin.produk.destroy', $produk) }}" method="POST"
                                            onsubmit="return confirm('Hapus produk ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger">Hapus</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">Belum ada produk.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
