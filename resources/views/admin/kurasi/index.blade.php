@extends('base.app')

@section('title', 'Periode Kurasi')

@section('content')
<div class="container-fluid p-0">
    <div class="row no-gutters">
        @include('layouts.sidebar')

        <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-0 dashboard-main">
            @include('layouts.navbar')

            <div class="px-4 py-3 dashboard-content" data-aos="fade-up">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h4 class="font-weight-bold text-primary mb-1">Periode Kurasi</h4>
                        <p class="text-muted small mb-0">Manajemen jadwal dan status kurasi produk UMKM.</p>
                    </div>
                    <button class="btn btn-primary shadow-sm rounded-pill font-weight-bold px-4" data-toggle="modal" data-target="#addPeriodeModal">
                        <i data-lucide="plus" class="mr-2" style="width: 18px; height: 18px;"></i>Tambah Periode
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

                @if($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show mb-4 border-0 shadow-sm" role="alert">
                        <ul class="mb-0 pl-3">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif

                @if(!$activeAHP)
                    <div class="alert alert-warning mb-4 border-0 shadow-sm">
                        <i data-lucide="alert-triangle" class="mr-2"></i> <strong>Perhatian:</strong> Saat ini tidak ada sesi AHP yang aktif. Anda harus memiliki setidaknya satu sesi AHP aktif untuk membuat Periode Kurasi baru.
                    </div>
                @endif

                <div class="card border-0 shadow-sm rounded-lg overflow-hidden">
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0" id="tableKurasi">
                                <thead class="bg-light text-muted small uppercase tracking-wider">
                                    <tr>
                                        <th class="pl-4 py-3" style="width: 50px;">No</th>
                                        <th class="py-3">Nama Periode</th>
                                        <th class="py-3">Waktu Pelaksanaan</th>
                                        <th class="py-3 text-center">Jumlah Produk</th>
                                        <th class="py-3">Kurator</th>
                                        <th class="py-3 text-center">Status</th>
                                        <th class="py-3 pr-4 text-right">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($periode as $index => $p)
                                        <tr>
                                            <td class="pl-4 text-muted small">{{ $index + 1 }}</td>
                                            <td class="py-3">
                                                <div class="font-weight-bold text-dark">{{ $p->nama_periode }}</div>
                                                <div class="small text-muted">{{ $p->penanggung_jawab }}</div>
                                            </td>
                                            <td class="py-3">
                                                <div>{{ \Carbon\Carbon::parse($p->tanggal_kurasi)->translatedFormat('d F Y') }}</div>
                                                <div class="small text-muted">Bulan: {{ $p->bulan }}, Tahun: {{ $p->tahun }}</div>
                                            </td>
                                            <td class="py-3">
                                                <span>{{ $p->periode_alternatif_count }} Produk</span>
                                            </td>
                                            <td class="py-3">
                                                @if($p->kurator)
                                                    <div class="d-flex align-items-center">
                                                        <div class="avatar-circle avatar-circle--sm bg-primary-light text-primary mr-2">
                                                            {{ strtoupper(substr($p->kurator->name, 0, 2)) }}
                                                        </div>
                                                        <span>{{ $p->kurator->name }}</span>
                                                    </div>
                                                @else
                                                    <span class="text-muted italic">Belum ditentukan</span>
                                                @endif
                                            </td>
                                            <td class="py-3 text-center">
                                                @if($p->status_kurasi == 'belum')
                                                    <span class="badge badge-pill badge-secondary px-3 py-2">Belum Mulai</span>
                                                @elseif($p->status_kurasi == 'berlangsung')
                                                    <span class="badge badge-pill badge-warning px-3 py-2 text-white">Berlangsung</span>
                                                @else
                                                    <span class="badge badge-pill badge-success px-3 py-2">Selesai</span>
                                                @endif
                                            </td>
                                            <td class="py-3 pr-4 text-right">
                                                <div class="btn-group shadow-sm rounded-pill overflow-hidden">
                                                    <a href="{{ route('admin.kurasi.produk', $p->id_periode_kurasi) }}" class="btn btn-sm btn-white border-right" title="{{ $p->status_kurasi == 'belum' ? 'Kelola Produk' : 'Lihat Produk' }}">
                                                        <i data-lucide="{{ $p->status_kurasi == 'belum' ? 'package' : 'eye' }}" class="text-info mr-1"></i> {{ $p->status_kurasi == 'belum' ? 'Kelola' : 'Lihat' }}
                                                    </a>
                                                    @if($p->status_kurasi == 'belum')
                                                    <button class="btn btn-sm btn-white border-right" data-toggle="modal" data-target="#editPeriodeModal{{ $p->id_periode_kurasi }}" title="Edit">
                                                        <i data-lucide="edit-2" class="text-primary"></i>
                                                    </button>
                                                    <button class="btn btn-sm btn-white" data-toggle="modal" data-target="#deletePeriodeModal{{ $p->id_periode_kurasi }}" title="Hapus">
                                                        <i data-lucide="trash-2" class="text-danger"></i>
                                                    </button>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center py-5 text-muted">
                                                <div class="d-flex flex-column align-items-center">
                                                    <i data-lucide="calendar" class="mb-2" style="width: 32px; height: 32px; opacity: 0.5;"></i>
                                                    <p class="mb-0">Belum ada data periode kurasi.</p>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>

<!-- Modals -->
@include('modal.kurasi.add')
@foreach($periode as $p)
    @include('modal.kurasi.edit')
    @if($p->status_kurasi == 'belum')
        @include('modal.kurasi.delete')
    @endif
@endforeach

@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        AOS.init({
            duration: 800,
            once: true
        });

        $('#tableKurasi').DataTable({
            "language": {
                "search": "Cari periode:",
                "lengthMenu": "Tampilkan _MENU_ data",
                "info": "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                "emptyTable": "Belum ada data periode kurasi.",
                "paginate": {
                    "previous": "<i data-lucide='chevron-left'></i>",
                    "next": "<i data-lucide='chevron-right'></i>"
                }
            },
            "drawCallback": function() {
                if (window.lucide) {
                    lucide.createIcons({
                        icons: lucide.icons
                    });
                }
            }
        });

        $(document).on('shown.bs.modal', function() {
            lucide.createIcons();
        });
    });
</script>
@endpush
