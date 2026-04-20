halaman ini akan aku jadikan tanya jawab dengan agent untuk implementation plan jadi mohon bantuannya

---

# Ide & Perencanaan Tampilan Dashboard Admin

Untuk sistem **Sistem Pendukung Keputusan (DSS) Kurasi Produk UMKM**, Dashboard Admin adalah pusat kendali (*Control Center*). Di bawah *Welcome Card*, sebaiknya kita menampilkan ringkasan data yang sifatnya intuitif (*High-level overview*). 

Berikut adalah **Implementation Plan** usulan mengenai apa saja yang sebaiknya ditampilkan:

## 1. Kartu Statistik Utama (Summary Cards)
Kita bisa meletakkan 4 buah kartu berjejer (menggunakan `row` dan `col-md-3`), yang menampilkan angka cepat:
- **Total UMKM Terdaftar**: Angka jumlah seluruh produk/UMKM yang ada di data master.
- **Periode Kurasi Aktif**: Menampilkan nama/status periode yang saat ini sedang berjalan.
- **Total Kriteria AHP**: Menampilkan berapa banyak kriteria yang digunakan sistem saat ini.
- **Produk Lolos Kurasi**: Menilik sekilas berapa total UMKM yang berhasil lolos standar secara keseluruhan.

## 2. Grafik Visual (Charts)
Untuk mempercantik tampilan dan memudahkan analisis, kita bisa tambahkan **Chart.js**:
- **Grafik Batang (Bar Chart) / Pie Chart**: Menampilkan rasio UMKM Lolos vs Tidak Lolos (Berdasarkan Periode Terakhir). Ini penting untuk melihat persentase kelulusan kurasi UMKM.
- **Line Chart (Opsional)**: Menampilkan tren jumlah UMKM yang mendaftar dari bulan ke bulan.
- **Gauge / Progress Bar**: Menampilkan persentase selesainya tugas Kurator di periode saat ini (Misal: *Total 50 Produk, 35 Sudah Dinilai, 15 Belum Dinilai*).

## 3. Tabel Akses Cepat (Quick Access Tables)
Di bagian terbawah, kita bisa memasang tabel berukuran kecil (hanya menampilkan 5 data teratas):
- **Aktivitas Periode Berjalan**: Menampilkan 5 UMKM terbaru yang masuk di periode saat ini beserta status penilaiannya (Sudah Dinilai/Belum Dinilai).
- **Hasil Kurasi Tertinggi (Leaderboard)**: Top 5 produk UMKM dengan skor AHP dan Profile Matching tertinggi.

---

### Diskusi Rencana Implementasi:
**Bagaimana menurut Anda?**
Jika Anda setuju dengan rancangan di atas, kita bisa menggunakan `_card.scss` yang tadi dibuat untuk mendesain `.card-stat`. Lalu kita integrasikan *Chart.js* untuk grafiknya. 

Tulis feedback atau bagian mana yang ingin diprioritaskan untuk dibangun terlebih dahulu di bawah sini, lalu panggil saya kembali!