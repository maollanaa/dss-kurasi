@extends('base.app')

@section('title', 'Kriteria & Parameter')

@section('content')
    <div class="container-fluid p-0 kriteria-page">
        <div class="row no-gutters">
            @include('layouts.sidebar')

            <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-0 kriteria-main">
                @include('layouts.navbar')

                <div class="px-4 py-3 mt-3 kriteria-content">
                    <div class="card card-welcome kriteria-hero">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-lg-8">
                                    <span class="badge badge-light badge-pill px-3 py-2 mb-3">US-05 & US-06</span>
                                    <h4 class="card-title mb-3">Konfigurasi kriteria, jenis parameter, rubrik, dan target ideal</h4>
                                    <p class="card-text mb-0">
                                        Halaman ini mengikuti PRD untuk membantu admin mengelola struktur penilaian
                                        kurasi: nama kriteria, aspek, jenis parameter, rubrik skala 1-5, serta target
                                        ideal yang akan dipakai pada AHP dan Profile Matching.
                                    </p>
                                </div>
                                <div class="col-lg-4 mt-4 mt-lg-0">
                                    <div class="hero-panel">
                                        <div class="hero-panel-label">Poin PRD</div>
                                        <div class="hero-panel-item">
                                            <i class="bi bi-check-circle-fill text-success mr-2"></i>
                                            Field kriteria lengkap: nama, deskripsi, aspek, jenis parameter, target
                                        </div>
                                        <div class="hero-panel-item">
                                            <i class="bi bi-check-circle-fill text-success mr-2"></i>
                                            Empat tipe parameter: range, ya/tidak, pemenuhan keadaan, subjektif
                                        </div>
                                        <div class="hero-panel-item">
                                            <i class="bi bi-check-circle-fill text-success mr-2"></i>
                                            Status aktif/nonaktif untuk mengontrol kriteria yang ikut sesi baru
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-6 col-xl-3">
                            <div class="card card-stat kpi-card">
                                <div class="card-body">
                                    <div class="stat-value">{{ $pageSummary['aktif'] }}</div>
                                    <div class="stat-label">Kriteria Aktif</div>
                                    <small class="text-muted d-block mt-2">Menjadi kandidat yang dipakai pada AHP aktif.</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6 col-xl-3">
                            <div class="card card-stat kpi-card">
                                <div class="card-body">
                                    <div class="stat-value">{{ $pageSummary['nonaktif'] }}</div>
                                    <div class="stat-label">Kriteria Nonaktif</div>
                                    <small class="text-muted d-block mt-2">Disimpan untuk histori konfigurasi dan peninjauan ulang.</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6 col-xl-3">
                            <div class="card card-stat kpi-card">
                                <div class="card-body">
                                    <div class="stat-value">{{ $pageSummary['aspek'] }}</div>
                                    <div class="stat-label">Kelompok Aspek</div>
                                    <small class="text-muted d-block mt-2">Saat ini mencakup kualitas produk dan kemasan.</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6 col-xl-3">
                            <div class="card card-stat kpi-card">
                                <div class="card-body">
                                    <div class="stat-value">{{ number_format($pageSummary['target_rata'], 1) }}</div>
                                    <div class="stat-label">Rata-rata Target</div>
                                    <small class="text-muted d-block mt-2">{{ $pageSummary['rubrik_lengkap'] }} kriteria sudah memiliki rubrik skala.</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xl-8">
                            <div class="card">
                                <div class="card-header bg-white border-0 d-flex flex-column flex-lg-row align-items-lg-center justify-content-between">
                                    <div>
                                        <h5 class="mb-1">Daftar Kriteria Penilaian</h5>
                                        <p class="text-muted mb-0">Data kriteria disusun dari database dan diringkas sesuai struktur field pada PRD.</p>
                                    </div>
                                    <div class="mt-3 mt-lg-0">
                                        <a href="#rubrik-kriteria" class="btn btn-primary">
                                            <i class="bi bi-journal-text mr-1"></i> Lihat Rubrik Detail
                                        </a>
                                    </div>
                                </div>
                                <div class="card-body pt-0">
                                    <div class="table-responsive">
                                        <table class="table table-hover align-middle mb-0" id="kriteriaTable">
                                            <thead>
                                                <tr>
                                                    <th>Kode</th>
                                                    <th>Kriteria</th>
                                                    <th>Aspek</th>
                                                    <th>Jenis Parameter</th>
                                                    <th class="text-center">Target</th>
                                                    <th class="text-center">Status</th>
                                                    <th class="text-right">Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse ($kriteriaItems as $item)
                                                    <tr>
                                                        <td>
                                                            <span class="badge badge-pill badge-light border px-3 py-2">{{ $item->kode_kriteria }}</span>
                                                        </td>
                                                        <td>
                                                            <div class="font-weight-semibold text-dark">{{ $item->nama_kriteria }}</div>
                                                            <small class="text-muted d-block">{{ $item->deskripsi_kriteria ?: 'Belum ada deskripsi kriteria.' }}</small>
                                                        </td>
                                                        <td>
                                                            <span class="aspek-chip aspek-{{ \Illuminate\Support\Str::slug($item->aspek) }}">
                                                                {{ $item->aspek }}
                                                            </span>
                                                        </td>
                                                        <td>
                                                            <div class="parameter-type">{{ $item->jenis_parameter }}</div>
                                                            <small class="text-muted d-block">{{ $item->contoh_parameter }}</small>
                                                        </td>
                                                        <td class="text-center">
                                                            <span class="target-pill">{{ $item->target_nilai }}/5</span>
                                                        </td>
                                                        <td class="text-center">
                                                            @if ($item->is_aktif)
                                                                <span class="badge badge-success badge-pill px-3 py-2">Aktif</span>
                                                            @else
                                                                <span class="badge badge-secondary badge-pill px-3 py-2">Nonaktif</span>
                                                            @endif
                                                        </td>
                                                        <td class="text-right text-nowrap">
                                                            <a href="#rubrik-{{ $item->id_kriteria }}" class="btn btn-sm btn-outline-primary mr-1">Rubrik</a>
                                                            <span class="btn btn-sm btn-outline-dark disabled" aria-disabled="true">Edit</span>
                                                        </td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="7" class="text-center text-muted py-4">
                                                            Belum ada data kriteria yang tersedia.
                                                        </td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-4">
                            <div class="card">
                                <div class="card-header bg-white border-0">
                                    <h5 class="mb-1">Komposisi Jenis Parameter</h5>
                                    <p class="text-muted mb-0">Empat tipe input pada PRD diringkas untuk membantu konfigurasi cepat.</p>
                                </div>
                                <div class="card-body pt-2">
                                    <div class="parameter-summary">
                                        @foreach ($parameterGuides as $guide)
                                            <div class="parameter-summary-item">
                                                <div class="parameter-summary-icon">
                                                    <i class="bi {{ $guide['icon'] }}"></i>
                                                </div>
                                                <div>
                                                    <div class="font-weight-semibold">{{ $guide['nama'] }}</div>
                                                    <small class="text-muted d-block">{{ $guide['contoh'] }}</small>
                                                    <small class="text-primary d-block mt-1">{{ $guide['criteria_count'] }} kriteria</small>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>

                            <div class="card">
                                <div class="card-header bg-white border-0">
                                    <h5 class="mb-1">Aturan Target Nilai</h5>
                                    <p class="text-muted mb-0">Nilai target menjadi profil ideal dalam proses Profile Matching.</p>
                                </div>
                                <div class="card-body pt-2">
                                    <div class="target-rules">
                                        <div class="target-rule-item">
                                            <span class="rule-index">1</span>
                                            <div>Semua target memakai skala <strong>1 sampai 5</strong>.</div>
                                        </div>
                                        <div class="target-rule-item">
                                            <span class="rule-index">2</span>
                                            <div>Target yang lebih tinggi menunjukkan standar ideal yang lebih ketat.</div>
                                        </div>
                                        <div class="target-rule-item">
                                            <span class="rule-index">3</span>
                                            <div>Hanya kriteria aktif yang semestinya diikutkan ke sesi AHP dan ranking baru.</div>
                                        </div>
                                        <div class="target-rule-item">
                                            <span class="rule-index">4</span>
                                            <div>Rubrik konversi harus konsisten agar gap terhadap target tetap valid.</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header bg-white border-0 d-flex flex-column flex-lg-row justify-content-between align-items-lg-center">
                            <div>
                                <h5 class="mb-1">Panduan Jenis Parameter</h5>
                                <p class="text-muted mb-0">Panduan singkat ini mengikuti empat jenis parameter yang tertulis pada PRD.</p>
                            </div>
                            <span class="badge badge-info badge-pill px-3 py-2 mt-3 mt-lg-0">{{ count($parameterGuides) }} tipe parameter</span>
                        </div>
                        <div class="card-body pt-2">
                            <div class="row">
                                @foreach ($parameterGuides as $guide)
                                    <div class="col-lg-6">
                                        <div class="rubric-card">
                                            <div class="d-flex align-items-start">
                                                <div class="rubric-icon mr-3">
                                                    <i class="bi {{ $guide['icon'] }}"></i>
                                                </div>
                                                <div>
                                                    <h6 class="mb-1">{{ $guide['nama'] }}</h6>
                                                    <p class="text-muted mb-2">{{ $guide['deskripsi'] }}</p>
                                                    <small class="d-block text-uppercase text-muted font-weight-bold mb-2">Contoh Penggunaan</small>
                                                    <p class="mb-3">{{ $guide['contoh'] }}</p>
                                                    <small class="d-block text-uppercase text-muted font-weight-bold mb-2">Panduan Rubrik</small>
                                                    <ul class="rubric-list mb-0">
                                                        @foreach ($guide['rubrik'] as $rubrik)
                                                            <li>{{ $rubrik }}</li>
                                                        @endforeach
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <div class="card" id="rubrik-kriteria">
                        <div class="card-header bg-white border-0">
                            <h5 class="mb-1">Rubrik Detail per Kriteria</h5>
                            <p class="text-muted mb-0">Skala 1-5 berikut dipakai sebagai dasar normalisasi nilai aktual kurator sebelum perhitungan gap.</p>
                        </div>
                        <div class="card-body pt-2">
                            <div class="row">
                                @forelse ($kriteriaItems as $item)
                                    <div class="col-lg-6">
                                        <div class="rubric-card" id="rubrik-{{ $item->id_kriteria }}">
                                            <div class="d-flex justify-content-between align-items-start mb-3">
                                                <div>
                                                    <span class="badge badge-light border mb-2">{{ $item->kode_kriteria }}</span>
                                                    <h6 class="mb-1">{{ $item->nama_kriteria }}</h6>
                                                    <p class="text-muted mb-2">{{ $item->deskripsi_kriteria ?: 'Belum ada deskripsi kriteria.' }}</p>
                                                </div>
                                                <span class="target-pill">{{ $item->target_nilai }}/5</span>
                                            </div>

                                            <div class="d-flex flex-wrap mb-3">
                                                <span class="aspek-chip aspek-{{ \Illuminate\Support\Str::slug($item->aspek) }} mr-2 mb-2">{{ $item->aspek }}</span>
                                                <span class="badge badge-pill badge-light border px-3 py-2 mr-2 mb-2">{{ $item->jenis_parameter }}</span>
                                                @if ($item->is_aktif)
                                                    <span class="badge badge-success badge-pill px-3 py-2 mb-2">Aktif</span>
                                                @else
                                                    <span class="badge badge-secondary badge-pill px-3 py-2 mb-2">Nonaktif</span>
                                                @endif
                                            </div>

                                            @if ($item->skala->isNotEmpty())
                                                <div class="table-responsive">
                                                    <table class="table table-sm mb-0">
                                                        <thead>
                                                            <tr>
                                                                <th style="width: 90px;">Skala</th>
                                                                <th>Deskripsi Rubrik</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach ($item->skala as $skala)
                                                                <tr>
                                                                    <td><strong>{{ $skala->nilai_skala }}</strong></td>
                                                                    <td>{{ $skala->deskripsi_skala }}</td>
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            @else
                                                <div class="alert alert-light border mb-0">
                                                    Rubrik skala belum tersedia untuk kriteria ini.
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                @empty
                                    <div class="col-12">
                                        <div class="alert alert-light border mb-0">
                                            Belum ada rubrik yang dapat ditampilkan karena data kriteria masih kosong.
                                        </div>
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-7">
                            <div class="card border-0 shadow-sm">
                                <div class="card-header bg-white border-0">
                                    <h5 class="mb-1">Alur Konfigurasi yang Disarankan</h5>
                                    <p class="text-muted mb-0">Urutan ini disusun mengikuti alur kerja admin yang dijelaskan di PRD.</p>
                                </div>
                                <div class="card-body pt-2">
                                    <div class="workflow">
                                        <div class="workflow-step">
                                            <span class="workflow-number">01</span>
                                            <div>
                                                <h6>Tentukan aspek kriteria</h6>
                                                <p class="mb-0 text-muted">Kelompokkan ke aspek kualitas produk atau kemasan agar struktur evaluasi konsisten.</p>
                                            </div>
                                        </div>
                                        <div class="workflow-step">
                                            <span class="workflow-number">02</span>
                                            <div>
                                                <h6>Pilih jenis parameter</h6>
                                                <p class="mb-0 text-muted">Cocokkan sifat penilaian dengan tipe range, ya/tidak, pemenuhan keadaan, atau subjektif berskala.</p>
                                            </div>
                                        </div>
                                        <div class="workflow-step">
                                            <span class="workflow-number">03</span>
                                            <div>
                                                <h6>Lengkapi rubrik konversi</h6>
                                                <p class="mb-0 text-muted">Pastikan setiap jawaban kurator dapat diterjemahkan konsisten ke skor akhir 1-5.</p>
                                            </div>
                                        </div>
                                        <div class="workflow-step">
                                            <span class="workflow-number">04</span>
                                            <div>
                                                <h6>Tetapkan target ideal</h6>
                                                <p class="mb-0 text-muted">Nilai target inilah yang nanti dibandingkan dengan nilai aktual saat menghitung gap.</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-5">
                            <div class="card border-0 shadow-sm">
                                <div class="card-header bg-white border-0">
                                    <h5 class="mb-1">Kaitan Dengan Modul Lanjutan</h5>
                                    <p class="text-muted mb-0">Catatan ini merangkum hubungan halaman dengan proses AHP dan hasil kurasi.</p>
                                </div>
                                <div class="card-body pt-2">
                                    <div class="note-list">
                                        <div class="note-item">
                                            <i class="bi bi-diagram-3 text-primary"></i>
                                            <span>Kriteria aktif akan menjadi dimensi pada matriks perbandingan berpasangan AHP.</span>
                                        </div>
                                        <div class="note-item">
                                            <i class="bi bi-bullseye text-danger"></i>
                                            <span>Target nilai berfungsi sebagai profil ideal pada perhitungan Profile Matching.</span>
                                        </div>
                                        <div class="note-item">
                                            <i class="bi bi-journal-check text-success"></i>
                                            <span>Rubrik yang sudah dibuat perlu ditampilkan kembali saat kurator mengisi nilai aktual produk.</span>
                                        </div>
                                        <div class="note-item">
                                            <i class="bi bi-clock-history text-warning"></i>
                                            <span>Status nonaktif membantu menjaga histori konfigurasi tanpa ikut memengaruhi sesi baru.</span>
                                        </div>
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
            if (window.jQuery && $.fn.DataTable && document.getElementById('kriteriaTable')) {
                $('#kriteriaTable').DataTable({
                    pageLength: 10,
                    lengthChange: false,
                    info: false,
                    order: [[0, 'asc']],
                    language: {
                        search: 'Cari:',
                        paginate: {
                            previous: 'Sebelumnya',
                            next: 'Berikutnya'
                        },
                        zeroRecords: 'Belum ada kriteria yang cocok.'
                    }
                });
            }
        });
    </script>
@endpush
