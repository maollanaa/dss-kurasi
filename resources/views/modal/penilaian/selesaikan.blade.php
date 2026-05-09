{{-- Modal Konfirmasi Selesaikan Kurasi --}}
<div class="modal fade" id="modalSelesaikanKurasi" tabindex="-1" role="dialog" aria-labelledby="modalSelesaikanKurasiLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content border-0 shadow" style="border-radius: 16px; overflow: hidden;">
            <div class="modal-body text-center p-5">
                <div class="mb-4">
                    <div class="d-inline-flex align-items-center justify-content-center rounded-circle bg-primary" style="width: 64px; height: 64px;">
                        <i data-lucide="clipboard-check" class="text-white" style="width: 32px; height: 32px;"></i>
                    </div>
                </div>
                <h5 class="font-weight-bold mb-2">Selesaikan Kurasi?</h5>
                <p class="text-muted mb-4">
                    Status periode <strong class="text-dark">{{ $periode->nama_periode }}</strong> akan diubah menjadi <span class="badge badge-success px-2 py-1">Selesai</span>. Tindakan ini tidak dapat dibatalkan.
                </p>
                <div class="d-flex justify-content-center">
                    <button type="button" class="btn btn-outline-secondary btn-rounded font-weight-bold px-4 py-2 mr-2" data-dismiss="modal">
                        Batal
                    </button>
                    <form action="{{ route('kurator.penilaian.selesaikan', $periode->id_periode_kurasi) }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-primary btn-rounded font-weight-bold px-4 py-2">
                            <i data-lucide="check" class="mr-1" style="width: 16px; height: 16px;"></i> Ya, Selesaikan
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
