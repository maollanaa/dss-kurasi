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
                        <input type="text" name="name" class="form-control" value="{{ $user->name }}"
                            required>
                    </div>
                    <div class="form-group">
                        <label class="small font-weight-bold">Email</label>
                        <input type="email" name="email" class="form-control" value="{{ $user->email }}"
                            required>
                    </div>
                    <div class="form-group">
                        <label class="small font-weight-bold">Password (Kosongkan jika tidak diubah)</label>
                        <div class="input-group">
                            <input type="password" name="password" class="form-control"
                                placeholder="Isi untuk mengganti password">
                            <div class="input-group-append">
                                <button class="btn btn-outline-light border-left-0 btn-toggle-password" type="button" style="border: 1px solid #ced4da; border-left: none; background: white;">
                                    <i data-lucide="eye" class="text-muted"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="small font-weight-bold">Role</label>
                        <select name="role" class="form-control" @if(auth()->id() == $user->id) disabled
                        @endif required>
                            <option value="kurator" {{ $user->role === 'kurator' ? 'selected' : '' }}>Kurator
                            </option>
                            <option value="admin" {{ $user->role === 'admin' ? 'selected' : '' }}>Admin
                            </option>
                        </select>
                        @if(auth()->id() == $user->id)
                            <input type="hidden" name="role" value="{{ $user->role }}">
                            <small class="text-muted italic">Role tidak dapat diubah untuk akun sendiri.</small>
                        @endif
                    </div>
                </div>
                <div class="modal-footer border-top-0 pb-4 px-4">
                    <button type="button" class="btn btn-light rounded-pill px-4"
                        data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4 shadow-sm">Simpan
                        Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>
