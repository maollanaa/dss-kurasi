<!-- Modal Delete User -->
<div class="modal fade" id="modalDeleteUser-{{ $user->id }}" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content border-0 shadow-lg overflow-hidden">
            <div class="modal-header bg-white border-bottom-0 pt-4 px-4 justify-content-center">
                <h5 class="modal-title font-weight-bold text-danger">Konfirmasi Hapus</h5>
            </div>
            <div class="modal-body text-center px-5 pb-4">
                <div class="rounded-circle bg-danger-light d-flex align-items-center justify-content-center mx-auto mb-4"
                    style="width: 80px; height: 80px;">
                    <i data-lucide="alert-triangle" class="text-danger"
                        style="width: 40px; height: 40px;"></i>
                </div>
                <h4 class="font-weight-bold text-dark mb-2">Hapus User?</h4>
                <p class="text-muted mb-0">Anda akan menghapus user <strong>{{ $user->name }}</strong>.</p>
                <p class="text-muted small">Tindakan ini tidak dapat dibatalkan.</p>
            </div>
            <div class="modal-footer border-top-0 justify-content-center pb-4 px-4">
                <button type="button" class="btn btn-light rounded-pill px-4 mr-2"
                    data-dismiss="modal">Batal</button>
                <form action="{{ route('admin.user.delete', $user->id) }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-danger rounded-pill px-4 shadow-sm">Ya, Hapus
                        Akun</button>
                </form>
            </div>
        </div>
    </div>
</div>
