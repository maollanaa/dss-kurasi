<!-- Modal Edit Kriteria -->
<div class="modal fade" id="modalEdit-{{ $item->id_kriteria }}" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <form action="{{ route('admin.kriteria.update', $item->id_kriteria) }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i data-lucide="pencil"></i>
                        Edit Kriteria {{ $item->kode_kriteria }}
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group mb-3">
                        <label>Nama Kriteria</label>
                        <input type="text" name="nama_kriteria"
                            class="form-control"
                            value="{{ $item->nama_kriteria }}"
                            required placeholder="Contoh: Tekstur / Rasa">
                    </div>

                    <div class="form-group mb-3">
                        <label>Aspek Penilaian</label>
                        <select name="aspek" class="form-control" required>
                            <option value="kualitas_produk" {{ $item->aspek == 'kualitas_produk' ? 'selected' : '' }}>Kualitas Produk</option>
                            <option value="kemasan" {{ $item->aspek == 'kemasan' ? 'selected' : '' }}>Kemasan</option>
                        </select>
                    </div>

                    <div class="form-group mb-3">
                        <label>Deskripsi</label>
                        <textarea name="deskripsi_kriteria" class="form-control" rows="3"
                            placeholder="Jelaskan detail kriteria ini...">{{ $item->deskripsi_kriteria }}</textarea>
                    </div>

                    <div class="form-group mb-0">
                        <label>Target Nilai / Skala Ideal</label>
                        <select name="target_nilai" class="form-control" required>
                            @foreach($item->scales as $scale)
                                @if($scale->is_aktif || $item->target_nilai == $scale->nilai_skala)
                                    <option value="{{ $scale->nilai_skala }}" {{ $item->target_nilai == $scale->nilai_skala ? 'selected' : '' }}>
                                        Skala {{ $scale->nilai_skala }} - {{ $scale->deskripsi_skala }}
                                        {{ !$scale->is_aktif ? '(Non-aktif)' : '' }}
                                    </option>
                                @endif
                            @endforeach
                        </select>
                        <small class="text-muted mt-1 d-block">
                            <i data-lucide="info"></i>
                            Target nilai adalah nilai "ideal" yang diharapkan untuk produk lolos kurasi.
                        </small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>