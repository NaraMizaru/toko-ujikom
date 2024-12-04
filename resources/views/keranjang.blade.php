@extends('layout.app')
@section('title', 'Home')

@push('css')
    {{-- CSS Only For This Page --}}
@endpush

@section('content')
    <div class="container mt-5">
        <h5>Keranjang</h5>
        <hr>

        @foreach ($keranjang as $item)
            <div class="card mt-3">
                <div class="row">
                    <div class="col-2 p4">
                        <img src="{{ asset($item->produk->foto) }}" alt="" class="img-thumbnail">
                    </div>
                    <div class="col-8">
                        <div class="card-body">
                            <h3 class="card-title">{{ $item->produk->name }}</h3>
                            <hr>
                            <p class="card-text">Harga Rp. {{ number_format($item->produk->harga, 0, ',', '.') }}</p>
                            <input type="number" name="qty" id="qty" class="form-control"
                                value="{{ $item->qty }}" required>
                            <hr>
                            <p class="card-text">Total Rp. {{ number_format($item->total_harga, 0, ',', '.') }}</p>
                        </div>
                    </div>
                    <div class="col-2 p-5">
                        <a href="{{ route('index.bayar', $item->id) }}" class="btn btn-info">Bayar</a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endsection

@push('js')
    {{-- JS Only For This Page --}}
@endpush
