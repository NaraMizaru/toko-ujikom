@extends('layout.app')
@section('title', 'Login')

@push('css')
    {{-- CSS Only For This Page --}}
@endpush

@section('content')
    <div class="container mt-5">
        <form action="{{ route('post.login') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="" class="form-label">Email Address</label>
                <input type="email" name="email" id="email" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="" class="form-label">Password</label>
                <input type="password" name="password" id="password" class="form-control" required>
                @if (Session::has('status'))
                    <div><span class="text-danger">{{ Session::get('status') }}</span></div>
                @endif
            </div>
            <button class="btn btn-primary" type="submit">Login</button>
        </form>
    </div>
@endsection

@push('js')
    {{-- JS Only For This Page --}}
@endpush
