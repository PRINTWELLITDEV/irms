@extends('layouts.app')

@section('body-class', 'login-body')

@section('content')

    <img src="{{ asset('uploads/img/login-visual.jpg') }}" alt="Login Background" class="login-bg-img" />
    <div class="login-split-container" data-aos="fade-down">
        <div class="login-card">
            <div class="login-visual position-relative">
                <div class="login-app-logo">
                    <img src="{{ asset('uploads/img/irms-logo.png') }}" alt="IRMS Logo">
                </div>
                <img class="login-visual-img" src="{{ asset('uploads/img/login-visual.jpg') }}" alt="Login Visual" />
            </div>
            <div class="login-form-panel">
                <h1>Log in to IRMS!</h1>
                <div class="mb-3 form-text">Inventory Rack Management System – Secure access for your inventory and rack operations.</div>
                <form method="POST" action="{{ route('login.submit') }}">
                    @csrf
                    <div class="mb-3">
                        <label for="userid" class="form-label">User ID</label>
                        <input id="userid" type="text"
                            class="form-control @error('userid') is-invalid @enderror"
                            name="userid"
                            value="{{ old('userid', $rememberedUserId ?? '') }}"
                            required autofocus
                            placeholder="Enter your User ID">
                        @error('userid')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input id="password" type="password"
                            class="form-control @error('password') is-invalid @enderror"
                            name="password"
                            required
                            placeholder="Enter your Password">
                        @error('password')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="mb-3 d-flex align-items-center justify-content-between">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="remember"
                                id="remember" {{ old('remember', $rememberChecked ?? false) ? 'checked' : '' }}>
                            <label class="form-check-label" for="remember">
                                Remember
                            </label>
                        </div>
                        @if (Route::has('password.request'))
                            <a class="forgot-link" href="{{ route('password.request') }}">
                                Forgot your password?
                            </a>
                        @endif
                    </div>
                    <div class="mb-3">
                        <button type="submit" class="btn btn-primary w-100">
                            NEXT &rarr;
                        </button>
                    </div>
                    @if (Route::has('register'))
                    <div class="text-center">
                        <a href="{{ route('register') }}" class="create-account">Create account</a>
                    </div>
                    @endif
                </form>
            </div>
        </div>
    </div>
@endsection
