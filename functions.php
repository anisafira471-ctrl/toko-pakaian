<?php
// functions.php - Processing Layer

session_start();

// Inisialisasi data awal jika session masih kosong
function inisialisasiData() {
    if (!isset($_SESSION['products'])) {
        require_once 'products.php';
        $_SESSION['products'] = $products;
    }
}

// Menghitung total nilai stok
function hitungTotalNilaiStok(array $dataProduk): float 
{
    $totalNilai = 0;
    foreach ($dataProduk as $item) {
        $totalNilai += $item['harga'] * $item['stok'];
    }
    return $totalNilai;
}

// Menentukan warna baris stok kritis
function dapatkanWarnaBaris(int $stok): string 
{
    return ($stok < 3) ? 'stok-kritis' : '';
}

// Fitur [C]reate: Tambah Produk
function tambahProduk($id, $nama, $kategori, $harga, $stok, $deskripsi) {
    $_SESSION['products'][] = [
        'id'        => $id,
        'nama'      => $nama,
        'kategori'  => $kategori,
        'harga'     => (float)$harga,
        'stok'      => (int)$stok,
        'deskripsi' => $deskripsi
    ];
}

// Fitur [D]elete: Hapus Produk
function hapusProduk($index) {
    if (isset($_SESSION['products'][$index])) {
        array_splice($_SESSION['products'], $index, 1);
    }
}

// Fitur [U]pdate: Edit Produk
function editProduk($index, $nama, $kategori, $harga, $stok, $deskripsi) {
    if (isset($_SESSION['products'][$index])) {
        $_SESSION['products'][$index]['nama'] = $nama;
        $_SESSION['products'][$index]['kategori'] = $kategori;
        $_SESSION['products'][$index]['harga'] = (float)$harga;
        $_SESSION['products'][$index]['stok'] = (int)$stok;
        $_SESSION['products'][$index]['deskripsi'] = $deskripsi;
    }
}