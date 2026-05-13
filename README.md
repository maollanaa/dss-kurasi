# Kurasidss Project

Sistem Pendukung Keputusan (DSS) untuk Kurasi Produk.

## 🚀 Tahapan Testing & Sharing (Public Access)

Gunakan langkah-langkah ini untuk membagikan project lokal agar bisa diakses oleh user lain melalui internet untuk keperluan testing.

### 1. Persiapan Environment (Laragon)
*   **Virtual Host**: Pastikan project bisa diakses di browser lokal melalui `http://kurasidss-raw.test`.

### 2. Instalasi Expose
Expose digunakan untuk membuat tunnel dari localhost ke internet publik.
```bash
composer global require beyondcode/expose
```
`   
### 3. Konfigurasi Token
Daftar akun di [sharedwithexpose.com](https://sharedwithexpose.com/) dan masukkan tokennya:
```bash
expose token a4f53814-9afe-47fb-a6f6-6221bc59dc40
```

### 4. Build Assets (Penting)
Sebelum menjalankan sharing, pastikan asset sudah di-build untuk versi produksi agar CSS/JS bisa terload dengan benar di link publik:
```bash
npm run build
```

### 5. Menjalankan Sharing (Tunneling)
Jalankan perintah berikut di terminal:
```bash
expose share kurasidss-raw.test
```

### 6. Mengakses Link Publik
*   Salin **Public URL** yang muncul di terminal (contoh: `https://xxxxxx.sharedwithexpose.com`).
*   Bagikan link tersebut ke user testing.

---

## 🛠 Troubleshooting Tampilan (Layar Putih)
Jika link sudah bisa diakses tapi tampilan berantakan atau hanya layar putih:
1. Pastikan sudah menjalankan `npm run build`.
2. Jangan menggunakan `npm run dev` saat melakukan sharing via Expose.
3. Pastikan `APP_URL` di file `.env` sudah sesuai (atau biarkan sistem mendeteksi otomatis via Proxy yang sudah dikonfigurasi).

---

## 🛠 Tech Stack
- **Framework**: Laravel 11
- **Database**: MySQL
- **Environment**: Laragon (Windows)
- **Sharing Tool**: Expose by Beyond Code

## 📝 Catatan Penting
- Pastikan laptop tetap menyala dan terminal tetap terbuka selama proses testing.
- Database yang digunakan adalah database lokal di laptop Anda.
- Kecepatan akses tergantung pada koneksi upload internet Anda.
