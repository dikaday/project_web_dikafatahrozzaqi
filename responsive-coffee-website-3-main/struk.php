<?php
session_start();
include 'assets/koneksi.php';

if (!isset($_GET['id_pesanan']) || !is_numeric($_GET['id_pesanan'])) {
    echo "Halaman tidak ditemukan.";
    exit;
}
$id_pesanan = $_GET['id_pesanan'];

$sql_pesanan = "SELECT p.*, pl.nama as nama_akun 
                FROM pesanan p 
                JOIN pelanggan pl ON p.id_pelanggan = pl.id_pelanggan 
                WHERE p.id_pesanan = ?";
$stmt_pesanan = $koneksi->prepare($sql_pesanan);
$stmt_pesanan->bind_param("i", $id_pesanan);
$stmt_pesanan->execute();
$info_pesanan = $stmt_pesanan->get_result()->fetch_assoc();

if (!$info_pesanan) {
    echo "Pesanan tidak ditemukan.";
    exit;
}

$nama_display = !empty($info_pesanan['nama_pelanggan']) ? $info_pesanan['nama_pelanggan'] : $info_pesanan['nama_akun'];

$sql_items = "SELECT dp.jumlah, dp.subtotal, pr.nama_produk, pr.harga 
              FROM detail_pesanan dp 
              JOIN produk pr ON dp.id_produk = pr.id_produk 
              WHERE dp.id_pesanan = ?";
$stmt_items = $koneksi->prepare($sql_items);
$stmt_items->bind_param("i", $id_pesanan);
$stmt_items->execute();
$items_pesanan = $stmt_items->get_result();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Order Receipt #<?php echo $info_pesanan['id_pesanan']; ?></title>
    <link rel="stylesheet" href="assets/css/styles.css">
    </head>
<body class="struk-body">

    <div class="struk-container">
        <div class="struk-header">
            <h2>STARCOFFEE</h2>
            <p>Thank you for your order!</p>
        </div>

        <div class="struk-info">
            <p><strong>Order ID:</strong> #<?php echo $info_pesanan['id_pesanan']; ?></p>
            <p><strong>Date:</strong> <?php echo date('d M Y, H:i', strtotime($info_pesanan['tanggal_pesanan'])); ?></p>
            <p><strong>Customer:</strong> <?php echo htmlspecialchars($nama_display); ?></p>
        </div>

        <hr class="struk-divider">

        <div class="struk-items">
            <?php 
            $total_belanja = 0;
            while($item = $items_pesanan->fetch_assoc()): 
                $total_belanja += $item['subtotal'];
            ?>
            <div class="item">
                <div class="item-name"><?php echo htmlspecialchars($item['nama_produk']); ?></div>
                <div class="item-detail">
                    <?php echo $item['jumlah']; ?> x Rp <?php echo number_format($item['harga']); ?>
                </div>
                <div class="item-subtotal">Rp <?php echo number_format($item['subtotal']); ?></div>
            </div>
            <?php endwhile; ?>
        </div>

        <hr class="struk-divider">

        <div class="struk-summary">
            <div class="summary-line">
                <span>Subtotal</span>
                <span>Rp <?php echo number_format($total_belanja); ?></span>
            </div>
             <?php if ($info_pesanan['diskon'] > 0): ?>
            <div class="summary-line">
                <span>Discount</span>
                <span>- Rp <?php echo number_format($info_pesanan['diskon']); ?></span>
            </div>
            <?php endif; ?>
            <div class="summary-line total">
                <strong><span>Grand Total</span></strong>
                <strong><span>Rp <?php echo number_format($info_pesanan['total_harga']); ?></span></strong>
            </div>
        </div>
         <div class="struk-footer">
            <p>Keep this receipt as proof of payment.</p>
        </div>
    </div>

    <div class="no-print">
        <button onclick="window.print()" class="button">Print Receipt</button>
        <a href="index.php" class="button button-dark">Back to Shop</a>
    </div>

</body>
</html>