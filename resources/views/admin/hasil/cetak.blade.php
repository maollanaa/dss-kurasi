<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Hasil Kurasi - {{ $periode->nama_periode }}</title>
    
    {{-- Google Fonts: Poppins --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    {{-- Bootstrap 4 CSS --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    
    {{-- Vite Assets --}}
    @vite(['resources/scss/app.scss'])
</head>
<body class="report-body">
    <div class="no-print mb-4 text-right">
        <button onclick="window.print()" class="btn btn-primary shadow-sm px-4">
            Cetak Laporan
        </button>
        <button onclick="window.close()" class="btn btn-light border px-4">Tutup</button>
    </div>

    <div class="report-header">
        <div class="report-title">Laporan Hasil Penilaian Kurasi Produk UMKM</div>
        <div>Periode: {{ $periode->nama_periode }}</div>
    </div>

    <div class="info-section">
        <table class="info-table">
            <tr>
                <td width="150"><strong>Tanggal Pelaksanaan</strong></td>
                <td width="10">:</td>
                <td>{{ \Carbon\Carbon::parse($periode->tanggal_kurasi)->translatedFormat('d F Y') }}</td>
            </tr>
            <tr>
                <td><strong>Kurator Penilai</strong></td>
                <td>:</td>
                <td>{{ $periode->kurator->name }}</td>
            </tr>
            <tr>
                <td><strong>Status Kurasi</strong></td>
                <td>:</td>
                <td>Selesai</td>
            </tr>
            <tr>
                <td><strong>Total Produk</strong></td>
                <td>:</td>
                <td>{{ count($results) }} Produk</td>
            </tr>
        </table>
    </div>

    {{-- TABEL 1: PRODUK LOLOS --}}
    <h5 class="font-weight-bold mb-3" style="text-decoration: underline;">I. Daftar Produk Lolos Kurasi</h5>
    <table class="table-report">
        <thead>
            <tr>
                <th style="width: 5%;">No</th>
                <th style="width: 30%;">Nama Produk</th>
                <th style="width: 25%;">Brand / UMKM</th>
                <th style="width: 25%;">Nama Pemilik</th>
                <th style="width: 15%;">Skor Akhir</th>
            </tr>
        </thead>
        <tbody>
            @php $noLolos = 1; @endphp
            @foreach($results as $res)
                @if($res->status_lolos)
                    <tr>
                        <td class="text-center">{{ $noLolos++ }}</td>
                        <td><strong>{{ $res->alternatif->nama_produk }}</strong></td>
                        <td>{{ $res->alternatif->brand ?? $res->alternatif->nama_brand_umkm }}</td>
                        <td>{{ $res->alternatif->nama_pemilik }}</td>
                        <td class="text-center font-weight-bold text-success">{{ number_format($res->total_score, 3) }}</td>
                    </tr>
                @endif
            @endforeach
            @if($noLolos == 1)
                <tr>
                    <td colspan="5" class="text-center text-muted font-italic">Tidak ada produk yang dinyatakan lolos.</td>
                </tr>
            @endif
        </tbody>
    </table>

    {{-- TABEL 2: PRODUK TIDAK LOLOS --}}
    <h5 class="font-weight-bold mb-3" style="text-decoration: underline;">II. Daftar Produk Tidak Lolos & Catatan Evaluasi</h5>
    <table class="table-report">
        <thead>
            <tr>
                <th style="width: 5%;">No</th>
                <th style="width: 20%;">Nama Produk</th>
                <th style="width: 15%;">Brand / UMKM</th>
                <th style="width: 60%;">Evaluasi & Saran Perbaikan</th>
            </tr>
        </thead>
        <tbody>
            @php $noTidakLolos = 1; @endphp
            @foreach($results as $res)
                @if(!$res->status_lolos)
                    <tr>
                        <td class="text-center">{{ $noTidakLolos++ }}</td>
                        <td>
                            <strong>{{ $res->alternatif->nama_produk }}</strong><br>
                            <small class="text-muted">{{ $res->alternatif->nama_pemilik }}</small>
                        </td>
                        <td>{{ $res->alternatif->brand ?? $res->alternatif->nama_brand_umkm }}</td>
                        <td>
                            @if(!$res->is_lolos_legalitas)
                                <div class="text-danger font-weight-bold small mb-1">GAGAL TAHAP LEGALITAS</div>
                                <span class="eval-note text-justify">
                                    Dokumen wajib yang belum lengkap: <strong>{{ implode(', ', $res->missing_docs) }}</strong>.<br>
                                    <em>Rekomendasi: Demi memenuhi standar regulasi dan keamanan pangan, silakan lengkapi dokumen yang diperlukan. Produk dapat kembali mengajukan produk ini pada periode kurasi mendatang.</em>
                                </span>
                            @else
                                <div class="text-warning font-weight-bold small mb-1">GAGAL TAHAP TEKNIS (SKOR: {{ number_format($res->total_score, 3) }})</div>
                                <span class="eval-note text-justify">
                                    Aspek yang perlu ditingkatkan:
                                    @foreach($res->evaluations as $eval)
                                        <span class="eval-item">- {{ $eval['kriteria'] }}, saat ini produk belum memenuhi <strong>"{{ $eval['target_desc'] }}"</strong>.</span>
                                    @endforeach
                                    <em>Rekomendasi: Produk ini memiliki potensi besar. Kami menyarankan peningkatan kualitas pada kriteria di atas agar memiliki daya saing yang lebih tinggi pada periode kurasi selanjutnya.</em>
                                </span>
                            @endif
                        </td>
                    </tr>
                @endif
            @endforeach
            @if($noTidakLolos == 1)
                <tr>
                    <td colspan="4" class="text-center text-muted font-italic">Seluruh produk dinyatakan lolos kurasi.</td>
                </tr>
            @endif
        </tbody>
    </table>

    <div class="mt-5 pt-4">
        <div style="display: flex; justify-content: flex-end;">
            <div style="width: 250px; text-align: center;">
                <p class="mb-5">Dicetak pada: {{ now()->translatedFormat('d F Y') }}</p>
                <br><br>
                <p class="font-weight-bold mb-0">( ____________________ )</p>
                <p class="small">Kurator Penilai</p>
            </div>
        </div>
    </div>

    <script>
        window.onload = function() {
            // window.print();
        }
    </script>
</body>
</html>
