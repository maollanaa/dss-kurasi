@extends('base.app')

@section('title', 'Dashboard')

@section('content')
    <div class="container-fluid p-0">
        <div class="row no-gutters">
            <!-- Sidebar -->
            @include('layouts.sidebar')

            <!-- Main Content -->
            <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-0 dashboard-main">
                @include('layouts.navbar')

                <div class="px-4 py-3 dashboard-content">

                    {{-- Hero Work Focus --}}
                    @if($recentActiveTask)
                        <div class="card card-welcome mb-4 shadow-sm border-0">
                            <div class="card-body p-4">
                                <div class="row align-items-center">
                                    <div class="col-md-8">
                                        <h5 class="card-title mb-2">
                                            <i data-lucide="activity" class="mr-2" style="width: 20px;"></i> Tugas Aktif: {{ $recentActiveTask->nama_periode }}
                                        </h5>
                                        <p class="card-text mb-3">
                                            Progres Penilaian: <strong>{{ $progress['assessed'] }} dari {{ $progress['total'] }} Produk</strong> ({{ $progress['percentage'] }}%)
                                        </p>
                                        <div class="progress mb-4" style="height: 8px; background: rgba(255,255,255,0.2);">
                                            <div class="progress-bar bg-white" role="progressbar" style="width: {{ $progress['percentage'] }}%" aria-valuenow="{{ $progress['percentage'] }}" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                        <a href="{{ route('kurator.penilaian.detail', $recentActiveTask->id_periode_kurasi) }}" class="btn btn-light text-primary font-weight-bold px-4 shadow-sm">
                                            Lanjutkan Penilaian <i data-lucide="arrow-right" class="ml-1" style="width: 16px;"></i>
                                        </a>
                                    </div>
                                    <div class="col-md-4 d-none d-md-block text-right">
                                        <i data-lucide="clipboard-check" class="text-white opacity-25" style="width: 100px; height: 100px;"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="card card-welcome mb-4 shadow-sm border-0">
                            <div class="card-body p-4 text-center">
                                <h5 class="card-title justify-content-center mb-2">Semua Tugas Selesai! ✨</h5>
                                <p class="card-text mb-3">Tidak ada periode kurasi aktif yang ditugaskan kepada Anda saat ini.</p>
                                <a href="{{ route('kurator.penilaian.index') }}" class="btn btn-light text-primary font-weight-bold px-4 shadow-sm">Lihat Riwayat</a>
                            </div>
                        </div>
                    @endif

                    {{-- Stats Grid --}}
                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="summary-grid">
                                <!-- Card 1 -->
                                <div class="card card-stat h-100 shadow-sm border-0">
                                    <div class="card-body d-flex flex-column justify-content-between">
                                        <div class="d-flex justify-content-between align-items-start">
                                            <div class="stat-label">Tugas Aktif</div>
                                            <div class="stat-icon"><i data-lucide="layout-grid"></i></div>
                                        </div>
                                        <div class="stat-value text-primary">{{ $activeTasksCount }}</div>
                                    </div>
                                </div>
                                <!-- Card 2 -->
                                <div class="card card-stat h-100 shadow-sm border-0">
                                    <div class="card-body d-flex flex-column justify-content-between">
                                        <div class="d-flex justify-content-between align-items-start">
                                            <div class="stat-label">Produk Dikurasi</div>
                                            <div class="stat-icon"><i data-lucide="box"></i></div>
                                        </div>
                                        <div class="stat-value text-warning">{{ $totalProductsCount }}</div>
                                    </div>
                                </div>
                                <!-- Card 3 -->
                                <div class="card card-stat h-100 shadow-sm border-0">
                                    <div class="card-body d-flex flex-column justify-content-between">
                                        <div class="d-flex justify-content-between align-items-start">
                                            <div class="stat-label">Kurasi Selesai</div>
                                            <div class="stat-icon"><i data-lucide="check-circle"></i></div>
                                        </div>
                                        <div class="stat-value text-success">{{ $completedTasksCount }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Info Cards --}}
                    <div class="row mt-2">
                        <div class="col-12">
                            <div class="card border-0 bg-light p-3" style="border-radius: 15px; border-left: 5px solid #0d6efd !important;">
                                <div class="d-flex align-items-center">
                                    <i data-lucide="info" class="text-primary mr-3"></i>
                                    <span class="text-muted small">Sistem menggunakan metode <strong>AHP (Analytic Hierarchy Process)</strong> dan <strong>Profile Matching</strong> untuk perankingan otomatis berdasarkan input nilai Anda.</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
@endsection