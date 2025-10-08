<?php
include 'auth.php';
include '../assets/koneksi.php';
if (!isset($_GET['id'])) { header('Location: kelola_pesanan.php'); exit; }
$id_pesanan = $_GET['id'];
// Ambil info pesanan
$sql_info = "SELECT p.*, pl.nama as nama_pelanggan, pl.alamat, pl.no_telpon FROM pesanan p JOIN pelanggan pl ON p.id_pelanggan = pl.id_pelanggan WHERE p.id_pesanan = ?";
$stmt_info = $koneksi->prepare($sql_info);
$stmt_info->bind_param("i", $id_pesanan);
$stmt_info->execute();
$info_pesanan = $stmt_info->get_result()->fetch_assoc();
// Ambil detail item
$sql_detail = "SELECT dp.*, pr.nama_produk FROM detail_pesanan dp JOIN produk pr ON dp.id_produk = pr.id_produk WHERE dp.id_pesanan = ?";
$stmt_detail = $koneksi->prepare($sql_detail);
$stmt_detail->bind_param("i", $id_pesanan);
$stmt_detail->execute();
$detail_items = $stmt_detail->get_result();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Detail Pesanan #<?php echo $id_pesanan; ?></title>
    <link rel="stylesheet" href="../assets/css/styles.css">
    <link rel="stylesheet" href="admin_style.css">
</head>
<body>
    <?php include 'header.php'; ?>
    <div class="admin-container">
        <a href="kelola_pesanan.php" class="button">&larr; Kembali</a>
        <div class="card" style="display: grid; grid-template-columns: 2fr 1fr; gap: 2rem;">
            <div>
                <h3>Item Dipesan</h3>
                <table>
                   <thead><tr><th>Produk</th><th>Jumlah</th><th>Subtotal</th></tr></thead>
                   <tbody>
                       <?php while($item = $detail_items->fetch_assoc()): ?>
                       <tr><td><?php echo htmlspecialchars($item['nama_produk']); ?></td><td><?php echo $item['jumlah']; ?></td><td>Rp <?php echo number_format($item['subtotal']); ?></td></tr>
                       <?php endwhile; ?>
                   </tbody>
                </table>
            </div>
            <div>
                <h3>Info Pelanggan & Status</h3>
                <p><strong>Nama:</strong> <?php echo htmlspecialchars($info_pesanan['nama_pelanggan']); ?></p>
                <p><strong>Total Bayar:</strong> Rp <?php echo number_format($info_pesanan['total_harga']); ?></p>
                <hr style="margin: 1rem 0;">
                <form action="proses_pesanan.php" method="POST">
                    <input type="hidden" name="id_pesanan" value="<?php echo $id_pesanan; ?>">
                    <div class="form-group">
                        <label><strong>Ubah Status Pesanan</strong></label>
                        <select name="status">
                            <option value="Baru" <?php if($info_pesanan['status'] == 'Baru') echo 'selected'; ?>>Baru</option>
                            <option value="Diproses" <?php if($info_pesanan['status'] == 'Diproses') echo 'selected'; ?>>Diproses</option>
                            <option value="Selesai" <?php if($info_pesanan['status'] == 'Selesai') echo 'selected'; ?>>Selesai</option>
                            <option value="Dibatalkan" <?php if($info_pesanan['status'] == 'Dibatalkan') echo 'selected'; ?>>Dibatalkan</option>
                        </select>
                    </div>
                    <button type="submit" class="button">Update Status</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>