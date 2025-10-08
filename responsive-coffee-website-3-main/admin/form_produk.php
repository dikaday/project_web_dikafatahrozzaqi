<?php
include 'auth.php';
include '../assets/koneksi.php';

// Inisialisasi variabel
$produk = ['id_produk' => '', 'nama_produk' => '', 'harga' => '', 'stok' => '', 'gambar' => ''];
$form_action = 'proses_produk.php?action=add';
$page_title = 'Tambah Produk Baru';

// Jika ini adalah mode edit, ambil data dari DB
if (isset($_GET['id'])) {
    $id_produk = $_GET['id'];
    $sql = "SELECT * FROM produk WHERE id_produk = ?";
    $stmt = $koneksi->prepare($sql);
    $stmt->bind_param("s", $id_produk);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $produk = $result->fetch_assoc();
        $form_action = 'proses_produk.php?action=edit&id=' . $id_produk;
        $page_title = 'Edit Produk';
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title><?php echo $page_title; ?></title>
    <link rel="stylesheet" href="../assets/css/styles.css">
    <link rel="stylesheet" href="admin_style.css">
</head>
<body>
    <?php include 'header.php'; ?>
    <div class="admin-container">
        <div class="card">
            <h2><?php echo $page_title; ?></h2>
            <form action="<?php echo $form_action; ?>" method="POST">
                <div class="form-group">
                    <label>Nama Produk</label>
                    <input type="text" name="nama_produk" value="<?php echo htmlspecialchars($produk['nama_produk']); ?>" required>
                </div>
                <div class="form-group">
                    <label>Harga</label>
                    <input type="number" name="harga" value="<?php echo htmlspecialchars($produk['harga']); ?>" required>
                </div>
                 <div class="form-group">
                    <label>Stok</label>
                    <input type="number" name="stok" value="<?php echo htmlspecialchars($produk['stok']); ?>" required>
                </div>
                <div class="form-group">
                    <label>Path Gambar (Contoh: `assets/img/popular-coffee-1`)</label>
                    <input type="text" name="gambar" value="<?php echo htmlspecialchars($produk['gambar']); ?>" required>
                    <small>Nama file gambar di folder `assets/img/` tanpa ekstensi `.png`</small>
                </div>
                <button type="submit" class="button">Simpan</button>
            </form>
        </div>
    </div>
</body>
</html>