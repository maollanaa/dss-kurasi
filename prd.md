PRD — Sistem Pendukung Keputusan Kurasi Produk Pangan UMKM
 
 1. Latar Belakang & Tujuan Produk
Proses kurasi produk pangan UMKM untuk pasar modern di Kabupaten Sidoarjo saat ini masih dilakukan secara manual — semua kriteria diperlakukan setara tanpa bobot, perhitungan dilakukan akumulatif, dan rawan kesalahan saat jumlah produk besar. Hal ini menyebabkan hasil pemeringkatan kurang proporsional dan sulit dipertanggungjawabkan.
Tujuan sistem adalah menyediakan platform web berbasis metode AHP (pembobotan kriteria) dan Profile Matching (pemeringkatan berdasarkan gap terhadap profil ideal) untuk menghasilkan rekomendasi kelayakan produk yang objektif, konsisten, dan transparan.

2. Pengguna & Peran
Role - Deskripsi
Admin - Mengelola konfigurasi sistem: kriteria, parameter, nilai target, data produk, bobot AHP, dan periode kurasi
Kurator - Mengisi nilai aktual produk per periode kurasi dan melihat hasil

3. Ruang Lingkup Sistem
Dalam Scope:
Manajemen periode kurasi
Manajemen data alternatif (produk UMKM) per periode
Filter legalitas sebagai syarat awal (bukan perhitungan)
Konfigurasi kriteria & parameter penilaian (skala 1–5)
Penetapan nilai target per kriteria
Modul AHP: matriks perbandingan berpasangan → bobot kriteria + uji konsistensi (CR)
Modul Profile Matching: nilai aktual → gap → bobot gap → skor akhir
Output: ranking produk + catatan evaluasi (gap negatif)
History hasil kurasi per periode

Di luar Scope:
Pemasaran, distribusi, atau penjualan produk
Integrasi dengan sistem eksternal (BPOM, OSS, dll.)
Aspek legalitas tidak masuk perhitungan AHP/PM

4. Fitur & User Stories
4.1 Manajemen Periode Kurasi
US-01 — Sebagai Admin, saya ingin membuat periode kurasi baru agar setiap siklus kurasi tercatat secara terpisah.
Acceptance Criteria:
Form berisi: nama periode, tanggal mulai, dinas penanggung jawab
Status periode: Draft → Berlangsung → Selesai
Admin dapat melihat daftar semua periode kurasi beserta statusnya
Admin membuat periode kurasi. Sistem secara otomatis akan mengikat (link) periode ini dengan Sesi AHP yang sedang aktif saat itu.

US-02 — Sebagai Admin, saya ingin melihat daftar periode kurasi beserta statusnya agar dapat memantau progres kurasi yang sedang dan sudah berjalan.
Acceptance Criteria:
Tabel menampilkan: nama periode, tanggal, dinas, jumlah produk, status
Periode yang selesai menampilkan link ke hasil kurasi

4.2 Manajemen Data Alternatif (Produk)
US-03 — Sebagai Admin, saya ingin menambahkan produk UMKM ke dalam periode kurasi agar produk tersebut dapat dinilai oleh kurator.
Acceptance Criteria:
Form berisi: nama produk, merk, deskripsi singkat
Checklist legalitas (multi-item): NIB, PIRT, Sertifikat Halal, dll.
Produk yang tidak memenuhi semua item legalitas diberi status Tidak Lolos Legalitas dan tidak muncul dalam antrian penilaian kurator
Produk yang memenuhi semua legalitas berstatus Siap Dikurasi

US-04 — Sebagai Admin, saya ingin melihat daftar produk dalam satu periode beserta status legalitasnya agar dapat mengidentifikasi produk yang bisa lanjut ke tahap penilaian.

4.3 Manajemen Kriteria & Parameter
US-05 — Sebagai Admin, saya ingin menambahkan dan mengonfigurasi kriteria penilaian agar sistem dapat menyesuaikan standar kurasi yang digunakan.
Acceptance Criteria:
Field kriteria: nama, deskripsi, aspek (Kualitas Produk / Kemasan), jenis parameter, nilai target (1–5)
Terdapat 4 jenis parameter:
Range — input rentang nilai yang dikonversi ke skala 1–5 (contoh: harga, kapasitas produksi, masa kadaluarsa)
Ya/Tidak — jawaban biner dikonversi ke skala (contoh: kode produksi)
Pemenuhan Keadaan — tingkat pemenuhan kondisi tertentu (contoh: material kemasan, informasi label, uji nutrisi)
Subjektif Berskala — penilaian langsung 1–5 oleh kurator (contoh: rasa, desain kemasan)

Admin dapat mendefinisikan rubrik konversi untuk masing-masing jenis parameter
Kriteria dapat diaktifkan/dinonaktifkan

US-06 — Sebagai Admin, saya ingin menetapkan nilai target untuk setiap kriteria agar sistem memiliki profil ideal sebagai standar pemeringkatan.
Acceptance Criteria:
Nilai target menggunakan skala 1–5
Nilai target tersimpan dan digunakan dalam perhitungan Profile Matching

4.4 Modul AHP (Pembobotan Kriteria)
US-07 — Sebagai Admin, saya ingin mengisi matriks perbandingan berpasangan antar kriteria menggunakan skala Saaty (1–9) agar sistem dapat menghitung bobot masing-masing kriteria.
Acceptance Criteria:
Tampilan matriks n×n (n = jumlah kriteria aktif)
Nilai diagonal = 1, sisi simetris otomatis terisi nilai reciprocal
Dropdown atau input angka untuk tiap pasangan kriteria (nilai 1–9)
Setelah disimpan, sistem otomatis menghitung:

