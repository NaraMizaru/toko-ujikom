@extends('layout.app')
@section('title', 'Home')

@push('css')
    {{-- CSS Only For This Page --}}
@endpush

@section('content')
    <div class="container mt-5">
        <h5>Summary</h5>
        <hr>
        @foreach ($detailTransaksi as $item)
            <div class="card mt-3">
                <div class="row">
                    <div class="col-2 p-4">
                        <img src="{{ asset($item->produk->foto) }}" alt="" class="img-thumbnail">
                    </div>
                    <div class="col-10">
                        <h3 class="card-title">{{ $item->produk->name }}</h3>
                        <h5 class="card-title">Invoice : {{ $item->transaksi->kode }}</h5>
                        <hr>
                        <p class="card-text">Harga Rp. {{ number_format($item->produk->harga, 0, ',', '.') }}</p>
                        <p class="card-text">Banyak : {{ $item->qty }}</p>
                        <hr>
                        <p class="card-text">Total Rp. {{ number_format($item->total_harga, 0, ',', '.') }}</p>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endsection

@push('js')
    {{-- JS Only For This Page --}}
@endpush
