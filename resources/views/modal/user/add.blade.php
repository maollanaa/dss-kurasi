<div class="modal fade" id="modalAddUser" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-white border-bottom-0 pt-4 px-4">
                <h5 class="modal-title font-weight-bold text-primary">Tambah Pengguna Baru</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('admin.user.store') }}" method="POST">
                @csrf
                <div class="modal-body px-4">
                    <div class="form-group">
                        <label class="small font-weight-bold">Nama Lengkap</label>
                        <input type="text" name="name" class="form-control" required
                            placeholder="Contoh: Rakha Maulana">
                    </div>
                    <div class="form-group">
                        <label class="small font-weight-bold">Email</label>
                        <input type="email" name="email" class="form-control" required
                            placeholder="email@example.com">
                    </div>
                    <div class="form-group">
                        <label class="small font-weight-bold">Password</label>
                        <div class="input-group">
                            <input type="password" name="password" class="form-control" required
                                placeholder="Minimal 6 karakter">
                            <div class="input-group-append">
                                <button class="btn btn-outline-light border-left-0 btn-toggle-password" type="button" style="border: 1px solid #ced4da; border-left: none; background: white;">
                                    <i data-lucide="eye" class="text-muted"></i>
                                </button>
                            </div>
                        </div>
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
                    <button type="button" class="btn btn-light rounded-pill px-4"
                        data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4 shadow-sm">Simpan
                        Data</button>
                </div>
            </form>
        </div>
    </div>
</div>
