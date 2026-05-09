<div class="modal fade" id="modalLegalitas-{{ $item->id_alternatif }}" tabindex="-1" role="dialog" aria-labelledby="modalLegalitasLabel-{{ $item->id_alternatif }}" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content border-0 shadow-lg overflow-hidden">
            <div class="modal-header modal-header--gradient pt-4 px-4">
                <h5 class="modal-title font-weight-bold">
                    <i data-lucide="shield-check" class="mr-2"></i>Filter Legalitas Produk
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('admin.produk.legalitas', $item->id_alternatif) }}" method="POST" id="formLegalitas-{{ $item->id_alternatif }}">
                @csrf
                <div class="modal-body px-4">
                    <div class="alert alert-info border-0 shadow-sm rounded-lg small mb-4">
                        <i data-lucide="info" class="mr-2" style="width: 14px;"></i>
                        <strong>Syarat Lolos Kurasi:</strong> Wajib memiliki NIB, Sertifikat Halal, dan salah satu dari BPOM atau SP-PIRT.
                    </div>

                    @php
                        $leg = $item->legalitas;
                        
                        // Strip prefixes for editing
                        $no_nib = $leg ? preg_replace('/[^0-9]/', '', $leg->no_nib) : '';
                        $no_halal = $leg ? preg_replace('/[^0-9]/', '', $leg->no_sertifikat_halal) : '';
                        
                        // Split SP-PIRT (XXXXXXXXXXXXX-XX)
                        $no_pirt_1 = '';
                        $no_pirt_2 = '';
                        if ($leg && $leg->no_sp_pirt) {
                            $pirt_parts = explode('-', $leg->no_sp_pirt);
                            $no_pirt_1 = preg_replace('/[^0-9]/', '', $pirt_parts[0] ?? '');
                            $no_pirt_2 = preg_replace('/[^0-9]/', '', $pirt_parts[1] ?? '');
                        }
                        
                        // Handle BPOM
                        $no_bpom = $leg ? $leg->no_bpom : '';
                    @endphp

                    {{-- NIB --}}
                    <div class="legalitas-item mb-3 p-3 rounded-lg border bg-light">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <h6 class="mb-0 font-weight-bold small uppercase tracking-wider">1. NIB (Nomor Induk Berusaha)</h6>
                            <div class="custom-control custom-switch">
                                <input type="hidden" name="is_nib" value="0">
                                <input type="checkbox" class="custom-control-input" id="is_nib-{{ $item->id_alternatif }}" name="is_nib" value="1" {{ optional($leg)->is_nib ? 'checked' : '' }}>
                                <label class="custom-control-label" for="is_nib-{{ $item->id_alternatif }}">Tersedia</label>
                            </div>
                        </div>
                        <input type="text" class="form-control form-control-sm rounded-pill border-0 px-3 @error('no_nib') is-invalid @enderror" 
                            id="input-no_nib-{{ $item->id_alternatif }}"
                            name="no_nib" value="{{ old('no_nib', $no_nib) }}" placeholder="Contoh: 1234567890123" 
                            inputmode="numeric" maxlength="13">
                        <small class="text-muted mt-1 d-block"><i data-lucide="info" class="mr-1" style="width: 10px;"></i> Wajib 13 digit angka</small>
                    </div>

                    {{-- Sertifikat Halal --}}
                    <div class="legalitas-item mb-3 p-3 rounded-lg border bg-light">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <h6 class="mb-0 font-weight-bold small uppercase tracking-wider">2. Sertifikat Halal</h6>
                            <div class="custom-control custom-switch">
                                <input type="hidden" name="is_sertifikat_halal" value="0">
                                <input type="checkbox" class="custom-control-input" id="is_sertifikat_halal-{{ $item->id_alternatif }}" name="is_sertifikat_halal" value="1" {{ optional($leg)->is_sertifikat_halal ? 'checked' : '' }}>
                                <label class="custom-control-label" for="is_sertifikat_halal-{{ $item->id_alternatif }}">Tersedia</label>
                            </div>
                        </div>
                        <div class="input-group input-group-sm">
                            <div class="input-group-prepend">
                                <span class="input-group-text bg-white border-0 text-muted font-weight-bold px-3" style="border-radius: 50px 0 0 50px;">ID</span>
                            </div>
                            <input type="text" class="form-control form-control-sm border-0 px-2 @error('no_sertifikat_halal') is-invalid @enderror" 
                                id="input-no_sertifikat_halal-{{ $item->id_alternatif }}"
                                name="no_sertifikat_halal" value="{{ old('no_sertifikat_halal', $no_halal) }}" 
                                placeholder="17 digit angka" inputmode="numeric" maxlength="17" style="border-radius: 0 50px 50px 0;">
                        </div>
                        <small class="text-muted mt-1 d-block"><i data-lucide="info" class="mr-1" style="width: 10px;"></i> Masukkan 17 digit angka saja</small>
                    </div>

                    {{-- BPOM & PIRT (One of) --}}
                    <div class="legalitas-item mb-3 p-3 rounded-lg border bg-light shadow-sm" style="border-left: 4px solid #0d6efd !important;">
                        <h6 class="mb-3 font-weight-bold small uppercase tracking-wider">3. Izin Edar (BPOM / SP-PIRT)</h6>
                        
                        <div class="mb-3">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <span class="small text-muted font-weight-500">BPOM (MD / ML)</span>
                                <div class="custom-control custom-switch">
                                    <input type="hidden" name="is_bpom" value="0">
                                    <input type="checkbox" class="custom-control-input" id="is_bpom-{{ $item->id_alternatif }}" name="is_bpom" value="1" {{ optional($leg)->is_bpom ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="is_bpom-{{ $item->id_alternatif }}"></label>
                                </div>
                            </div>
                            <input type="text" class="form-control form-control-sm border-0 px-3 rounded-pill @error('no_bpom') is-invalid @enderror" 
                                id="input-no_bpom-{{ $item->id_alternatif }}"
                                name="no_bpom" value="{{ old('no_bpom', $no_bpom) }}" 
                                placeholder="Nomor BPOM">
                            <small class="text-muted mt-1 d-block"><i data-lucide="info" class="mr-1" style="width: 10px;"></i> Masukkan nomor BPOM beserta huruf (jika ada)</small>
                        </div>

                        <div class="mb-0">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <span class="small text-muted font-weight-500">SP-PIRT</span>
                                <div class="custom-control custom-switch">
                                    <input type="hidden" name="is_sp_pirt" value="0">
                                    <input type="checkbox" class="custom-control-input" id="is_sp_pirt-{{ $item->id_alternatif }}" name="is_sp_pirt" value="1" {{ optional($leg)->is_sp_pirt ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="is_sp_pirt-{{ $item->id_alternatif }}"></label>
                                </div>
                            </div>
                            <div class="d-flex align-items-center">
                                <input type="text" class="form-control form-control-sm border-0 px-3 flex-grow-1 @error('no_sp_pirt_1') is-invalid @enderror" 
                                    id="input-no_sp_pirt_1-{{ $item->id_alternatif }}"
                                    name="no_sp_pirt_1" value="{{ old('no_sp_pirt_1', $no_pirt_1) }}" 
                                    placeholder="13 digit" inputmode="numeric" maxlength="13" style="border-radius: 50px 0 0 50px;">
                                <div class="bg-white border-0 px-2 py-1 text-muted font-weight-bold">-</div>
                                <input type="text" class="form-control form-control-sm border-0 px-3 @error('no_sp_pirt_2') is-invalid @enderror" 
                                    id="input-no_sp_pirt_2-{{ $item->id_alternatif }}"
                                    name="no_sp_pirt_2" value="{{ old('no_sp_pirt_2', $no_pirt_2) }}" 
                                    placeholder="2 digit" inputmode="numeric" maxlength="2" style="border-radius: 0 50px 50px 0; width: 80px;">
                            </div>
                            <small class="text-muted mt-1 d-block"><i data-lucide="info" class="mr-1" style="width: 10px;"></i> Wajib 15 digit angka (Format: 13-2)</small>
                        </div>
                    </div>

                    <div class="form-group mb-0">
                        <label class="text-muted small font-weight-bold uppercase tracking-wider">Catatan / Keterangan</label>
                        <textarea class="form-control border-light bg-light px-3" name="keterangan" rows="2" style="border-radius: 12px;" placeholder="Tambahkan catatan jika diperlukan...">{{ optional($leg)->keterangan }}</textarea>
                    </div>
                </div>
                <div class="modal-footer border-top-0 pb-4 px-4">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4 shadow-sm">Update Status Filter</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    (function() {
        const formId = 'formLegalitas-{{ $item->id_alternatif }}';
        const form = document.getElementById(formId);
        
        if (!form) return;

        form.addEventListener('submit', function(e) {
            try {
                let isValid = true;
                
                // Clean old error messages
                form.querySelectorAll('.invalid-feedback-custom').forEach(el => el.remove());
                form.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));

                const checkField = (toggleId, inputNames, expectedDigits, label) => {
                    const toggle = document.getElementById(toggleId);
                    if (!toggle || !toggle.checked) return;

                    inputNames.forEach((name, index) => {
                        const input = form.querySelector(`[name="${name}"]`);
                        if (!input) return;

                        const val = input.value.trim().replace(/[^0-9]/g, '');
                        const expected = Array.isArray(expectedDigits) ? expectedDigits[index] : expectedDigits;

                        if (val.length !== expected) {
                            isValid = false;
                            input.classList.add('is-invalid');
                            
                            // Add error message if not present
                            const parent = input.closest('.legalitas-item') || input.parentElement;
                            if (!parent.querySelector(`.err-${name}`)) {
                                const err = document.createElement('div');
                                err.className = `text-danger small mt-1 invalid-feedback-custom err-${name}`;
                                err.innerHTML = `<i data-lucide="alert-circle" style="width:10px; height:10px; vertical-align: middle;"></i> <span style="vertical-align: middle;">${label} harus ${expected} digit angka.</span>`;
                                parent.appendChild(err);
                                if (window.lucide) lucide.createIcons();
                            }
                        }
                    });
                };

                // 1. NIB (13)
                checkField('is_nib-{{ $item->id_alternatif }}', ['no_nib'], 13, 'NIB');
                
                // 2. Halal (17)
                checkField('is_sertifikat_halal-{{ $item->id_alternatif }}', ['no_sertifikat_halal'], 17, 'Sertifikat Halal');
                
                // (BPOM is now arbitrary string, so no digit length check)
                
                // 4. SP-PIRT (13 & 2)
                checkField('is_sp_pirt-{{ $item->id_alternatif }}', ['no_sp_pirt_1', 'no_sp_pirt_2'], [13, 2], 'SP-PIRT');

                if (!isValid) {
                    e.preventDefault();
                    e.stopPropagation();
                    return false;
                }
            } catch (err) {
                console.error('Validation Error:', err);
                // In case of unexpected JS error, we still try to stop submission if things look wrong
                e.preventDefault();
                return false;
            }
        });
    })();
</script>
