# Dokumentasi Sementara Sistem DSS Kurasi Produk UMKM

Berikut adalah rangkuman dari struktur sistem dan sejauh mana fitur yang sudah disiapkan atau dikonfigurasi saat ini.

---

## 1. Fitur yang Tersedia & Terkonfigurasi Saat Ini
Sistem ini dirancang sebagai **Sistem Pendukung Keputusan (DSS)** untuk melakukan kurasi terhadap produk UMKM menggunakan metode **AHP (Analytical Hierarchy Process)** dan **Profile Matching**. 

Sejauh ini yang sudah terbangun adalah kerangka utama berupa:
- **Autentikasi (Login/Logout)**: 
  - Login dengan email dan password.
  - Implementasi *Remember Me* fungsional (menyimpan data isian form login menggunakan Cookie).
  - Modal proteksi untuk konfirmasi sistem Logout.
- **Role-Based Access Control (RBAC)**:
  - Memisahkan tampilan halaman utama (Dashboard) antara peran **Admin** dan **Kurator**.
- **User Interface (Pembangunan Tata Letak)**:
  - Struktur dasar UI menggunakan Bootstrap 4, dengan dukungan SCSS modern via Vite.
  - Sidebar dinamis (menyesuaikan layar 100% tinggi) & Navbar responsif.
- **Arsitektur Awal Basis Data & Migrasi DSS**:
  - Tabel-tabel dan kerangka utama metode pengambilan keputusan (AHP dan Penilaian Profile Matching) telah siap secara struktur untuk kemudian dioperasikan oleh alur logika Backend (Controller/Service).
  - Skrip pengisian data dasar default (*seeder/insert_default*) ke Kriteria dan Skalanya juga telah dibuat.

---

## 2. Struktur Database (Berdasarkan File *Migration*)
Melihat dari kerangka skema basis data yang sudah dirancang pada folder migrasi, rancangan database sistem ini telah disiapkan dengan matang untuk mendukung logika DSS. Berikut rinciannya:

### A. Tabel Manajemen Utama
- `users`: Tabel default untuk pengguna sistem (Admin, Kurator, dll).
- `cache`, `jobs`: Tabel bawaan Laravel/optimasi server untuk sesi, caching, dan queue sistem *background task*.

### B. Tabel Data Master DSS (Data Alternatif & Kriteria)
- `kriteria`: Menyimpan aspek / kriteria apa saja yang akan dinilai pada setiap produk (misalnya: Harga, Kualitas, Legalitas, dll).
- `kriteria_skala`: Menyimpan sub-kriteria (skala penilaian) dari masing-masing kriteria.
- `alternatif`: Data master dari UMKM atau Produk yang sedang di-kurasi (kandidat).
- `alternatif_legalitas`: Tabel untuk merekam data kelengkapan perizinan hukum (legalitas) dari kandidat produk.

### C. Tabel Logika DSS - Metode AHP (Analytical Hierarchy Process)
*Berfungsi menimbang dan mecari tingkat kepentingan dari masing-masing Kriteria.*
- `ahp_sesi`: Riwayat/sesi dilakukannya perhitungan pembobotan kriteria AHP. 
- `ahp_perbandingan`: Menyimpan nilai perbandingan matriks antar satu kriteria dengan kriteria lainnya (pairwise comparison).
- `ahp_bobot`: Menyimpan hasil bobot/prioritas final tiap kriteria setelah dihitung via formula AHP.

### D. Tabel Proses Penilaian & Hasil Kurasi
*Sistem manajemen periode atau batching serta perekapan hasil nilai akhir produk alternatif.*
- `periode_kurasi`: Tabel pengelompokan (batch) event kurasi, misal "Periode Kurasi Triwulan 1".
- `periode_alternatif`: Tabel penghubung (*pivot*) produk alternatif apa saja yang masuk ke dalam periode kurasi tertentu.
- `penilaian_kurasi`: Tabel inti untuk merekam nilai/rating asli yang diberikan oleh Kurator ke setiap Alternatif pada Kriteria tertentu.
- `hasil_kurasi` & `hasil_kurasi_detail`: Tabel untuk menyimpan persentase, total nilai kelayakan/ranking DSS (*scoring akhir*), serta status lolos tidaknya UMKM tersebut.

---

## Kesimpulan
Fondasi sistem seperti **Autentikasi**, **Roles Dashboard**, perapian **UI (Navbar/Sidebar)**, serta **Skema Migrasi Database Ekosistem DSS**, sudah tertata sepenuhnya. Tahap pengembangan selanjutnya adalah mulai membangun fungsi / menu CRUD (Create, Read, Update, Delete) di controller masing-masing, serta penyusunan logika perhitungannya (Formula AHP secara matematis).
