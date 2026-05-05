<div class="modal fade" id="editPeriodeModal{{ $p->id_periode_kurasi }}" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content border-0 shadow-lg rounded-lg">
            <div class="modal-header border-bottom-0 pb-0">
                <h5 class="modal-title font-weight-bold text-dark">Edit Periode Kurasi</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('admin.kurasi.update', $p->id_periode_kurasi) }}" method="POST">
                @csrf
                <div class="modal-body py-4">
                    <div class="form-group">
                        <label class="font-weight-bold text-dark small">Nama Periode</label>
                        <input type="text" name="nama_periode" class="form-control rounded-lg bg-light border-0" value="{{ $p->nama_periode }}" required>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold text-dark small">Tanggal Kurasi</label>
                        <input type="date" name="tanggal_kurasi" class="form-control rounded-lg bg-light border-0" value="{{ $p->tanggal_kurasi->format('Y-m-d') }}" required>
                        <small class="text-muted mt-1 d-block">Bulan dan Tahun akan diisi otomatis berdasarkan tanggal ini.</small>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold text-dark small">Penanggung Jawab</label>
                        <input type="text" name="penanggung_jawab" class="form-control rounded-lg bg-light border-0" value="{{ $p->penanggung_jawab }}" required>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold text-dark small">Kurator (Opsional)</label>
                        <select name="id_kurator" class="form-control rounded-lg bg-light border-0">
                            <option value="">-- Pilih Kurator (Kosongkan jika belum ada) --</option>
                            @foreach($kurators as $kur)
                                <option value="{{ $kur->id }}" {{ $p->id_kurator == $kur->id ? 'selected' : '' }}>{{ $kur->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold text-dark small">Status Kurasi</label>
                        <select name="status_kurasi" class="form-control rounded-lg bg-light border-0" required>
                            <option value="belum" {{ $p->status_kurasi == 'belum' ? 'selected' : '' }}>Belum Mulai</option>
                            <option value="berlangsung" {{ $p->status_kurasi == 'berlangsung' ? 'selected' : '' }}>Berlangsung</option>
                            <option value="selesai" {{ $p->status_kurasi == 'selesai' ? 'selected' : '' }}>Selesai</option>
                        </select>
                    </div>
                    <div class="form-group mb-0">
                        <label class="font-weight-bold text-dark small">Catatan Umum</label>
                        <textarea name="catatan_umum" rows="2" class="form-control rounded-lg bg-light border-0">{{ $p->catatan_umum }}</textarea>
                    </div>
                </div>
                <div class="modal-footer border-top-0 pt-0">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4 shadow-sm">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>
