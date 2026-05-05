@extends('base.app')

@section('title', 'Manajemen Kriteria & Parameter')

@section('content')
    <div class="container-fluid p-0">
        <div class="row no-gutters">
            <!-- Sidebar -->
            @include('layouts.sidebar')

            <!-- Main Content -->
            <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-0 dashboard-main">
                @include('layouts.navbar')

                <div class="px-4 py-3 dashboard-content" data-aos="fade-up">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div>
                            <h4 class="font-weight-bold text-primary mb-1">Manajemen Kriteria & Parameter</h4>
                            <p class="text-muted small mb-0">Konfigurasi kriteria penilaian dan aktifkan/nonaktifkan skala penilaian.</p>
                        </div>
                    </div>

                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show mb-4 border-0 shadow-sm" role="alert">
                            <i data-lucide="check-circle" class="mr-2"></i> {{ session('success') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif

                    <div class="row">
                        @foreach ($kriteria as $item)
                            <div class="col-12">
                                <div class="card border-0 shadow-sm overflow-hidden card-kriteria">
                                    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center border-bottom-0">
                                        <div class="d-flex align-items-center kriteria-info mr-3">
                                            <div class="rounded-circle bg-primary-light p-2 mr-3 text-primary d-flex align-items-center justify-content-center flex-shrink-0" style="width: 40px; height: 40px;">
                                                <span class="font-weight-bold">{{ $item->kode_kriteria }}</span>
                                            </div>
                                            <div class="kriteria-text">
                                                <h6 class="mb-0 font-weight-bold text-dark text-truncate-custom">{{ $item->nama_kriteria }}</h6>
                                                <small class="text-muted text-truncate-custom">{{ ucfirst(str_replace('_', ' ', $item->aspek)) }}</small>
                                            </div>
                                        </div>
                                        <div class="d-flex align-items-center">
                                            <span class="badge badge-info mr-3 p-2 px-3 badge-pill">Target: {{ $item->target_nilai }}</span>
                                            <button class="btn btn-sm btn-light rounded-circle shadow-sm collapsed" type="button" data-toggle="collapse" data-target="#collapse-{{ $item->id_kriteria }}" aria-expanded="false">
                                                <i data-lucide="chevron-down" class="icon-down"></i>
                                                <i data-lucide="chevron-up" class="icon-up"></i>
                                            </button>
                                            <button class="btn btn-sm btn-outline-primary ml-2 px-3 rounded-pill" data-toggle="modal" data-target="#modalEdit-{{ $item->id_kriteria }}">
                                                <i data-lucide="pencil" class="mr-sm-1"></i><span class="d-none d-sm-inline">Edit</span>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="collapse" id="collapse-{{ $item->id_kriteria }}">
                                        <div class="card-body pt-0">
                                            <p class="text-muted small mt-2 mb-3">{{ $item->deskripsi_kriteria }}</p>
                                            
                                            <div class="table-responsive">
                                                <table class="table table-hover table-borderless align-middle mb-0">
                                                    <thead class="text-muted small uppercase tracking-wider">
                                                        <tr>
                                                            <th style="width: 150px;">Nilai Skala</th>
                                                            <th>Deskripsi Parameter / Skala</th>
                                                            <th style="width: 100px;" class="text-center">Status</th>
                                                            <th style="width: 100px;" class="text-center">Aksi</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($item->scales as $scale)
                                                            <tr class="border-top">
                                                                <td>
                                                                    <div class="badge badge-pill badge-light p-2 px-3 border">
                                                                        Skala {{ $scale->nilai_skala }}
                                                                    </div>
                                                                </td>
                                                                <td>
                                                                    <span class="text-sm {{ !$scale->is_aktif ? 'text-muted text-strikethrough' : '' }}">{{ $scale->deskripsi_skala }}</span>
                                                                </td>
                                                                <td class="text-center">
                                                                    @if($scale->is_aktif)
                                                                        <span class="badge badge-pill badge-success px-2 py-1">Aktif</span>
                                                                    @else
                                                                        <span class="badge badge-pill badge-secondary px-2 py-1">Non-aktif</span>
                                                                    @endif
                                                                </td>
                                                                <td class="text-center">
                                                                    <button type="button"
                                                                            class="btn btn-sm btn-outline-secondary rounded-pill px-2"
                                                                            data-toggle="modal"
                                                                            data-target="#modalEditSkala-{{ $item->id_kriteria }}-{{ $scale->nilai_skala }}"
                                                                            title="Edit Skala">
                                                                        <i data-lucide="pencil" style="width: 13px; height: 13px;"></i>
                                                                        <span class="d-none d-md-inline ml-1" style="font-size: 0.78rem;">Edit</span>
                                                                    </button>
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        @endforeach
                    </div>
                </div>

                @foreach ($kriteria as $item)
                    @include('modal.kriteria.edit', ['item' => $item])
                    @foreach ($item->scales as $scale)
                        @include('modal.kriteria.skala', ['item' => $item, 'scale' => $scale])
                    @endforeach
                @endforeach
            </main>
        </div>
    </div>
@endsection



@push('scripts')
<script>
    $(document).ready(function() {
        AOS.init({
            duration: 800,
            once: true
        });

        // Re-initialize Lucide icons after modals are shown
        $(document).on('shown.bs.modal', function() {
            lucide.createIcons();
        });
    });
</script>
@endpush
