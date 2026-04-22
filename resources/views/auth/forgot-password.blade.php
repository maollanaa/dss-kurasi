@extends('base.app')

@section('title', 'Lupa Password')
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
                            <h2>Pemulihan Kata Sandi</h2>
                            <p>
                                Masukkan email Anda yang terdaftar untuk mendapatkan instruksi pengaturan ulang password.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Sisi Kanan: Form Forgot Password -->
                <div class="col-md-7">
                    <div class="login-right">
                        <div class="login-form-wrap" data-aos="fade-left" data-aos-delay="400" data-aos-duration="1000">
                            <a href="{{ route('login') }}" class="btn btn-link p-0 mb-4 text-decoration-none text-dark d-flex align-items-center">
                                <i data-lucide="arrow-left" class="mr-2"></i> Kembali ke Login
                            </a>
                            <h3>Lupa Password?</h3>
                            <p class="subtitle">Kami akan mengirimkan link reset ke email Anda</p>

                            @if (session('status'))
                                <div class="alert alert-success alert-dismissible fade show mb-4 border-0 shadow-sm" role="alert" style="border-radius: 12px;">
                                    <div class="d-flex align-items-center">
                                        <i data-lucide="check-circle" class="mr-2 lucide-sm"></i>
                                        <span>{{ session('status') }}</span>
                                    </div>
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                            @endif

                            <form action="{{ route('password.email') }}" method="POST">
                                @csrf

                                <div class="form-group">
                                    <label for="email">Email Terdaftar</label>
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
                                            value="{{ old('email') }}"
                                            placeholder="Masukkan email"
                                            required
                                            autofocus
                                        >
                                    </div>
                                    @error('email')
                                        <div class="text-danger small mt-1 pl-1">
                                            <i data-lucide="alert-circle" class="lucide-xs mr-1"></i> {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <button type="submit" class="btn btn-dark btn-block btn-login mt-4">
                                    <span>Kirim Link Reset Password</span>
                                    <i data-lucide="send" class="ml-2"></i>
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
