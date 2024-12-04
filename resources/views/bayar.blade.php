@extends('layout.app')
@section('title', 'Home')

@push('css')
    {{-- CSS Only For This Page --}}
@endpush

@section('content')
    <div class="container mt-5">
        <form action="{{ route('proses.bayar', $detailTransaksi->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            <h5>Upload Bukti Pembayaran</h5>
            <hr>
            <div class="row">
                <div class="col-4">
                    <div class="card">
                        <img src="{{ asset($produk->foto) }}" alt="" class="card-img-top">
                    </div>
                </div>
                <div class="col-8">
                    <div class="card">
                        <div class="card-body">
                            <h3 class="card-title">{{ $produk->name }}</h3>
                            <hr>
                            <p class="card-text">Rp. {{ number_format($produk->harga, 0, ',', '.') }}</p>
                            <p class="card-text">Rp. {{ number_format($detailTransaksi->total_harga, 0, ',', '.') }}</p>
                            <p class="card-text">Banyak: {{ $detailTransaksi->qty }}</p>
                            <hr>
                            <div class="mb-2">
                              <label for=""><b>Bukti Pembayaran</b></label>
                              <input type="file" name="bukti_transaksi" id="buktiTransaksi" class="form-control" accept="image/*" required>
                              <hr>
                              <h5>Keterangan :</h5>
                              <p>Silahkan lakukan pembayaran ke bank berikut dan tunggu konfirmasi dari kami</p>
                              <button class="btn btn-success">Upload</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection

@push('js')
    {{-- JS Only For This Page --}}
@endpush
