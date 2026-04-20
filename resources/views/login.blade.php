@extends('base.app')

@section('title', 'Login')
@section('class-body', 'bg-light')

@section('content')
<div class="login-page">
    <div class="login-wrapper">
        <div class="card login-card">
            <div class="row no-gutters">
                <div class="col-md-5 d-none d-md-block">
                    <div class="login-left">
                        <img src="{{ asset('assets/images/logo.png') }}" alt="Logo" class="brand-logo">
                        <h2>Sistem Pendukung Keputusan Kurasi Produk UMKM</h2>
                        <p>
                            Selamat datang kembali. Silakan login untuk mengakses dashboard
                            dan mengelola data sistem.
                        </p>
                    </div>
                </div>

                <div class="col-md-7">
                    <div class="login-right">
                        <div class="login-form-wrap">
                            <h3>Login</h3>
                            <p class="subtitle">Masuk ke akun Anda</p>

                            <form action="{{ route('login') }}" method="POST">
                                @csrf

                                <div class="form-group">
                                    <label for="email">Email</label>
                                    <input
                                        type="email"
                                        name="email"
                                        id="email"
                                        class="form-control @error('email') is-invalid @enderror"
                                        value="{{ old('email') }}"
                                        placeholder="Masukkan email"
                                        required
                                        autofocus
                                    >
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="password">Password</label>
                                    <input
                                        type="password"
                                        name="password"
                                        id="password"
                                        class="form-control @error('password') is-invalid @enderror"
                                        placeholder="Masukkan password"
                                        required
                                    >
                                    @error('password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group d-flex justify-content-between align-items-center">
                                    <div class="custom-control custom-checkbox">
                                        <input
                                            type="checkbox"
                                            class="custom-control-input"
                                            id="remember"
                                            name="remember"
                                        >
                                        <label class="custom-control-label" for="remember">
                                            Remember me
                                        </label>
                                    </div>

                                    <a href="#" class="text-dark">Lupa password?</a>
                                </div>

                                <button type="submit" class="btn btn-dark btn-block btn-login">
                                    Masuk
                                </button>
                            </form>

                            <div class="login-footer-text">
                                &copy; {{ date('Y') }} 3S Admin. All rights reserved.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection