# Sistem Informasi Produk (Product System)

Sistem Informasi Produk adalah aplikasi web sederhana berbasis PHP yang dirancang untuk mengelola data barang/komoditas (seperti produk pakaian) dengan arsitektur modular dan fitur CRUD (Create, Read, Update, Delete) berbasis Session.

## 🛠️ Fitur Utama
- **Read (Tampil Data):** Menampilkan daftar produk lengkap dengan ID, nama, kategori, harga, stok, dan deskripsi.
- **Create (Tambah Data):** Menambahkan data produk baru melalui formulir input.
- **Update (Edit Data):** Mengubah informasi produk yang sudah ada.
- **Delete (Hapus Data):** Menghapus produk dari daftar.
- **Indikator Stok Kritis:** Otomatis memberi warna merah pada baris produk yang stoknya kurang dari 3.
- **Kalkulasi Otomatis:** Menghitung total nilai aset gudang secara otomatis berdasarkan rumus `Harga x Stok`.

---

## 🏗️ Struktur Arsitektur Kode (Modular)
Project ini dibagi menjadi 3 lapisan utama (*3-tier architecture*) untuk menjaga kode tetap rapi dan mudah dirawat:

1. **`products.php` (Data Layer)**
   - Berisi array multidimensi `$products` yang menyimpan data awal produk (pakaian, elektronik, dll).
2. **`functions.php` (Processing Layer / Business Logic)**
   - Berisi fungsi-fungsi pengolahan logika sistem:
     - `session_start()` & `inisialisasiData()` untuk penyimpanan data sementara.
     - `hitungTotalNilaiStok()` untuk kalkulasi aset.
     - `dapatkanWarnaBaris()` untuk penanda stok kritis.
     - Fungsi CRUD (`tambahProduk`, `editProduk`, `hapusProduk`).
3. **`index.php` (Presentation Layer)**
   - Menangani antarmuka pengguna (UI) dengan HTML & CSS.
   - Menggabungkan `products.php` dan `functions.php` menggunakan `require_once`.
   - Menangkap aksi formulir (`POST`) untuk menjalankan perintah CRUD.

---

## 🚀 Cara Menjalankan Project

1. Pastikan aplikasi **XAMPP** sudah terinstal di komputer.
2. Simpan folder project ini di direktori:
   `C:\xampp\htdocs\product-system\`
3. Buka **XAMPP Control Panel** dan klik **Start** pada modul **Apache**.
4. Buka browser (Chrome/Edge) dan akses URL:
   `http://localhost/product-system/`