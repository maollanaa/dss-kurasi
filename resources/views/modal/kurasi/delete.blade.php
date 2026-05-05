<div class="modal fade" id="deletePeriodeModal{{ $p->id_periode_kurasi }}" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content border-0 shadow-lg rounded-lg">
            <div class="modal-header border-bottom-0 pb-0">
                <h5 class="modal-title font-weight-bold text-danger">Konfirmasi Hapus</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body py-4">
                <p class="mb-0">Apakah Anda yakin ingin menghapus periode kurasi <strong>{{ $p->nama_periode }}</strong>? Tindakan ini tidak dapat dibatalkan.</p>
            </div>
            <div class="modal-footer border-top-0 pt-0">
                <button type="button" class="btn btn-light rounded-pill px-4" data-dismiss="modal">Batal</button>
                <form action="{{ route('admin.kurasi.delete', $p->id_periode_kurasi) }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-danger rounded-pill px-4 shadow-sm">Ya, Hapus</button>
                </form>
            </div>
        </div>
    </div>
</div>
