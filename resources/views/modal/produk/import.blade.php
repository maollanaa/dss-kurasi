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
            <form id="formImportProduk" action="{{ route('admin.produk.import') }}" method="POST"
                enctype="multipart/form-data">
                @csrf
                <div class="modal-body px-4 pb-4">
                    <div class="form-group mb-0">
                        <label class="text-muted small font-weight-bold uppercase tracking-wider mb-2">File Excel
                            (.xlsx, .xls)</label>
                        <div class="custom-file rounded-pill overflow-hidden">
                            <input type="file" class="custom-file-input" id="file_excel" name="file_excel"
                                accept=".xlsx, .xls" required
                                onchange="$(this).next('.custom-file-label').html(this.files[0].name)">
                            <label class="custom-file-label" for="file_excel">Pilih file...</label>
                        </div>

                        <!-- Progress Bar Container -->
                        <div id="importProgressContainer" class="mt-3" style="display: none;">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <span class="small font-weight-bold text-primary">Proses Upload...</span>
                                <span id="importProgressText" class="small text-muted">0% (0 / 0 MB)</span>
                            </div>
                            <div class="progress rounded-pill" style="height: 10px;">
                                <div id="importProgressBar"
                                    class="progress-bar progress-bar-striped progress-bar-animated bg-primary"
                                    role="progressbar" style="width: 0%"></div>
                            </div>
                        </div>

                        <div class="mt-3 p-3 rounded bg-light border-left border-primary"
                            style="border-left-width: 4px !important;">
                            <h6 class="small font-weight-bold mb-2 text-dark">Instruksi:</h6>
                            <ul class="small text-muted mb-0 pl-3">
                                <li>Pastikan file memiliki 2 sheet: "Detail Produk" & "Legalitas".</li>
                                <li>Kolom **Foto Produk (E)** bisa diisi gambar langsung di Excel.</li>
                                <li>Sistem akan mengupdate data jika produk & brand sudah ada.</li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-top-0 px-4 pb-4">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-dismiss="modal">Batal</button>
                    <button type="submit" id="btnStartImport" class="btn btn-primary rounded-pill px-4 shadow-sm">Mulai
                        Import</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
        $('#formImportProduk').on('submit', function (e) {
            e.preventDefault();

            let formData = new FormData(this);
            let $form = $(this);
            let $btn = $('#btnStartImport');
            let $progressBar = $('#importProgressBar');
            let $progressContainer = $('#importProgressContainer');
            let $progressText = $('#importProgressText');

            // Reset and Show Progress
            $progressBar.css('width', '0%');
            $progressText.html('0% (0 / 0 MB)');
            $progressContainer.fadeIn();
            $btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm mr-2"></span>Memproses...');

            $.ajax({
                url: $form.attr('action'),
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                xhr: function () {
                    var xhr = new window.XMLHttpRequest();
                    xhr.upload.addEventListener("progress", function (evt) {
                        if (evt.lengthComputable) {
                            var percentComplete = Math.round((evt.loaded / evt.total) * 100);
                            $progressBar.css('width', percentComplete + '%');

                            let loadedMB = (evt.loaded / (1024 * 1024)).toFixed(2);
                            let totalMB = (evt.total / (1024 * 1024)).toFixed(2);
                            $progressText.html(percentComplete + '% (' + loadedMB + ' / ' + totalMB + ' MB)');

                            if (percentComplete === 100) {
                                $progressText.html('Menyimpan ke Database...');
                            }
                        }
                    }, false);
                    return xhr;
                },
                success: function (response) {
                    // Since controller returns redirect, we manually refresh to show success flash message
                    location.reload();
                },
                error: function (xhr) {
                    let errorMsg = "Terjadi kesalahan saat import.";
                    if (xhr.status === 413) {
                        errorMsg = "File terlalu besar untuk diupload.";
                    }
                    alert(errorMsg);
                    $btn.prop('disabled', false).html('Mulai Import');
                    $progressContainer.hide();
                }
            });
        });
    });
</script>