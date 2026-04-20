<!-- Modal Edit Kriteria -->
<div class="modal fade" id="modalEdit-{{ $item->id_kriteria }}" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content border-0 shadow-lg">
            <form action="{{ route('admin.kriteria.update', $item->id_kriteria) }}" method="POST">
                @csrf
                <div class="modal-header border-bottom-0 pb-0">
                    <h5 class="modal-title font-weight-bold text-primary">
                        <i data-lucide="pencil" class="mr-2" style="width: 20px; height: 20px;"></i>
                        Edit Kriteria {{ $item->kode_kriteria }}
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body py-4">
                    <div class="form-group mb-3">
                        <label class="font-weight-600 mb-1">Nama Kriteria</label>
                        <input type="text" name="nama_kriteria" class="form-control rounded-pill px-3 shadow-none border" 
                               value="{{ $item->nama_kriteria }}" required placeholder="Contoh: Tekstur / Rasa">
                    </div>

                    <div class="form-group mb-3">
                        <label class="font-weight-600 mb-1">Aspek Penilaian</label>
                        <select name="aspek" class="form-control rounded-pill px-3 shadow-none border" required>
                            <option value="kualitas_produk" {{ $item->aspek == 'kualitas_produk' ? 'selected' : '' }}>Kualitas Produk</option>
                            <option value="kemasan" {{ $item->aspek == 'kemasan' ? 'selected' : '' }}>Kemasan</option>
                        </select>
                    </div>

                    <div class="form-group mb-3">
                        <label class="font-weight-600 mb-1">Deskripsi</label>
                        <textarea name="deskripsi_kriteria" class="form-control rounded-lg shadow-none border" 
                                  rows="3" placeholder="Jelaskan detail kriteria ini...">{{ $item->deskripsi_kriteria }}</textarea>
                    </div>

                    <div class="form-group mb-0">
                        <label class="font-weight-600 mb-1">Target Nilai / Skala Ideal</label>
                        <select name="target_nilai" class="form-control rounded-pill px-3 shadow-none border" required>
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
                            <i data-lucide="info" class="mr-1" style="width: 12px; height: 12px;"></i>
                            Target nilai adalah nilai "ideal" yang diharapkan untuk produk lolos kurasi.
                        </small>
                    </div>
                </div>
                <div class="modal-footer border-top-0 pt-0">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4 shadow-sm border-0">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>
