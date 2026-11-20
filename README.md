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
- Mengajukan desain kue custom, mengirim foto referensi, dan menyetujui penawaran harga admin.
- Melanjutkan pesanan custom yang disetujui ke checkout, jadwal pengambilan, dan pembayaran yang sama dengan pesanan katalog.
- Memilih tanggal dan jam pengambilan.
- Memilih pembayaran cash, transfer, atau QRIS.
- Mengunggah atau mengirim ulang bukti transfer/QRIS setelah dikembalikan admin; bukti sebelumnya tetap tersimpan.
- Melihat notifikasi penawaran custom dan bukti pembayaran yang dikembalikan.
- Mengubah pesanan reguler hanya sebelum admin mulai memprosesnya.

### Admin dan owner

- Mengelola produk, pesanan, dan pembayaran sesuai hak akses.
- Memeriksa permintaan kue custom, menentukan biaya dekorasi, mengirim penawaran harga, atau menolak dengan alasan.
- Melihat jumlah pesanan, permintaan custom, dan bukti pembayaran yang belum diperiksa; indikator hilang setelah status ditangani.
- Memeriksa riwayat bukti transfer/QRIS lama dan terbaru, lalu menerima atau mengembalikan bukti dengan alasan.
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

   Migrasi `2026_11_20_000001_create_custom_cake_orders_table` menambahkan tabel permintaan custom, sedangkan `2026_11_20_000002_create_custom_cake_base_prices_table` menyimpan harga dasar untuk tiap ukuran. Admin dapat mengubah harga, menambahkan ukuran baru (1 sampai 500 cm), atau menghapus ukuran dari halaman Permintaan Custom. Perubahan berlaku untuk permintaan baru; permintaan lama tetap memakai harga saat diajukan. Foto referensi disimpan pada disk privat `local`, jadi tidak perlu membuat symlink `storage:link`.

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
