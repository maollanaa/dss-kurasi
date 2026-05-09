@extends('base.app')

@section('title', 'Hasil Kurasi')

@section('content')
<div class="container-fluid p-0">
    <div class="row no-gutters">
        @include('layouts.sidebar')

        <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-0 dashboard-main">
            @include('layouts.navbar')

            <div class="px-4 py-3 dashboard-content" data-aos="fade-up">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h4 class="font-weight-bold text-primary mb-1">Hasil Kurasi</h4>
                        <p class="text-muted small mb-0">Daftar periode kurasi yang telah selesai diselesaikan.</p>
                    </div>
                </div>

                @if(session('error'))
                    <div class="alert alert-danger border-0 shadow-sm mb-4" role="alert">
                        <i data-lucide="alert-circle" class="mr-2"></i> {{ session('error') }}
                    </div>
                @endif

                <div class="row">
                    @forelse($periodes as $periode)
                        <div class="col-md-6 col-lg-4 mb-4">
                            <div class="card shadow-sm h-100 border-0 rounded-lg overflow-hidden card-hover-up">
                                <div class="card-body p-4">
                                    <div class="d-flex justify-content-between align-items-start mb-3">
                                        <h5 class="card-title font-weight-bold text-dark mb-0">
                                            {{ $periode->nama_periode }}
                                        </h5>
                                        <span class="badge badge-pill badge-success px-3 py-2">
                                            Selesai
                                        </span>
                                    </div>
                                    
                                    <div class="mb-3 text-muted small">
                                        <div class="d-flex align-items-center mb-1">
                                            <i data-lucide="calendar" class="mr-2" style="width: 14px; height: 14px;"></i>
                                            {{ \Carbon\Carbon::parse($periode->tanggal_kurasi)->translatedFormat('d F Y') }}
                                        </div>
                                        <div class="d-flex align-items-center mb-1">
                                            <i data-lucide="user" class="mr-2" style="width: 14px; height: 14px;"></i>
                                            Kurator: {{ $periode->kurator->name }}
                                        </div>
                                        <div class="d-flex align-items-center">
                                            <i data-lucide="package" class="mr-2" style="width: 14px; height: 14px;"></i>
                                            {{ $periode->periode_alternatif_count }} Produk Dikurasi
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer bg-white border-top-0 p-4 pt-0">
                                    <a href="{{ route('hasil.detail', $periode->id_periode_kurasi) }}" class="btn btn-primary btn-block btn-rounded d-flex align-items-center justify-content-center font-weight-bold">
                                        <span>Lihat Hasil & Ranking</span>
                                        <i data-lucide="bar-chart-3" class="ml-2" style="width: 16px; height: 16px;"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-12">
                            <div class="card border-0 shadow-sm rounded-lg overflow-hidden">
                                <div class="card-body text-center py-5 text-muted">
                                    <div class="d-flex flex-column align-items-center">
                                        <i data-lucide="bar-chart-3" class="mb-2" style="width: 48px; height: 48px; opacity: 0.3;"></i>
                                        <h5 class="font-weight-bold">Belum Ada Hasil Kurasi</h5>
                                        <p class="mb-0">Daftar ini akan terisi setelah periode kurasi diselesaikan oleh kurator.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforelse
                </div>
            </div>
        </main>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        AOS.init({ duration: 800, once: true });
    });
</script>
@endpush
