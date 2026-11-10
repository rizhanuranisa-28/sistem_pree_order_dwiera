# Sistem Pre-Order Dwiera Cake

Aplikasi web untuk membantu pelanggan melihat produk Dwiera Cake, membuat pesanan pre-order, memilih jadwal pengambilan, dan mengirim bukti pembayaran. Admin dan owner dapat mengelola kegiatan operasional sesuai hak akses masing-masing.

## Repository

- GitHub: [rizhanuranisa-28/sistem_pree_order_dwiera](https://github.com/rizhanuranisa-28/sistem_pree_order_dwiera)
- URL aplikasi: Belum dicantumkan.

## Fitur

### Pelanggan

- Registrasi, login, dan pengelolaan profil.
- Melihat katalog dan detail produk beserta pilihan ukuran.
- Menambahkan produk ke keranjang dan membuat pesanan pre-order.
- Memilih tanggal dan jam pengambilan.
- Memilih pembayaran cash atau transfer.
- Mengunggah dan memperbarui bukti transfer.
- Melihat pesanan dan status pemeriksaan pembayaran.

### Admin dan owner

- Mengelola produk, pesanan, dan pembayaran sesuai hak akses.
- Memeriksa bukti transfer dan memperbarui status pembayaran.
- Melihat ringkasan, laporan, dan informasi operasional yang tersedia di aplikasi.

## Teknologi

- PHP 8.2 atau lebih baru
- Laravel 12
- SQLite sebagai konfigurasi database default lokal (dapat disesuaikan melalui `.env`)
- Vite dan npm untuk membangun aset frontend

## Persyaratan

Pasang perangkat berikut sebelum menjalankan project:

- PHP 8.2+
- Composer
- Node.js dan npm

## Instalasi dan Menjalankan Secara Lokal

1. Clone repository dan masuk ke folder project:

   ```bash
   git clone https://github.com/rizhanuranisa-28/sistem_pree_order_dwiera.git
   cd sistem_pree_order_dwiera
   ```

2. Pasang dependency PHP dan JavaScript:

   ```bash
   composer install
   npm install
   ```

3. Buat file konfigurasi lokal:

   ```bash
   # Windows PowerShell
   Copy-Item .env.example .env

   # macOS atau Linux
   cp .env.example .env
   ```

4. Pastikan file database SQLite tersedia. Jika belum ada, buat file kosong:

   ```bash
   # Windows PowerShell
   New-Item -ItemType File -Path database/database.sqlite

   # macOS atau Linux
   touch database/database.sqlite
   ```

   Konfigurasi default memakai SQLite. Jika menggunakan database lain, sesuaikan pengaturan koneksi di `.env`.

5. Buat application key dan jalankan migrasi:

   ```bash
   php artisan key:generate
   php artisan migrate
   ```

6. Build aset frontend dan jalankan server lokal:

   ```bash
   npm run build
   php artisan serve
   ```

7. Buka alamat lokal yang ditampilkan Artisan, biasanya `http://127.0.0.1:8000`.

## API

Endpoint API yang tersedia:

| Method | Endpoint | Keterangan |
|---|---|---|
| GET | `/api/health` | Pemeriksaan status aplikasi |
| GET | `/api/produk` | Mengambil data produk |
| GET | `/api/pesanan` | Mengambil data pesanan |
| GET | `/api/pembayaran` | Mengambil data pembayaran |

## Pengujian

Jalankan automated test dengan:

```bash
composer test
```

## Informasi Pembayaran

Informasi rekening transfer ditampilkan pada halaman pembayaran dan unggah bukti. Nilainya dikelola di `config/payment.php`. Periksa nilai tersebut sebelum deployment dan jangan memasukkan kredensial rahasia ke repository.

## Deployment

URL dan platform deployment belum dicantumkan di dokumentasi ini. Tambahkan URL aplikasi dan nama platform setelah aplikasi berhasil dipublikasikan.

## Keamanan

- Jangan commit file `.env`.
- Jangan membagikan password, token, API key, atau kredensial database.
- Gunakan konfigurasi environment yang sesuai untuk server production.
