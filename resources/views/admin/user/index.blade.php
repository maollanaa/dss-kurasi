@extends('base.app')

@section('title', 'Manajemen Pengguna')

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
                            <h4 class="font-weight-bold text-primary mb-1">Manajemen Pengguna</h4>
                            <p class="text-muted small mb-0">Kelola akun administrator dan kurator sistem.</p>
                        </div>
                        <button class="btn btn-primary rounded-pill px-4 shadow-sm" data-toggle="modal" data-target="#modalAddUser">
                            <i data-lucide="plus-circle" class="mr-2"></i>Tambah User
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
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif

                    <div class="card border-0 shadow-sm rounded-lg overflow-hidden">
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0" id="tableUser">
                                    <thead class="bg-light text-muted small uppercase tracking-wider">
                                        <tr>
                                            <th class="pl-4 py-3" style="width: 50px;">No</th>
                                            <th class="py-3">Nama & Email</th>
                                            <th class="py-3">Role</th>
                                            <th class="py-3">Terakhir Aktif</th>
                                            <th class="py-3 pr-4 text-right">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($users as $index => $user)
                                            <tr>
                                                <td class="pl-4 text-muted small">{{ $index + 1 }}</td>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <div class="avatar-circle mr-3" style="width: 40px; height: 40px; background: #e3f2fd; color: #0d6efd; display: flex; align-items: center; justify-content: center; border-radius: 50%; font-weight: bold;">
                                                            {{ strtoupper(substr($user->name, 0, 1)) }}
                                                        </div>
                                                        <div>
                                                            <h6 class="mb-0 font-weight-bold text-dark">{{ $user->name }}</h6>
                                                            <small class="text-muted">{{ $user->email }}</small>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <span class="badge badge-pill {{ $user->role === 'admin' ? 'badge-primary' : 'badge-info' }} px-3 py-2">
                                                        {{ ucfirst($user->role) }}
                                                    </span>
                                                </td>
                                                <td>
                                                    @if($user->last_activity)
                                                        <span class="text-dark small">
                                                            <i data-lucide="clock" class="text-muted mr-1" style="width: 14px; height: 14px;"></i>
                                                            {{ \Carbon\Carbon::createFromTimestamp($user->last_activity)->diffForHumans() }}
                                                        </span>
                                                    @else
                                                        <span class="text-muted small italic">Tidak ada aktivitas</span>
                                                    @endif
                                                </td>
                                                <td class="pr-4 text-right">
                                                    <div class="btn-group shadow-sm rounded-pill overflow-hidden">
                                                        <button class="btn btn-sm btn-white border-right" data-toggle="modal" data-target="#modalEditUser-{{ $user->id }}" title="Edit User">
                                                            <i data-lucide="edit-3" class="text-primary mr-1" style="width: 14px;"></i> Edit
                                                        </button>
                                                        <button class="btn btn-sm btn-white text-danger" @if(auth()->id() == $user->id) disabled @else data-toggle="modal" data-target="#modalDeleteUser-{{ $user->id }}" @endif title="Hapus User">
                                                            <i data-lucide="trash-2" class="mr-1" style="width: 14px;"></i> Hapus
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
                
                <!-- Modal Tambah User -->
                <div class="modal fade" id="modalAddUser" tabindex="-1" role="dialog" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content border-0 shadow-lg">
                            <div class="modal-header bg-white border-bottom-0 pt-4 px-4">
                                <h5 class="modal-title font-weight-bold text-primary">Tambah User Baru</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <form action="{{ route('admin.user.store') }}" method="POST">
                                @csrf
                                <div class="modal-body px-4">
                                    <div class="form-group">
                                        <label class="small font-weight-bold">Nama Lengkap</label>
                                        <input type="text" name="name" class="form-control" required placeholder="Contoh: Rakha Maulana">
                                    </div>
                                    <div class="form-group">
                                        <label class="small font-weight-bold">Email</label>
                                        <input type="email" name="email" class="form-control" required placeholder="email@example.com">
                                    </div>
                                    <div class="form-group">
                                        <label class="small font-weight-bold">Password</label>
                                        <input type="password" name="password" class="form-control" required placeholder="Minimal 6 karakter">
                                    </div>
                                    <div class="form-group">
                                        <label class="small font-weight-bold">Role</label>
                                        <select name="role" class="form-control" required>
                                            <option value="kurator">Kurator</option>
                                            <option value="admin">Admin</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="modal-footer border-top-0 pb-4 px-4">
                                    <button type="button" class="btn btn-light rounded-pill px-4" data-dismiss="modal">Batal</button>
                                    <button type="submit" class="btn btn-primary rounded-pill px-4 shadow-sm">Simpan Data</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                @foreach($users as $user)
                    <!-- Modal Edit User -->
                    <div class="modal fade" id="modalEditUser-{{ $user->id }}" tabindex="-1" role="dialog" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered" role="document">
                            <div class="modal-content border-0 shadow-lg">
                                <div class="modal-header bg-white border-bottom-0 pt-4 px-4">
                                    <h5 class="modal-title font-weight-bold text-primary">Edit Data User</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <form action="{{ route('admin.user.update', $user->id) }}" method="POST">
                                    @csrf
                                    <div class="modal-body px-4">
                                        <div class="form-group">
                                            <label class="small font-weight-bold">Nama Lengkap</label>
                                            <input type="text" name="name" class="form-control" value="{{ $user->name }}" required>
                                        </div>
                                        <div class="form-group">
                                            <label class="small font-weight-bold">Email</label>
                                            <input type="email" name="email" class="form-control" value="{{ $user->email }}" required>
                                        </div>
                                        <div class="form-group">
                                            <label class="small font-weight-bold">Password (Kosongkan jika tidak diubah)</label>
                                            <input type="password" name="password" class="form-control" placeholder="Isi untuk mengganti password">
                                        </div>
                                        <div class="form-group">
                                            <label class="small font-weight-bold">Role</label>
                                            <select name="role" class="form-control" @if(auth()->id() == $user->id) disabled @endif required>
                                                <option value="kurator" {{ $user->role === 'kurator' ? 'selected' : '' }}>Kurator</option>
                                                <option value="admin" {{ $user->role === 'admin' ? 'selected' : '' }}>Admin</option>
                                            </select>
                                            @if(auth()->id() == $user->id)
                                                <input type="hidden" name="role" value="{{ $user->role }}">
                                                <small class="text-muted italic">Role tidak dapat diubah untuk akun sendiri.</small>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="modal-footer border-top-0 pb-4 px-4">
                                        <button type="button" class="btn btn-light rounded-pill px-4" data-dismiss="modal">Batal</button>
                                        <button type="submit" class="btn btn-primary rounded-pill px-4 shadow-sm">Simpan Perubahan</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Modal Delete User -->
                    <div class="modal fade" id="modalDeleteUser-{{ $user->id }}" tabindex="-1" role="dialog" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered" role="document">
                            <div class="modal-content border-0 shadow-lg overflow-hidden">
                                <div class="modal-header bg-white border-bottom-0 pt-4 px-4 justify-content-center">
                                    <h5 class="modal-title font-weight-bold text-danger">Konfirmasi Hapus</h5>
                                </div>
                                <div class="modal-body text-center px-5 pb-4">
                                    <div class="rounded-circle bg-danger-light d-flex align-items-center justify-content-center mx-auto mb-4" style="width: 80px; height: 80px;">
                                        <i data-lucide="alert-triangle" class="text-danger" style="width: 40px; height: 40px;"></i>
                                    </div>
                                    <h4 class="font-weight-bold text-dark mb-2">Hapus User?</h4>
                                    <p class="text-muted mb-0">Anda akan menghapus user <strong>{{ $user->name }}</strong>.</p>
                                    <p class="text-muted small">Tindakan ini tidak dapat dibatalkan.</p>
                                </div>
                                <div class="modal-footer border-top-0 justify-content-center pb-4 px-4">
                                    <button type="button" class="btn btn-light rounded-pill px-4 mr-2" data-dismiss="modal">Batal</button>
                                    <form action="{{ route('admin.user.delete', $user->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-danger rounded-pill px-4 shadow-sm">Ya, Hapus Akun</button>
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

@push('scripts')
<script>
    $(document).ready(function() {
        $('#tableUser').DataTable({
            "language": {
                "search": "Cari user:",
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

        $(document).on('shown.bs.modal', function() {
            lucide.createIcons();
        });
    });
</script>
@endpush
