@extends('base.app')

@section('title', 'Reset Password')
@section('class-body', 'bg-light')

@section('content')
<div class="login-page">
    <div class="login-wrapper">
        <div class="card login-card" data-aos="zoom-in" data-aos-duration="800">
            <div class="row no-gutters">
                <!-- Sisi Kiri: Branding & Welcome -->
                <div class="col-md-5 d-none d-md-block">
                    <div class="login-left">
                        <div data-aos="fade-up" data-aos-delay="200" data-aos-duration="1000">
                            <img src="{{ asset('assets/images/logo.png') }}" alt="Logo" class="brand-logo">
                            <h2>Atur Ulang Password</h2>
                            <p>
                                Masukkan password baru Anda untuk memulihkan akses ke akun dashboard Kurasi UMKM.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Sisi Kanan: Form Reset Password -->
                <div class="col-md-7">
                    <div class="login-right">
                        <div class="login-form-wrap" data-aos="fade-left" data-aos-delay="400" data-aos-duration="1000">
                            <h3>Password Baru</h3>
                            <p class="subtitle">Buat password yang kuat dan mudah diingat</p>

                            <form action="{{ route('password.update') }}" method="POST">
                                @csrf
                                <input type="hidden" name="token" value="{{ $token }}">

                                <div class="form-group">
                                    <label for="email">Email</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">
                                                <i data-lucide="mail"></i>
                                            </span>
                                        </div>
                                        <input
                                            type="email"
                                            name="email"
                                            id="email"
                                            class="form-control @error('email') is-invalid @enderror"
                                            value="{{ old('email', $email) }}"
                                            placeholder="Masukkan email"
                                            required
                                            readonly
                                        >
                                    </div>
                                    @error('email')
                                        <div class="text-danger small mt-1 pl-1">
                                            <i data-lucide="alert-circle" class="lucide-xs mr-1"></i> {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="password">Password Baru</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">
                                                <i data-lucide="lock"></i>
                                            </span>
                                        </div>
                                        <input
                                            type="password"
                                            name="password"
                                            id="password"
                                            class="form-control @error('password') is-invalid @enderror"
                                            placeholder="Masukkan password baru"
                                            required
                                            autofocus
                                        >
                                        <div class="input-group-append">
                                            <button class="btn btn-outline-light border-left-0 btn-toggle-password" type="button" style="border: 1px solid #ced4da; border-left: none; background: white;">
                                                <i data-lucide="eye" class="text-muted"></i>
                                            </button>
                                        </div>
                                    </div>
                                    @error('password')
                                        <div class="text-danger small mt-1 pl-1">
                                            <i data-lucide="alert-circle" class="lucide-xs mr-1"></i> {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="password_confirmation">Konfirmasi Password Baru</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">
                                                <i data-lucide="check-square"></i>
                                            </span>
                                        </div>
                                        <input
                                            type="password"
                                            name="password_confirmation"
                                            id="password_confirmation"
                                            class="form-control"
                                            placeholder="Ulangi password baru"
                                            required
                                        >
                                        <div class="input-group-append">
                                            <button class="btn btn-outline-light border-left-0 btn-toggle-password" type="button" style="border: 1px solid #ced4da; border-left: none; background: white;">
                                                <i data-lucide="eye" class="text-muted"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <button type="submit" class="btn btn-dark btn-block btn-login mt-4">
                                    <span>Simpan Perubahan Password</span>
                                    <i data-lucide="save" class="ml-2"></i>
                                </button>
                            </form>

                            <div class="login-footer-text">
                                &copy; {{ date('Y') }} SPK Kurasi UMKM. All rights reserved.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        if (typeof AOS !== 'undefined') {
            AOS.refresh();
        }
    });
</script>
@endpush
