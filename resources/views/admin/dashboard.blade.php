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

                    <div class="card card-welcome">
                        <div class="card-body">
                            <h5 class="card-title"> Selamat datang di Dashboard Admin</h5>
                            <p class="card-text ">Sebagai admin, Anda dapat mengelola data kriteria sistem AHP, data
                                master Produk UMKM, pengguna sistem, hingga pengaturan periode kurasi.</p>
                        </div>
                    </div>

                    <!-- Baris 1: Summary Cards dan Bobot Kriteria -->
                    <div class="row mb-4">
                        <!-- Kolom Summary Cards-->
                        <div class="col-12 col-md-8 col-custom-5-8">
                            <div class="summary-grid">
                                <!-- Card 1 -->
                                <a href="#" class="text-decoration-none">
                                    <div class="card card-stat h-100 shadow-sm border-0">
                                        <div class="card-body d-flex flex-column justify-content-center">
                                            <div class="stat-value">{{ $totalKriteria }}</div>
                                            <div class="stat-label">Total Kriteria</div>
                                        </div>
                                    </div>
                                </a>

                                <!-- Card 2 -->
                                <a href="#" class="text-decoration-none">
                                    <div class="card card-stat h-100 shadow-sm border-0">
                                        <div class="card-body d-flex flex-column justify-content-center">
                                            <div class="stat-value">{{ $totalPeriodeKurasi }}</div>
                                            <div class="stat-label">Periode Kurasi</div>
                                        </div>
                                    </div>
                                </a>

                                <!-- Card 3 -->
                                <a href="#" class="text-decoration-none">
                                    <div class="card card-stat h-100 shadow-sm border-0">
                                        <div class="card-body d-flex flex-column justify-content-center">
                                            <div class="stat-value">{{ $totalProduk }}</div>
                                            <div class="stat-label">Total Produk</div>
                                        </div>
                                    </div>
                                </a>

                                <!-- Card 4 -->
                                <a href="#" class="text-decoration-none">
                                    <div class="card card-stat h-100 shadow-sm border-0">
                                        <div class="card-body d-flex flex-column justify-content-center">
                                            <div class="stat-value text-success">0</div>
                                            <div class="stat-label">Produk Lolos</div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>

                        <!-- Kolom Bobot Kriteria -->
                        <div class="col-12 col-md-4 col-custom-3-8 mt-4 mt-md-0">
                            <div class="card h-100 shadow-sm border-0">
                                <div class="card-header bg-white font-weight-bold">
                                    <i data-lucide="pie-chart" class="text-info mr-2"></i> Bobot Kriteria
                                </div>
                                <div class="card-body p-3 d-flex flex-column align-items-center justify-content-center">
                                    @if(count($kriteriaBobots) > 0)
                                        <div style="position: relative; height: 250px; width: 100%;">
                                            <canvas id="kriteriaPieChart"></canvas>
                                        </div>
                                    @else
                                        <div class="text-muted text-center py-5"
                                            style="min-height: 250px; display:flex; flex-direction:column; justify-content:center;">
                                            <div class="display-4 text-secondary mb-3 font-weight-bold" style="line-height:1;">0
                                            </div>
                                            Belum ada data kriteria.
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Baris 2: Top 5 Produk & Chart Lolos Kurasi -->
                    <div class="row">
                        <!-- Top 5 Produk -->
                        <div class="col-md-6 mb-4">
                            <div class="card h-100 shadow-sm border-0">
                                <div
                                    class="card-header bg-white font-weight-bold d-flex justify-content-between align-items-center">
                                    <span><i data-lucide="trophy" class="text-warning mr-2"></i> Top 5 Produk Kurasi
                                        Terakhir</span>
                                    <span class="badge badge-secondary">Periode: -</span>
                                </div>
                                <div class="card-body d-flex align-items-center justify-content-center">
                                    <div class="text-muted text-center py-5">
                                        <i data-lucide="inbox" class="d-block mb-3 text-secondary"
                                            style="width: 48px; height: 48px; margin: 0 auto;"></i>
                                        Belum ada kurasi yang berjalan sebelumnya.
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Chart Tren 5 Periode -->
                        <div class="col-md-6 mb-4">
                            <div class="card h-100 shadow-sm border-0">
                                <div class="card-header bg-white font-weight-bold">
                                    <i data-lucide="bar-chart-3" class="text-primary mr-2"></i> Tren 5 Periode Terakhir
                                    Lolos
                                    Kurasi
                                </div>
                                <div class="card-body d-flex align-items-center justify-content-center">
                                    <div class="text-muted text-center py-5">
                                        <i data-lucide="trending-down" class="text-secondary d-block mb-3"
                                            style="width: 48px; height: 48px; margin: 0 auto;"></i>
                                        Belum ada kurasi yang berjalan sebelumnya.
                                    </div>
                                </div>
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
        document.addEventListener('DOMContentLoaded', function () {
            const kriteriaLabels = {!! json_encode($kriteriaBobots->pluck('nama_kriteria')) !!};
            let kriteriaBobot = {!! json_encode($kriteriaBobots->map(function ($k) {
        return $k->bobot_prioritas ? (float) $k->bobot_prioritas * 100 : 0; })) !!};

            const totalBobot = kriteriaBobot.reduce((a, b) => a + b, 0);

            let backgroundColors = [
                '#0d6efd', '#6610f2', '#6f42c1', '#d63384', '#dc3545',
                '#fd7e14', '#ffc107', '#198754', '#20c997', '#0dcaf0'
            ];

            // Jika semua bobot masih 0, kita buat chart abu-abu dengan porsi sama rata
            if (totalBobot === 0 && kriteriaLabels.length > 0) {
                kriteriaBobot = kriteriaBobot.map(() => 100 / kriteriaLabels.length);
                backgroundColors = kriteriaLabels.map(() => '#e9ecef');
            }

            const ctx = document.getElementById('kriteriaPieChart');
            if (ctx) {
                new Chart(ctx, {
                    type: 'doughnut',
                    data: {
                        labels: kriteriaLabels,
                        datasets: [{
                            data: kriteriaBobot,
                            backgroundColor: backgroundColors,
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'bottom',
                                padding: 25,
                                labels: {
                                    font: {
                                        size: 11
                                    }
                                }
                            },
                            tooltip: {
                                callbacks: {
                                    label: function (context) {
                                        let label = context.label || '';
                                        if (label) {
                                            label += ': ';
                                        }
                                        if (totalBobot === 0) {
                                            label += '0%';
                                        } else {
                                            label += Math.round(context.raw * 100) / 100 + '%';
                                        }
                                        return label;
                                    }
                                }
                            }
                        }
                    }
                });
            }
        });
    </script>
@endpush