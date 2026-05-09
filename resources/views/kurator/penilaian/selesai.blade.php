@extends('base.app')

@section('title', 'Kurasi Selesai - ' . $periode->nama_periode)

@section('breadcrumb')
    <li class="breadcrumb-item">
        <a href="{{ route('kurator.penilaian.index') }}" style="color: inherit; text-decoration: none;">Tugas Kurasi</a>
    </li>
    <li class="breadcrumb-item active" aria-current="page">{{ $periode->nama_periode }}</li>
@endsection

@section('content')
    <div class="container-fluid p-0">
        <div class="row no-gutters">
            @include('layouts.sidebar')

            <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-0 dashboard-main">
                @include('layouts.navbar')

                <div class="kurasi-selesai-page px-4 py-3" data-aos="fade-up">
                    <div class="card selesai-card text-center">
                        <div class="card-body p-5">
                            {{-- Icon --}}
                            <div class="mb-4">
                                <div class="selesai-icon-wrapper d-inline-flex">
                                    <i data-lucide="check" class="text-white" style="width: 48px; height: 48px;"></i>
                                </div>
                            </div>

                            {{-- Heading --}}
                            <h2 class="font-weight-bold mb-2">Kurasi Telah Selesai!</h2>
                            <p class="text-muted small mb-4 mx-auto-420">
                                Proses penilaian kurasi untuk periode
                                <strong class="text-dark">{{ $periode->nama_periode }}</strong>
                                telah berhasil diselesaikan.
                            </p>

                            {{-- Statistik --}}
                            <div class="d-flex justify-content-center mb-4">
                                <div class="px-4 py-3 mx-2 bg-light rounded-lg stat-card-min">
                                    <div class="h4 font-weight-bold text-primary mb-0">{{ $totalProduk }}</div>
                                    <small class="text-muted">Produk Dinilai</small>
                                </div>
                            </div>

                            {{-- Actions --}}
                            <div class="mx-auto-260">
                                <a href="{{ route('kurator.penilaian.detail', $periode->id_periode_kurasi) }}" class="btn btn-primary btn-block font-weight-bold py-2 mb-2 shadow-sm rounded-pill">
                                    <i data-lucide="eye" class="mr-2" style="width: 16px; height: 16px;"></i> Lihat Hasil Kurasi
                                </a>
                                <a href="{{ route('kurator.penilaian.index') }}" class="btn btn-outline-secondary btn-block font-weight-bold py-2 rounded-pill">
                                    <i data-lucide="arrow-left" class="mr-2" style="width: 16px; height: 16px;"></i> Kembali ke Daftar Periode
                                </a>
                            </div>
                        </div>
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