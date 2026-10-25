@extends('layout.app')
@section('title', 'Kelola Kategori')

@section('content')
    <div class="container py-5">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
            <div>
                <h2 class="mb-1">Kelola Kategori</h2>
                <p class="text-muted mb-0">Kategori yang masih dipakai produk tidak dapat dihapus.</p>
            </div>
            <a href="{{ route('admin.kategori.create') }}" class="btn btn-primary">Tambah Kategori</a>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Nama Kategori</th>
                            <th class="text-center">Jumlah Produk</th>
                            <th class="text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($kategoris as $kategori)
                            <tr>
                                <td class="fw-semibold">{{ $kategori->name }}</td>
                                <td class="text-center">{{ $kategori->produk_count }}</td>
                                <td class="text-end">
                                    <div class="d-flex justify-content-end gap-2">
                                        <a href="{{ route('admin.kategori.edit', $kategori) }}"
                                            class="btn btn-sm btn-outline-warning">Edit</a>
                                        <form action="{{ route('admin.kategori.destroy', $kategori) }}" method="POST"
                                            onsubmit="return confirm('Hapus kategori ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger">Hapus</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center text-muted py-4">Belum ada kategori.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
