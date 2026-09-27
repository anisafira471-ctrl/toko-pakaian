<?php
// index.php - Presentation Layer (CRUD UI)

require_once 'functions.php';
inisialisasiData();

// Proses Aksi Form (Tambah, Edit, Hapus)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        if ($_POST['action'] === 'add') {
            tambahProduk($_POST['id'], $_POST['nama'], $_POST['kategori'], $_POST['harga'], $_POST['stok'], $_POST['deskripsi']);
        } elseif ($_POST['action'] === 'delete') {
            hapusProduk($_POST['index']);
        } elseif ($_POST['action'] === 'edit') {
            editProduk($_POST['index'], $_POST['nama'], $_POST['kategori'], $_POST['harga'], $_POST['stok'], $_POST['deskripsi']);
        }
        header("Location: index.php");
        exit;
    }
}

$products = $_SESSION['products'];
$totalAset = hitungTotalNilaiStok($products);

// Mode Edit Data
$editMode = false;
$editIndex = null;
$editData = null;
if (isset($_GET['edit'])) {
    $editMode = true;
    $editIndex = $_GET['edit'];
    $editData = $products[$editIndex] ?? null;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Informasi Produk (CRUD)</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; background-color: #f4f6f9; }
        h2 { color: #333; }
        .card { background: white; padding: 20px; border-radius: 8px; margin-bottom: 20px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        form input, form textarea { width: 100%; padding: 8px; margin: 5px 0 15px; box-sizing: border-box; }
        .form-group { display: flex; gap: 10px; }
        .form-group div { flex: 1; }
        button { padding: 10px 15px; border: none; cursor: pointer; border-radius: 4px; font-weight: bold; }
        .btn-add { background: #28a745; color: white; }
        .btn-update { background: #ffc107; color: black; }
        .btn-delete { background: #dc3545; color: white; padding: 5px 10px; text-decoration: none; border-radius: 3px; font-size: 0.8em; }
        .btn-edit { background: #17a2b8; color: white; padding: 5px 10px; text-decoration: none; border-radius: 3px; font-size: 0.8em; }
        table { width: 100%; border-collapse: collapse; background-color: #fff; }
        th, td { border: 1px solid #ddd; padding: 10px; text-align: left; }
        th { background-color: #007bff; color: white; }
        .stok-kritis { background-color: #f8d7da; color: #721c24; font-weight: bold; }
        .total-box { padding: 15px; background-color: #e2e3e5; border-radius: 5px; font-size: 1.1em; margin-top: 15px; }
    </style>
</head>
<body>

    <h2>System Management Produk (CRUD)</h2>

    <!-- Form Tambah / Edit Produk -->
    <div class="card">
        <h3><?= $editMode ? 'Edit Produk' : 'Tambah Produk Baru'; ?></h3>
        <form method="POST" action="index.php">
            <input type="hidden" name="action" value="<?= $editMode ? 'edit' : 'add'; ?>">
            <?php if ($editMode): ?>
                <input type="hidden" name="index" value="<?= $editIndex; ?>">
            <?php endif; ?>

            <div class="form-group">
                <div>
                    <label>ID Produk:</label>
                    <input type="text" name="id" value="<?= $editData['id'] ?? ''; ?>" <?= $editMode ? 'readonly' : 'required'; ?>>
                </div>
                <div>
                    <label>Nama Produk:</label>
                    <input type="text" name="nama" value="<?= $editData['nama'] ?? ''; ?>" required>
                </div>
            </div>

            <div class="form-group">
                <div>
                    <label>Kategori:</label>
                    <input type="text" name="kategori" value="<?= $editData['kategori'] ?? ''; ?>" required>
                </div>
                <div>
                    <label>Harga (Rp):</label>
                    <input type="number" name="harga" value="<?= $editData['harga'] ?? ''; ?>" required>
                </div>
                <div>
                    <label>Stok:</label>
                    <input type="number" name="stok" value="<?= $editData['stok'] ?? ''; ?>" required>
                </div>
            </div>

            <label>Deskripsi:</label>
            <textarea name="deskripsi" rows="2" required><?= $editData['deskripsi'] ?? ''; ?></textarea>

            <button type="submit" class="<?= $editMode ? 'btn-update' : 'btn-add'; ?>">
                <?= $editMode ? 'Simpan Perubahan' : 'Tambah Produk'; ?>
            </button>
            <?php if ($editMode): ?>
                <a href="index.php" style="margin-left: 10px; color: #666;">Batal</a>
            <?php endif; ?>
        </form>
    </div>

    <!-- Tabel Data Produk -->
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nama Produk</th>
                <th>Kategori</th>
                <th>Harga</th>
                <th>Stok</th>
                <th>Deskripsi</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($products as $index => $item): ?>
                <?php $rowClass = dapatkanWarnaBaris($item['stok']); ?>
                <tr class="<?= $rowClass; ?>">
                    <td><?= $item['id']; ?></td>
                    <td><?= $item['nama']; ?></td>
                    <td><?= $item['kategori']; ?></td>
                    <td>Rp <?= number_format($item['harga'], 0, ',', '.'); ?></td>
                    <td><?= $item['stok']; ?> <?= ($item['stok'] < 3) ? '(Kritis!)' : ''; ?></td>
                    <td><?= $item['deskripsi']; ?></td>
                    <td>
                        <a href="index.php?edit=<?= $index; ?>" class="btn-edit">Edit</a>
                        <form method="POST" action="index.php" style="display:inline;" onsubmit="return confirm('Yakin ingin menghapus produk ini?');">
                            <input type="hidden" name="action" value="delete">
                            <input type="hidden" name="index" value="<?= $index; ?>">
                            <button type="submit" class="btn-delete">Hapus</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <div class="total-box">
        <strong>Total Nilai Aset Gudang:</strong> Rp <?= number_format($totalAset, 0, ',', '.'); ?>
    </div>

</body>
</html>