@extends('base.app')

@section('title', 'Tugas Kurasi')

@section('content')
    <div class="container-fluid p-0">
        <div class="row no-gutters">
            @include('layouts.sidebar')

            <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-0 dashboard-main">
                @include('layouts.navbar')

                <div class="px-4 py-3 dashboard-content" data-aos="fade-up">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div>
                            <h4 class="font-weight-bold text-primary mb-1">Tugas Kurasi</h4>
                            <p class="text-muted small mb-0">Daftar periode kurasi yang ditugaskan kepada Anda.</p>
                        </div>
                    </div>

                    <div class="row">
                        @forelse($periodes as $periode)
                            <div class="col-md-6 col-lg-4 mb-4">
                                <div class="card shadow-sm h-100 border-0 rounded-lg overflow-hidden">
                                    <div class="card-body p-4">
                                        <div class="d-flex justify-content-between align-items-start mb-3">
                                            <h5 class="card-title font-weight-bold text-dark mb-0">
                                                {{ $periode->nama_periode }}
                                            </h5>
                                            <span class="badge badge-pill {{ $periode->status_kurasi == 'berlangsung' ? 'badge-warning text-white' : ($periode->status_kurasi == 'selesai' ? 'badge-success' : 'badge-secondary') }} px-3 py-2">
                                                {{ ucfirst($periode->status_kurasi) }}
                                            </span>
                                        </div>
                                        
                                        <div class="mb-3 text-muted small">
                                            <div class="d-flex align-items-center mb-1">
                                                <i data-lucide="calendar" class="mr-2" style="width: 14px; height: 14px;"></i>
                                                {{ \Carbon\Carbon::parse($periode->tanggal_kurasi)->translatedFormat('d F Y') }}
                                            </div>
                                            <div class="d-flex align-items-center">
                                                <i data-lucide="user" class="mr-2" style="width: 14px; height: 14px;"></i>
                                                PJ: {{ $periode->penanggung_jawab }}
                                            </div>
                                        </div>

                                        <div class="mb-4">
                                            <div class="d-flex justify-content-between align-items-center mb-1">
                                                <span class="small font-weight-bold text-muted">Progress Penilaian</span>
                                                <span class="small font-weight-bold text-primary">{{ $periode->produk_dinilai }} / {{ $periode->total_produk_lolos }} Produk</span>
                                            </div>
                                            <div class="progress" style="height: 8px; border-radius: 4px;">
                                                <div class="progress-bar bg-primary" role="progressbar" style="width: {{ $periode->progress_percentage }}%;" aria-valuenow="{{ $periode->progress_percentage }}" aria-valuemin="0" aria-valuemax="100"></div>
                                            </div>
                                            <div class="text-right mt-1">
                                                <span class="small text-muted">{{ $periode->progress_percentage }}% Selesai</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-footer bg-white border-top-0 p-4 pt-0">
                                        <a href="{{ route('kurator.penilaian.detail', $periode->id_periode_kurasi) }}" class="btn btn-primary btn-block btn-rounded d-flex align-items-center justify-content-center font-weight-bold">
                                            <span>Lihat Daftar Produk</span>
                                            <i data-lucide="arrow-right" class="ml-2" style="width: 16px; height: 16px;"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-12">
                                <div class="card border-0 shadow-sm rounded-lg overflow-hidden">
                                    <div class="card-body text-center py-5 text-muted">
                                        <div class="d-flex flex-column align-items-center">
                                            <i data-lucide="calendar" class="mb-2" style="width: 32px; height: 32px; opacity: 0.5;"></i>
                                            <p class="mb-0">Belum ada periode kurasi yang ditugaskan kepada Anda.</p>
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
