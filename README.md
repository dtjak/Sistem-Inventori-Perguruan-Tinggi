# Sistem Inventori Perguruan Tinggi

Sistem Informasi Manajemen Inventori (SIM-Inventori) berbasis web yang dirancang khusus untuk memfasilitasi kebutuhan pengelolaan barang habis pakai, aset/peralatan, pengajuan barang, pemesanan, penerimaan, retur barang, hingga pencocokan stok fisik (stock opname) di lingkungan Perguruan Tinggi.

## 🚀 Fitur Utama

### 1. Dashboard Interaktif
- Ringkasan statistik cepat (total barang, aset, supplier, pengajuan aktif).
- Grafik/tren aktivitas inventori.
- Panel notifikasi dinamis untuk alur persetujuan (approval workflow).

### 2. Katalog Barang & Aset Mandiri
- Halaman katalog publik yang memisahkan Barang Habis Pakai dengan Aset Peralatan.
- Filter pencarian cepat untuk memudahkan pencarian barang sebelum diajukan.

### 3. Manajemen Master Data
- **Master Barang**: Kelola inventori barang habis pakai (ATK, Elektronik, Kebersihan, dll) dilengkapi dengan pengaturan stok minimum, lokasi gudang, serta input kategori/satuan dinamis menggunakan SweetAlert2.
- **Master Aset**: Kelola aset/peralatan kampus (Furniture, Komputer, Proyektor) beserta status kondisinya.
- **Master Supplier**: Daftar pemasok barang yang aktif bekerja sama dengan institusi.

### 4. Siklus Transaksi Lengkap
- **Store Requisition (SR)**: Pengajuan permintaan barang habis pakai atau peminjaman aset oleh Staff Unit.
- **Delivery Requisition (DR)**: Validasi dan pengiriman barang dari gudang logistik berdasarkan dokumen SR yang telah disetujui.
- **Purchase Requisition (PR)**: Pengajuan pengadaan/pembelian barang baru oleh Unit Kerja ke Bagian Logistik.
- **Purchase Order (PO)**: Penerbitan dokumen pemesanan resmi kepada Supplier terpilih.
- **Receiving Report (RR)**: Pencatatan penerimaan barang yang dikirim oleh Supplier beserta pengecekan kesesuaian jumlah dan kualitas barang.
- **Retur Barang**: Pengembalian barang yang rusak atau tidak sesuai kembali ke Supplier dengan pencatatan nomor resi dan pengiriman barang pengganti.
- **Stock Opname (OPN)**: Proses pencocokan stok fisik di gudang dengan stok sistem secara berkala dengan fitur penyesuaian otomatis (adjustment) dan dialog konfirmasi SweetAlert2.

---

## 👥 Hak Akses (Aktor)
- **Staff Inventori (Gudang)**: Mengelola keluar masuk barang, memproses pengiriman, mencatat penerimaan, melakukan retur, dan melakukan stock opname.
- **Staff Unit / Peminjam**: Mengajukan permintaan barang/peminjaman aset (Store Requisition) dan pengajuan pembelian (Purchase Requisition).
- **Supplier**: Menerima Purchase Order, mengirimkan informasi pengiriman (resi/kurir), serta memproses retur barang jika ada penggantian.
- **Pimpinan / Logistik**: Memberikan persetujuan (approval) terhadap pengajuan pengadaan, pembelian, atau pengeluaran aset bernilai tinggi.

---

## 🛠️ Tech Stack
- **Framework**: Laravel 10 / PHP 8.x
- **Frontend UI**: Blade Templates, Bootstrap 5, Vanilla CSS
- **Interactive Elements**: SweetAlert2 (untuk notifikasi, konfirmasi aksi, dan form dinamis)
- **Database**: MySQL / MariaDB
- **Ekspor Dokumen**: Barryvdh DomPDF (Cetak Surat Jalan, Purchase Order, Retur, & Opname ke PDF)

---

## 💻 Panduan Instalasi Lokal

1. **Clone Repositori**:
   ```bash
   git clone https://github.com/dtjak/Sistem-Inventori-Perguruan-Tinggi.git
   cd web_inventori
   ```

2. **Install Dependensi PHP & Javascript**:
   ```bash
   composer install
   npm install
   ```

3. **Konfigurasi Environment**:
   Duplikat file `.env.example` menjadi `.env`
   ```bash
   cp .env.example .env
   ```
   Atur koneksi database Anda pada bagian berikut di `.env`:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=nama_database_anda
   DB_USERNAME=root
   DB_PASSWORD=
   ```

4. **Generate Application Key**:
   ```bash
   php artisan key:generate
   ```

5. **Migrasi Database & Seeder**:
   ```bash
   php artisan migrate --seed
   ```

6. **Jalankan Aplikasi**:
   Jalankan server lokal Laravel dan build asset Vite:
   ```bash
   php artisan serve
   ```
   Aplikasi dapat diakses di browser melalui link `http://127.0.0.1:8000`.

test error