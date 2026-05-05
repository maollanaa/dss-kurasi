@extends('base.app')

@section('title', 'Kelola Produk')

@section('breadcrumb')
    <li class="breadcrumb-item">
        <a href="{{ route('admin.kurasi.index') }}" style="color: inherit; text-decoration: none;">Periode Kurasi</a>
    </li>
    <li class="breadcrumb-item active" aria-current="page">Kelola Produk</li>
@endsection

@section('content')
<div class="container-fluid p-0">
    <div class="row no-gutters">
        @include('layouts.sidebar')

        <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-0 dashboard-main">
            @include('layouts.navbar')

            <div class="px-4 py-3 dashboard-content" data-aos="fade-up">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h4 class="font-weight-bold text-primary mb-1">
                            Kelola Produk: {{ $periode->nama_periode }}
                        </h4>
                        <p class="text-muted small mb-0 mt-2">Pilih produk-produk yang akan dinilai pada periode kurasi: <strong class="text-dark">{{ $periode->nama_periode }}</strong>.</p>
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
                
                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show mb-4 border-0 shadow-sm" role="alert">
                        <i data-lucide="alert-circle" class="mr-2"></i> {{ session('error') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif

                <div class="card border-0 shadow-sm rounded-lg overflow-hidden">
                    <form action="{{ route('admin.kurasi.produk.store', $periode->id_periode_kurasi) }}" method="POST">
                        @csrf
                        <div class="card-header bg-white border-bottom py-3 d-flex justify-content-between align-items-center">
                            <h6 class="m-0 font-weight-bold text-dark"><i data-lucide="list-checks" class="mr-2 text-primary" style="width: 18px;"></i>Daftar Produk (Alternatif)</h6>
                            <button type="submit" class="btn btn-primary btn-sm rounded-pill font-weight-bold px-4 shadow-sm">
                                <i data-lucide="save" class="mr-2" style="width: 14px;"></i>Simpan Pilihan Produk
                            </button>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive" style="max-height: 600px; overflow-y: auto;">
                                <table class="table table-hover align-middle mb-0" id="tableProdukKurasi">
                                    <thead class="bg-light text-muted small uppercase tracking-wider sticky-top">
                                        <tr>
                                            <th class="border-0 px-4 py-3" style="width: 50px;">
                                                <div class="custom-control custom-checkbox">
                                                    <input type="checkbox" class="custom-control-input" id="checkAll">
                                                    <label class="custom-control-label" for="checkAll"></label>
                                                </div>
                                            </th>
                                            <th class="border-0 py-3">Produk & Brand</th>
                                            <th class="border-0 py-3">Pemilik</th>
                                            <th class="border-0 py-3">Keterangan</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($alternatifs as $alt)
                                            <tr>
                                                <td class="px-4 py-3">
                                                    <div class="custom-control custom-checkbox">
                                                        <input type="checkbox" class="custom-control-input checkbox-item" 
                                                            id="check_{{ $alt->id_alternatif }}" 
                                                            name="alternatif_ids[]" 
                                                            value="{{ $alt->id_alternatif }}"
                                                            {{ in_array($alt->id_alternatif, $selectedAlternatifIds) ? 'checked' : '' }}>
                                                        <label class="custom-control-label" for="check_{{ $alt->id_alternatif }}"></label>
                                                    </div>
                                                </td>
                                                <td class="py-3">
                                                    <div class="d-flex align-items-center">
                                                        <div class="product-img-wrapper mr-3 rounded shadow-sm overflow-hidden" style="width: 48px; height: 48px; background: #f8f9fa;">
                                                            @if($alt->foto_produk)
                                                                <img src="{{ asset('storage/' . $alt->foto_produk) }}" alt="{{ $alt->nama_produk }}" class="w-100 h-100 object-fit-cover">
                                                            @else
                                                                <div class="w-100 h-100 d-flex align-items-center justify-content-center text-muted">
                                                                    <i data-lucide="package" style="width: 20px;"></i>
                                                                </div>
                                                            @endif
                                                        </div>
                                                        <div>
                                                            <h6 class="mb-0 font-weight-bold text-dark">{{ $alt->nama_produk }}</h6>
                                                            <small class="text-primary font-weight-500">{{ $alt->nama_brand_umkm }}</small>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="py-3">{{ $alt->nama_pemilik ?? '-' }}</td>
                                                <td class="py-3">
                                                    @if(in_array($alt->id_alternatif, $selectedAlternatifIds))
                                                        <span class="badge badge-success px-2 py-1"><i data-lucide="check" style="width: 12px; height: 12px; margin-right: 2px;"></i> Terpilih</span>
                                                    @else
                                                        <span class="badge badge-light px-2 py-1 text-muted border">Belum Terpilih</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="4" class="text-center py-5 text-muted">Belum ada data produk di sistem.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </main>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const checkAll = document.getElementById('checkAll');
        const checkboxes = document.querySelectorAll('.checkbox-item');

        if(checkAll) {
            checkAll.addEventListener('change', function() {
                checkboxes.forEach(cb => {
                    // Update DOM property
                    cb.checked = this.checked;
                    // Trigger change event if DataTables modifies it
                    $(cb).trigger('change');
                });
            });
            
            // Check 'checkAll' if all checkboxes are initially checked
            const allChecked = Array.from(checkboxes).every(cb => cb.checked);
            if(checkboxes.length > 0 && allChecked) {
                checkAll.checked = true;
            }
            
            checkboxes.forEach(cb => {
                cb.addEventListener('change', function() {
                    if(!this.checked) {
                        checkAll.checked = false;
                    } else {
                        const allChecked = Array.from(checkboxes).every(c => c.checked);
                        if(allChecked) checkAll.checked = true;
                    }
                });
            });
        }
    });

    $(document).ready(function() {
        // Initialize DataTables with pagination disabled to ensure all form inputs are submitted
        $('#tableProdukKurasi').DataTable({
            "paging": false,
            "info": false,
            "language": {
                "search": "Cari produk:"
            },
            "drawCallback": function() {
                if (window.lucide) {
                    lucide.createIcons();
                }
            }
        });
    });
</script>
@endpush
