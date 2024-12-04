@extends('layout.app')
@section('title', 'Home')

@push('css')
    {{-- CSS Only For This Page --}}
@endpush

@section('content')
    <div class="container mt-5">
        <div class="row">
            @foreach ($produks as $item)
                <div class="col-12 col-md-3">
                    <div class="card h-100">
                        <img src="{{ asset($item->foto) }}" class="card-img-top">
                        <div class="card-body">
                            <h5 class="card-title">{{ $item->name }}</h5>
                            <p class="card-text">Rp. {{ number_format($item->harga, 0, ',', '.',) }}</p>
                        </div>
                        <a href="{{ route('index.detail', $item->id) }}" class="btn btn-primary">Detail</a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection

@push('js')
    {{-- JS Only For This Page --}}
@endpush
