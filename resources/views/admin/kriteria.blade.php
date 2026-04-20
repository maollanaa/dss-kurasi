@extends('base.app')

@section('title', 'Manajemen Kriteria')

@section('content')
    <div class="container-fluid p-0">
        <div class="row no-gutters">
            <!-- Sidebar -->
            @include('layouts.sidebar')

            <!-- Main Content -->
            <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-0 dashboard-main">
                @include('layouts.navbar')

                <div class="px-4 py-3 mt-3 dashboard-content" data-aos="fade-up">
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
                                        <div class="d-flex align-items-center">
                                            <div class="rounded-circle bg-primary-light p-2 mr-3 text-primary d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                                <span class="font-weight-bold">{{ $item->kode_kriteria }}</span>
                                            </div>
                                            <div>
                                                <h6 class="mb-0 font-weight-bold text-dark">{{ $item->nama_kriteria }}</h6>
                                                <small class="text-muted">{{ ucfirst(str_replace('_', ' ', $item->aspek)) }}</small>
                                            </div>
                                        </div>
                                        <div class="d-flex align-items-center">
                                            <span class="badge badge-info mr-3 p-2 px-3 badge-pill">Target: {{ $item->target_nilai }}</span>
                                            <button class="btn btn-sm btn-light rounded-circle shadow-sm collapsed" type="button" data-toggle="collapse" data-target="#collapse-{{ $item->id_kriteria }}" aria-expanded="false">
                                                <i data-lucide="chevron-down" class="icon-down"></i>
                                                <i data-lucide="chevron-up" class="icon-up"></i>
                                            </button>
                                            <button class="btn btn-sm btn-outline-primary ml-2 px-3 rounded-pill" data-toggle="modal" data-target="#modalEdit-{{ $item->id_kriteria }}">
                                                <i data-lucide="pencil" class="mr-1"></i> Edit
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
                                                            <th style="width: 120px;" class="text-center">Status</th>
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
                                                                    <div class="custom-control custom-switch">
                                                                        <input type="checkbox" class="custom-control-input toggle-skala" 
                                                                               id="switch-{{ $item->id_kriteria }}-{{ $scale->nilai_skala }}"
                                                                               data-id-kriteria="{{ $item->id_kriteria }}"
                                                                               data-nilai-skala="{{ $scale->nilai_skala }}"
                                                                               {{ $scale->is_aktif ? 'checked' : '' }}>
                                                                        <label class="custom-control-label cursor-pointer" for="switch-{{ $item->id_kriteria }}-{{ $scale->nilai_skala }}"></label>
                                                                    </div>
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
                    @include('modal.edit_kriteria', ['item' => $item])
                @endforeach
            </main>
        </div>
    </div>
@endsection

@push('styles')
<style>
    .bg-primary-light { background-color: rgba(13, 110, 253, 0.1); }
    .card-kriteria { transition: all 0.3s ease; border-radius: 12px; }
    .card-kriteria:hover { transform: translateY(-3px); }
    .uppercase { text-transform: uppercase; }
    .tracking-wider { letter-spacing: 0.05em; }
    .text-strikethrough { text-decoration: line-through; opacity: 0.6; }
    .font-weight-600 { font-weight: 600; color: #444; }
    .cursor-pointer { cursor: pointer; }
    
    .custom-switch .custom-control-input:checked ~ .custom-control-label::before {
        background-color: #0d6efd;
        border-color: #0d6efd;
    }

    /* Chevron toggle visibility */
    .btn[data-toggle="collapse"].collapsed .icon-up { display: none; }
    .btn[data-toggle="collapse"]:not(.collapsed) .icon-down { display: none; }
    
    .btn[data-toggle="collapse"] i {
        transition: all 0.2s ease;
    }
</style>
@endpush

@push('scripts')
<script>
    $(document).ready(function() {
        AOS.init({
            duration: 800,
            once: true
        });

        $('.toggle-skala').on('change', function() {
            let checkbox = $(this);
            let idKriteria = checkbox.data('id-kriteria');
            let nilaiSkala = checkbox.data('nilai-skala');
            let row = checkbox.closest('tr');
            let label = row.find('span.text-sm');

            $.ajax({
                url: "{{ route('admin.kriteria.toggle-skala') }}",
                method: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    id_kriteria: idKriteria,
                    nilai_skala: nilaiSkala
                },
                success: function(response) {
                    if (response.success) {
                        if (response.is_aktif) {
                            label.removeClass('text-muted text-strikethrough');
                        } else {
                            label.addClass('text-muted text-strikethrough');
                        }
                    }
                },
                error: function() {
                    alert('Gagal mengubah status skala.');
                    checkbox.prop('checked', !checkbox.prop('checked'));
                }
            });
        });
    });
</script>
@endpush