Normalisasi matriks
Bobot (eigenvector) masing-masing kriteria
λmax, CI, RI, CR

Hasil CR ditampilkan dengan indikator visual: ✅ Konsisten (CR ≤ 0.10) / ❌ Tidak Konsisten (CR > 0.10)
Jika tidak konsisten, Admin diminta merevisi nilai perbandingan
Seluruh data (matriks, bobot, CR) tersimpan ke database
Bobot ini akan digunakan oleh semua periode kurasi baru hingga ada sesi AHP baru yang disahkan.

US-08 — Sebagai Admin, saya ingin melihat histori bobot AHP yang pernah disimpan agar dapat melacak perubahan prioritas kriteria dari waktu ke waktu.
Admin dapat melihat daftar sesi AHP terdahulu. Sistem menjamin bahwa perubahan bobot di masa sekarang tidak akan mengubah hasil perhitungan periode di masa lalu karena referensi ID sesi tersimpan secara permanen di tiap tabel periode.

4.5 Proses Kurasi oleh Kurator
US-09 — Sebagai Kurator, saya ingin melihat daftar periode kurasi yang perlu dikerjakan agar saya tahu tugas penilaian yang harus diselesaikan.
Acceptance Criteria:

Halaman awal kurator menampilkan CTA "Mulai Kurasi"
Daftar periode dengan status Berlangsung ditampilkan
Hanya produk berstatus Siap Dikurasi yang muncul dalam antrian penilaian

US-10 — Sebagai Kurator, saya ingin mengisi nilai aktual untuk setiap kriteria per produk agar sistem dapat menghitung gap dan skor akhir.
Acceptance Criteria:
Kurator mengisi nilai aktual (1–5) berdasarkan parameter.

Rubrik konversi ditampilkan sebagai panduan di samping/bawah input
Kurator dapat menyimpan progres sementara (draft) dan melanjutkan
Indikator progres: berapa produk sudah dinilai dari total

US-11 — Sebagai Kurator, saya ingin menyelesaikan proses kurasi agar sistem memproses perhitungan dan mengunci data penilaian.
Acceptance Criteria:
Tombol "Selesaikan Kurasi" hanya aktif jika semua produk sudah dinilai
Konfirmasi sebelum finalisasi
Setelah konfirmasi, sistem menjalankan kalkulasi AHP-Profile Matching secara otomatis
Status periode berubah menjadi Selesai

4.6 Kalkulasi AHP-Profile Matching (Background)
US-12 — Sebagai sistem, saya ingin mengeksekusi kalkulasi setelah kurator menyelesaikan penilaian agar hasil ranking tersedia secara otomatis.
Acceptance Criteria (alur perhitungan):
Normalisasi data alternatif: nilai input kurator dikonversi ke skala 1–5 sesuai rubrik
Perhitungan Gap: Gap = Nilai Aktual − Nilai Target per kriteria per produk
Konversi Gap ke Bobot Gap (tabel standar):
Gap -> Bobot
0  -> 5
1  -> 4.5
-1 -> 4
2  -> 3.5
-2 -> 3
3  -> 2.5
-3 -> 2
4  -> 1.5
-4 -> 1
5  -> 0.5
-5 -> 0

Skor Akhir: Nilai Total = Σ (Bobot Gap × Bobot AHP) untuk semua kriteria
Ranking: produk diurutkan dari skor tertinggi ke terendah
Catatan Evaluasi: kriteria dengan gap negatif per produk ditandai sebagai "perlu perbaikan di kriteria yg negatif gap"
Semua hasil disimpan ke database

4.7 Hasil & Laporan Kurasi
US-13 — Visualisasi Ranking: Menampilkan tabel produk dari skor tertinggi ke terendah beserta catatan evaluasi (gap negatif).

Sebagai Admin dan Kurator, saya ingin melihat hasil kurasi berupa ranking produk dan catatan evaluasi agar dapat memberikan rekomendasi yang dapat dipertanggungjawabkan.
Acceptance Criteria:
Halaman hasil menampilkan:
Informasi periode (nama, tanggal, dinas)
Tabel ranking: No. Urut, Nama Produk, Merk, Skor Akhir, Status Rekomendasi
Filter/sort berdasarkan ranking atau nama produk

Detail per produk menampilkan:
Skor per kriteria (bobot gap × bobot AHP)
Catatan evaluasi: daftar kriteria dengan gap negatif + keterangan perbaikan yang diperlukan

US-14 — Sebagai Admin dan Kurator, saya ingin mengakses history hasil kurasi dari periode sebelumnya agar dapat membandingkan perkembangan produk UMKM dari waktu ke waktu.

US-15 — Export PDF :
Sebagai Admin/Kurator, saya ingin mengunduh laporan hasil kurasi ke dalam format PDF.
Acceptance Criteria:
PDF berisi Header (Nama Periode, Tanggal, Penanggung Jawab).
Tabel ringkasan bobot kriteria yang digunakan (referensi Sesi AHP).
Tabel ranking lengkap (Nama Produk, Skor Akhir, Status).
Lampiran detail gap per produk untuk kriteria yang tidak memenuhi target.


Tech Stack:
Backend : PHP + Laravel (MVC)
Frontend: Blade + Bootstrap 4 + SCSS
Database: MySQL