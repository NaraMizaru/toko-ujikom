@extends('layout.app')
@section('title', 'Login')

@section('content')
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-5">
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-4">
                        <div class="text-center mb-4">
                            <h3 class="mb-1">Login</h3>
                            <p class="text-muted mb-0">Masuk untuk mulai berbelanja.</p>
                        </div>

                        <form action="{{ route('post.login') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label for="email" class="form-label">Email Address</label>
                                <input type="email" name="email" id="email"
                                    class="form-control @error('email') is-invalid @enderror"
                                    value="{{ old('email') }}" required autofocus>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" name="password" id="password"
                                    class="form-control @error('password') is-invalid @enderror" required>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <button class="btn btn-primary w-100" type="submit">Login</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
