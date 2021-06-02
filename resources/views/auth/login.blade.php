@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ ('Sign In') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <h1 class="h3 mb-3 fw-normal text-center">Authorization to account</h1>

                        <div class="form-floating mb-3">
                            <input type="email" id="email" name="email" class="form-control @error('email') is-invalid @enderror" placeholder="Name" value="{{ old('email') }}" required autocomplete="email" autofocus>
                            <label for="email">Email address</label>
                            @error('email')
                            <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                            @enderror
                        </div>
                        <div class="form-floating mb-1">
                            <input type="password" id="password" name="password" class="form-control @error('password') is-invalid @enderror" placeholder="name@example.com" required autocomplete="current-password">
                            <label for="password">Password</label>
                            @error('password')
                            <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                            @enderror
                        </div>

                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <label class="form-check-label" for="remember">
                                <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                                {{ ('Remember Me') }}
                            </label>
                            @if (Route::has('password.request'))
                                <a class="btn link-primary btn-outline-light" href="{{ route('password.request') }}">
                                    {{ ('Forgot Your Password?') }}
                                </a>
                            @endif
                        </div>

                        <button class="w-100 bt btn btn-primary" type="submit">Sign In</button>

                        <div class="d-flex align-items-center justify-content-center mt-1">
                            <span>Don't have an account yet?</span>
                            <a href="{{ route('register') }}" class="btn link-primary btn-outline-light">Sign Up</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
