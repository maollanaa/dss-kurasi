<div class="modal fade" id="modalEditSkala-{{ $item->id_kriteria }}-{{ $scale->nilai_skala }}" tabindex="-1"
    role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
        <div class="modal-content">
            <form action="{{ route('admin.kriteria.update-skala') }}" method="POST">
                @csrf
                <input type="hidden" name="id_kriteria" value="{{ $item->id_kriteria }}">
                <input type="hidden" name="nilai_skala" value="{{ $scale->nilai_skala }}">

                <div class="modal-header modal-header--gradient">
                    <div>
                        <h6 class="modal-title">
                            Edit Skala {{ $scale->nilai_skala }}
                        </h6>
                        <small class="text-muted">{{ $item->kode_kriteria }} — {{ $item->nama_kriteria }}</small>
                    </div>
                    <button type="button" class="close ml-auto" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <div class="d-flex align-items-center mb-3">
                        <span class="badge-skala mr-2">Skala {{ $scale->nilai_skala }}</span>
                        <!-- <span class="text-muted small">Nilai tidak dapat diubah</span> -->
                    </div>

                    <div class="form-group mb-3">
                        <label>Deskripsi Parameter / Skala</label>
                        <textarea name="deskripsi_skala" class="form-control" rows="3"
                            placeholder="Jelaskan detail skala ini..."
                            required>{{ old('deskripsi_skala', $scale->deskripsi_skala) }}</textarea>
                    </div>

                    <div class="form-group mb-0">
                        <label>Status</label>
                        <select name="is_aktif" class="form-control" required>
                            <option value="1" {{ $scale->is_aktif ? 'selected' : '' }}>Aktif</option>
                            <option value="0" {{ !$scale->is_aktif ? 'selected' : '' }}>Non-aktif</option>
                        </select>
                        <small class="text-muted d-block mt-1">
                            <i data-lucide="info"></i>
                            Skala non-aktif tidak akan muncul sebagai pilihan penilaian.
                        </small>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">
                        <i data-lucide="save"></i>Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>