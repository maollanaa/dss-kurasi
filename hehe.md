# Kebutuhan Sistem — Kurasidss

Dokumen ini merangkum kebutuhan fungsional dan non-fungsional sistem berdasarkan PRD dan implementasi saat ini.

---

## 1. Peran Pengguna (User Roles)

Sistem ini dirancang untuk diakses oleh dua jenis pengguna utama dengan tanggung jawab yang berbeda:

| Peran | Deskripsi Singkat & Tanggung Jawab Utama |
| :--- | :--- |
| **Admin** | Bertanggung jawab penuh atas manajemen data master (Kriteria, Produk UMKM), pengaturan bobot AHP, serta mengelola dan memantau seluruh periode kurasi. |
| **Kurator** | Bertugas melakukan penilaian faktual di lapangan terhadap produk-produk UMKM yang masuk dalam periode kurasi yang sedang berjalan. |

---

## 2. Kebutuhan Fungsional (Functional Requirements)

### 🛠️ Modul 1: Fondasi & Autentikasi (Sudah Selesai)
- **Sistem dapat** mengelola autentikasi pengguna (login, reset password, sesi) secara aman bagi Admin dan Kurator.
- **Sistem dapat** membatasi hak akses fitur dan menampilkan dashboard dinamis berdasarkan peran pengguna (*Role-based Access Control*).

### 📋 Modul 2: Manajemen Kriteria & Skala (Sudah Selesai)
- **Sistem dapat** melakukan manajemen data kriteria (CRUD) dan pengelompokan aspek beserta penetapan target nilai ideal (profil target).
- **Sistem dapat** mengelola rubrik/skala penilaian (1-5) untuk setiap kriteria, termasuk fitur aktivasi skala yang muncul pada form kurator.

### 📦 Modul 3: Manajemen Produk & Legalitas (Sudah Selesai/On-going)
- **Sistem dapat** mengelola data produk UMKM (CRUD) serta melakukan validasi otomatis dan penyaringan terhadap syarat legalitas wajib (NIB, Halal, BPOM/PIRT).
- **Sistem dapat** melakukan impor data produk secara massal via Excel menggunakan template standar yang disediakan oleh sistem.

### ⚖️ Modul 4: Kalkulasi AHP (Rencana)
- **Sistem dapat** menghitung bobot kriteria melalui matriks perbandingan berpasangan (*Pairwise Comparison*) dengan fitur kalkulasi otomatis.
- **Sistem dapat** menguji konsistensi matriks (Consistency Ratio) dan menyimpan bobot akhir ke dalam sesi AHP yang terkunci per periode.

### ✍️ Modul 5: Manajemen Periode & Penilaian (Rencana)
- **Sistem dapat** mengelola periode kurasi (batching) untuk menentukan daftar produk dan antrean penilaian bagi Kurator.
- **Sistem dapat** menyediakan form penilaian interaktif yang menampilkan panduan rubrik aktif serta melacak progres penilaian secara *real-time*.

### 📊 Modul 6: Hasil, Ranking & Laporan (Rencana)
- **Sistem dapat** menghitung skor akhir dan menghasilkan ranking produk secara otomatis menggunakan logika *Profile Matching* (Analisis Gap).
- **Sistem dapat** memberikan catatan evaluasi otomatis bagi produk di bawah target dan mengekspor laporan laporan resmi ke format PDF.

---

## 3. Kebutuhan Non-Fungsional (Non-Functional Requirements)

| Aspek | Status Saat Ini | Rencana Pengembangan |
| :--- | :--- | :--- |
| **Keamanan (Security)** | Proteksi CSRF, enkripsi password (bcrypt), session protection, dan konfirmasi logout. | Audit log (mencatat siapa yang mengubah kriteria/bobot) dan pengamanan endpoint API. |
| **Kinerja (Performance)** | Penggunaan DataTables untuk rendering data ribuan baris di sisi client dengan cepat. | Caching hasil perhitungan AHP dan optimalisasi query untuk agregasi ranking. |
| **UI/UX (Usability)** | Desain premium dengan SCSS kustom, responsivitas sidebar (tablet/mobile), dan micro-animations. | Panduan interaktif (guided tour) untuk admin baru saat mengisi matriks AHP. |
| **Integritas Data** | Penggunaan database transaction pada operasi krusial dan validasi server-side yang ketat. | Sistem backup database otomatis dan sinkronisasi data import yang lebih cerdas. |
| **Ketersediaan (Availability)** | Arsitektur Laravel yang stabil dan penanganan error yang user-friendly. | Implementasi logging yang lebih detail untuk troubleshooting cepat di production. |

---

## 4. Library JS yang Digunakan

Proyek ini menggunakan beberapa library JavaScript untuk mendukung fungsionalitas dan tampilan:

- **Lucide Icons**: Digunakan untuk seluruh ikon di dalam sistem (Dashboard, Sidebar, Button).
- **jQuery**: Library dasar untuk manipulasi DOM dan mendukung plugin lainnya.
- **DataTables**: Digunakan untuk menampilkan tabel data (User, Produk) dengan fitur pencarian, sorting, dan pagination.
- **Select2**: Digunakan untuk elemen input select yang lebih interaktif dan mendukung pencarian.
- **Axios**: Digunakan untuk menangani request HTTP/API (standar Laravel).
- **AOS (Animate On Scroll)**: Digunakan untuk memberikan efek animasi saat elemen di-scroll.
- **Swiper**: Digunakan untuk komponen slider/carousel jika diperlukan.
- **Chart.js**: Digunakan untuk visualisasi data statistik pada dashboard.
- **jQuery Mask Plugin**: Digunakan untuk formatting input (seperti input tanggal atau nomor).
- **Moment.js**: Digunakan untuk pengelolaan dan manipulasi format waktu/tanggal.
- **Bootstrap 4 (JS)**: Library UI utama untuk komponen seperti Modal, Dropdown, dan Tooltip.
