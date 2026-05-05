<div class="modal fade" id="addPeriodeModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content border-0 shadow-lg rounded-lg">
            <div class="modal-header border-bottom-0 pb-0">
                <h5 class="modal-title font-weight-bold text-dark">Tambah Periode Kurasi</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('admin.kurasi.store') }}" method="POST">
                @csrf
                <div class="modal-body py-4">
                    <div class="form-group">
                        <label class="font-weight-bold text-dark small">Nama Periode</label>
                        <input type="text" name="nama_periode" class="form-control rounded-lg bg-light border-0" placeholder="Contoh: Kurasi Tahap I 2026" required>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold text-dark small">Tanggal Kurasi</label>
                        <input type="date" name="tanggal_kurasi" class="form-control rounded-lg bg-light border-0" value="{{ date('Y-m-d') }}" required>
                        <small class="text-muted mt-1 d-block">Bulan dan Tahun akan diisi otomatis berdasarkan tanggal ini.</small>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold text-dark small">Penanggung Jawab</label>
                        <input type="text" name="penanggung_jawab" class="form-control rounded-lg bg-light border-0" placeholder="Nama instansi/panitia" required>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold text-dark small">Kurator (Opsional)</label>
                        <select name="id_kurator" class="form-control rounded-lg bg-light border-0">
                            <option value="">-- Pilih Kurator (Kosongkan jika belum ada) --</option>
                            @foreach($kurators as $kur)
                                <option value="{{ $kur->id }}">{{ $kur->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group mb-0">
                        <label class="font-weight-bold text-dark small">Catatan Umum</label>
                        <textarea name="catatan_umum" rows="2" class="form-control rounded-lg bg-light border-0" placeholder="Catatan tambahan..."></textarea>
                    </div>
                </div>
                <div class="modal-footer border-top-0 pt-0">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4 shadow-sm" {{ !$activeAHP ? 'disabled' : '' }}>Simpan Periode</button>
                </div>
            </form>
        </div>
    </div>
</div>
