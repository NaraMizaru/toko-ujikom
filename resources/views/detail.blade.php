@extends('layout.app')
@section('title', 'Home')

@push('css')
    {{-- CSS Only For This Page --}}
@endpush

@section('content')
    <div class="container mt-5">
        <form action="{{ route('post.keranjang', $produk->id) }}" method="POST">
            @csrf
            <div class="row">
                <div class="col-4">
                    <div class="card">
                        <img src="{{ asset($produk->foto) }}" alt="">
                    </div>
                </div>

                <div class="col-4">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">{{ $produk->name }}</h5>
                            <p class="card-text">Rp. {{ number_format($produk->harga, 0, ',' . '.') }}</p>
                            <input type="number" name="qty" id="qty" class="form-control" placeholder="Banyak">
                            <hr>
                            <h5>Keterangan:</h5>
                            <p>Ini merupakan detail dari barang yang di jual silahkan pelajari dengan seksama. Barang yang
                                sudah di beli tidak dapat di kembalikan lagi</p>
                        </div>
                    </div>
                </div>

                <div class="col-4">
                    <div class="card">
                        <div class="card-body">
                            <h5>Kategori: {{ $produk->kategori->name }}</h5>

                        </div>
                    </div>
                    <button type="submit" class="btn btn-success mt-3 w-100">Beli</button>
                </div>

            </div>
        </form>
    </div>
@endsection

@push('js')
    {{-- JS Only For This Page --}}
@endpush
