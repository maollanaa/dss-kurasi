@extends('base.app')

@section('title', 'Manajemen Produk UMKM')

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
                            <h4 class="font-weight-bold text-primary mb-1">Manajemen Produk UMKM</h4>
                            <p class="text-muted small mb-0">Kelola data produk, brand, dan filter legalitas sebelum masuk ke tahap kurasi.</p>
                        </div>
                        <button class="btn btn-primary rounded-pill px-4 shadow-sm" data-toggle="modal" data-target="#modalAddProduk">
                            <i data-lucide="plus-circle" class="mr-2"></i>Tambah Produk
                        </button>
                    </div>

                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show mb-4 border-0 shadow-sm" role="alert">
                            <i data-lucide="check-circle" class="mr-2"></i> {{ session('success') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show mb-4 border-0 shadow-sm" role="alert">
                            <i data-lucide="alert-circle" class="mr-2"></i> {{ session('error') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif

                    <div class="card border-0 shadow-sm rounded-lg overflow-hidden">
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0" id="tableProduk">
                                    <thead class="bg-light text-muted small uppercase tracking-wider">
                                        <tr>
                                            <th class="pl-4 py-3" style="width: 50px;">No</th>
                                            <th class="py-3">Produk & Brand</th>
                                            <th class="py-3">Pemilik</th>
                                            <th class="py-3 text-center">Dokumen</th>
                                            <th class="py-3 text-center">Data Legalitas</th>
                                            <th class="py-3 text-center">Filter Kurasi</th>
                                            <th class="py-3 pr-4 text-right">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($produk as $index => $item)
                                            <tr>
                                                <td class="pl-4 text-muted small">{{ $index + 1 }}</td>
                                                <td class="cursor-pointer" data-toggle="modal" data-target="#modalDetailProduk-{{ $item->id_alternatif }}">
                                                    <div class="d-flex align-items-center">
                                                        <div class="product-img-wrapper mr-3 rounded shadow-sm overflow-hidden" style="width: 48px; height: 48px; background: #f8f9fa;">
                                                            @if($item->foto_produk)
                                                                <img src="{{ asset('storage/' . $item->foto_produk) }}" alt="{{ $item->nama_produk }}" class="w-100 h-100 object-fit-cover">
                                                            @else
                                                                <div class="w-100 h-100 d-flex align-items-center justify-content-center text-muted">
                                                                    <i data-lucide="package" style="width: 20px;"></i>
                                                                </div>
                                                            @endif
                                                        </div>
                                                        <div>
                                                            <h6 class="mb-0 font-weight-bold text-dark">{{ $item->nama_produk }}</h6>
                                                            <small class="text-primary font-weight-500">{{ $item->nama_brand_umkm }}</small>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <span class="text-sm font-weight-500 text-dark">{{ $item->nama_pemilik }}</span>
                                                </td>
                                                <td class="text-center">
                                                    <div class="d-flex justify-content-center flex-wrap gap-1" style="gap: 4px;">
                                                        @if($item->legalitas)
                                                            @if($item->legalitas->is_nib)
                                                                <span class="badge badge-outline-secondary small px-2 py-1" style="font-size: 0.65rem; border: 1px solid #dee2e6;">NIB</span>
                                                            @endif
                                                            @if($item->legalitas->is_sertifikat_halal)
                                                                <span class="badge badge-outline-secondary small px-2 py-1" style="font-size: 0.65rem; border: 1px solid #dee2e6;">HALAL</span>
                                                            @endif
                                                            @if($item->legalitas->is_bpom)
                                                                <span class="badge badge-outline-secondary small px-2 py-1" style="font-size: 0.65rem; border: 1px solid #dee2e6;">BPOM</span>
                                                            @endif
                                                            @if($item->legalitas->is_sp_pirt)
                                                                <span class="badge badge-outline-secondary small px-2 py-1" style="font-size: 0.65rem; border: 1px solid #dee2e6;">PIRT</span>
                                                            @endif
                                                            @if(!$item->legalitas->is_nib && !$item->legalitas->is_sertifikat_halal && !$item->legalitas->is_bpom && !$item->legalitas->is_sp_pirt)
                                                                <span class="text-muted small">-</span>
                                                            @endif
                                                        @else
                                                            <span class="text-muted small">-</span>
                                                        @endif
                                                    </div>
                                                </td>
                                                <td class="text-center">
                                                    @if($item->is_aktif)
                                                        <span class="badge badge-pill badge-success px-3 py-2">Sudah Diisi</span>
                                                    @else
                                                        <span class="badge badge-pill badge-warning px-3 py-2 text-white">Belum Diisi</span>
                                                    @endif
                                                </td>
                                                <td class="text-center">
                                                    @if($item->legalitas && $item->legalitas->lolos_filter)
                                                        <span class="badge badge-pill badge-success px-3 py-2">
                                                            <i data-lucide="check-check" class="mr-1" style="width: 12px; height: 12px;"></i> Lolos
                                                        </span>
                                                    @else
                                                        <span class="badge badge-pill badge-danger px-3 py-2">
                                                            <i data-lucide="x-circle" class="mr-1" style="width: 12px; height: 12px;"></i> Tidak Lolos
                                                        </span>
                                                    @endif
                                                </td>
                                                <td class="pr-4 text-right">
                                                    <div class="btn-group shadow-sm rounded-pill overflow-hidden">
                                                        <button class="btn btn-sm btn-white border-right" data-toggle="modal" data-target="#modalLegalitas-{{ $item->id_alternatif }}" title="Manage Legalitas">
                                                            <i data-lucide="shield-check" class="text-info mr-1"></i> Legalitas
                                                        </button>
                                                        <button class="btn btn-sm btn-white" data-toggle="modal" data-target="#modalDetailProduk-{{ $item->id_alternatif }}" title="Detail Produk">
                                                            <i data-lucide="eye" class="text-primary"></i>
                                                        </button>
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

                <!-- Modals Stack -->
                @include('modal.add_produk')
                @foreach($produk as $item)
                    @include('modal.detail_produk', ['item' => $item])
                    @include('modal.edit_produk', ['item' => $item])
                    @include('modal.edit_legalitas', ['item' => $item])
                    <!-- Delete Confirmation Modal -->
                    <div class="modal fade" id="modalDeleteProduk-{{ $item->id_alternatif }}" tabindex="-1" role="dialog" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered" role="document">
                            <div class="modal-content border-0 shadow-lg overflow-hidden">
                                <div class="modal-header bg-white border-bottom-0 pt-4 px-4 justify-content-center">
                                    <h5 class="modal-title font-weight-bold text-danger">Konfirmasi Hapus</h5>
                                </div>
                                <div class="modal-body text-center px-5 pb-4">
                                    <div class="rounded-circle bg-danger-light d-flex align-items-center justify-content-center mx-auto mb-4" style="width: 80px; height: 80px;">
                                        <i data-lucide="alert-triangle" class="text-danger" style="width: 40px; height: 40px;"></i>
                                    </div>
                                    <h4 class="font-weight-bold text-dark mb-2">Hapus Produk?</h4>
                                    <p class="text-muted mb-0">Anda akan menghapus produk <strong>{{ $item->nama_produk }}</strong>.</p>
                                    <p class="text-muted small">Tindakan ini akan menghapus permanen seluruh data terkait.</p>
                                </div>
                                <div class="modal-footer border-top-0 justify-content-center pb-4 px-4">
                                    <button type="button" class="btn btn-light rounded-pill px-4 mr-2" data-dismiss="modal">Batal</button>
                                    <form action="{{ route('admin.produk.delete', $item->id_alternatif) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-danger rounded-pill px-4 shadow-sm">Ya, Hapus Permanen</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach

            </main>
        </div>
    </div>
@endsection

@push('styles')
<style>
    .bg-danger-light { background-color: rgba(220, 53, 69, 0.1); }
    .nav-link-inner { display: flex; align-items: center; }
    .product-img-wrapper img { object-fit: cover; }
    .btn-white { background-color: #fff; color: #6c757d; }
    .btn-white:hover { background-color: #f8f9fa; color: #495057; }
    .font-weight-500 { font-weight: 500; }
    .tracking-wider { letter-spacing: 0.05em; }

    /* Custom Table Styling */
    #tableProduk_wrapper .dataTables_length, 
    #tableProduk_wrapper .dataTables_filter {
        padding: 1.25rem 1.5rem;
    }
    #tableProduk_wrapper .dataTables_info, 
    #tableProduk_wrapper .dataTables_paginate {
        padding: 1rem 1.5rem;
    }
    .page-item.active .page-link {
        background-color: #0d6efd;
        border-color: #0d6efd;
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

        $('#tableProduk').DataTable({
            "language": {
                "search": "Cari produk:",
                "lengthMenu": "Tampilkan _MENU_ data",
                "info": "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                "paginate": {
                    "previous": "<i data-lucide='chevron-left'></i>",
                    "next": "<i data-lucide='chevron-right'></i>"
                }
            },
            "drawCallback": function() {
                lucide.createIcons();
            }
        });

        // Re-initialize Lucide icons after modals are shown
        $(document).on('shown.bs.modal', function() {
            lucide.createIcons();
        });

        // Auto-reopen legalitas modal if there's a validation error
        @if(session('error_id'))
            $('#modalLegalitas-{{ session('error_id') }}').modal('show');
        @endif
    });
</script>
@endpush
