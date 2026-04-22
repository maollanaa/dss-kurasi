<!-- Modal Import -->
<div class="modal fade" id="modalImport" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content border-0 shadow-lg overflow-hidden">
            <div class="modal-header bg-white border-bottom-0 pt-4 px-4">
                <h5 class="modal-title font-weight-bold text-dark">Import Data Produk</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('admin.produk.import') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body px-4 pb-4">
                    <div class="form-group mb-0">
                        <label class="text-muted small font-weight-bold uppercase tracking-wider mb-2">File Excel (.xlsx, .xls)</label>
                        <div class="custom-file rounded-pill overflow-hidden">
                            <input type="file" class="custom-file-input" id="file_excel" name="file_excel" accept=".xlsx, .xls" required onchange="$(this).next('.custom-file-label').html(this.files[0].name)">
                            <label class="custom-file-label" for="file_excel">Pilih file...</label>
                        </div>
                        <div class="mt-3 p-3 rounded bg-light">
                            <h6 class="small font-weight-bold mb-2">Instruksi:</h6>
                            <ul class="small text-muted mb-0 pl-3">
                                <li>Pastikan file memiliki 2 sheet: "Detail Produk" & "Legalitas".</li>
                                <li>Nama Produk & Brand digunakan sebagai pengenal unik.</li>
                                <li>Sistem akan mengupdate data jika produk sudah ada.</li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-top-0 px-4 pb-4">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4 shadow-sm">Mulai Import</button>
                </div>
            </form>
        </div>
    </div>
</div>
