<?php
// riwayat.php
session_start();
include 'assets/koneksi.php';

// Jika belum login, redirect ke halaman login
if (!isset($_SESSION['id_pelanggan'])) {
    header('Location: login.php');
    exit;
}

$id_pelanggan = $_SESSION['id_pelanggan'];

// Ambil data pesanan milik pelanggan yang login
$sql_pesanan = "SELECT * FROM pesanan WHERE id_pelanggan = ? ORDER BY tanggal_pesanan DESC";
$stmt_pesanan = $koneksi->prepare($sql_pesanan);
$stmt_pesanan->bind_param("i", $id_pelanggan);
$stmt_pesanan->execute();
$daftar_pesanan = $stmt_pesanan->get_result();

$detail_pesanan = null;
$info_pesanan = null;

// Jika ada parameter id_pesanan di URL, tampilkan detailnya
if (isset($_GET['id_pesanan'])) {
    $id_pesanan_detail = $_GET['id_pesanan'];

    // Ambil info pesanan (pastikan pesanan ini milik user yg login)
    $sql_info = "SELECT * FROM pesanan WHERE id_pesanan = ? AND id_pelanggan = ?";
    $stmt_info = $koneksi->prepare($sql_info);
    $stmt_info->bind_param("ii", $id_pesanan_detail, $id_pelanggan);
    $stmt_info->execute();
    $info_pesanan = $stmt_info->get_result()->fetch_assoc();

    if ($info_pesanan) {
        // Ambil item-item dalam detail pesanan
        $sql_detail = "SELECT dp.*, p.nama_produk, p.gambar, p.harga FROM detail_pesanan dp JOIN produk p ON dp.id_produk = p.id_produk WHERE dp.id_pesanan = ?";
        $stmt_detail = $koneksi->prepare($sql_detail);
        $stmt_detail->bind_param("i", $id_pesanan_detail);
        $stmt_detail->execute();
        $detail_pesanan = $stmt_detail->get_result();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <!--=============== FAVICON ===============-->
      <link rel="shortcut icon" href="assets/img/favicon.png" type="image/x-icon">

      <!--=============== REMIXICONS ===============-->
      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/4.6.0/remixicon.min.css">

      <!--=============== SWIPER CSS ===============-->
      <link rel="stylesheet" href="assets/css/swiper-bundle.min.css">

      <!--=============== CSS ===============-->
      <link rel="stylesheet" href="assets/css/styles.css">

      <title> Riwayat - Responsive coffee website</title>
    <style>
        .history-section {
            background-color: var(--body-color); /* DIUBAH: Latar belakang utama menjadi hijau tua */
            padding: 7rem 1.5rem 4rem;
            min-height: 100vh;
        }
        .history-container {
            max-width: 1120px;
            margin: auto;
            /* DIHAPUS: Latar belakang putih dan bayangan dihapus */
        }
        .history-title {
            color: var(--white-color); /* DIUBAH: Warna judul menjadi putih */
            text-align: center;
            margin-bottom: 2rem;
            font-size: var(--big-font-size);
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 2rem;
            background-color: hsla(166, 56%, 15%, 0.5); /* Latar belakang tabel sedikit transparan */
            border-radius: 8px;
            overflow: hidden; /* Agar border-radius terlihat di tabel */
        }
        th, td {
            padding: 1rem;
            text-align: left;
            border-bottom: 1px solid hsla(166, 12%, 80%, 0.2); /* DIUBAH: Warna border disesuaikan */
            color: var(--white-color); /* DIUBAH: Warna teks tabel menjadi putih */
        }
        th {
            background-color: var(--first-color-alt);
            color: var(--white-color);
            font-family: var(--second-font);
        }
        tr:hover {
            background-color: hsla(166, 56%, 25%, 0.5); /* Efek hover lebih gelap */
        }
        .detail-link {
            color: var(--first-color);
            font-weight: var(--font-medium);
            text-decoration: none;
            transition: .3s;
        }
        .detail-link:hover {
            color: var(--white-color);
        }
        .back-link {
            display: inline-block;
            margin-bottom: 2rem;
            color: var(--first-color);
            text-decoration: none;
            font-weight: var(--font-medium);
        }
        .summary-box {
            text-align: right; 
            color: var(--white-color); /* DIUBAH: Warna teks menjadi putih */
            margin-top: 1.5rem;
            padding: 1.5rem;
            border-top: 2px solid var(--first-color);
            background-color: hsla(166, 56%, 15%, 0.5);
            border-radius: 8px;
        }
        .summary-box h3 {
            margin-top:1rem;
        }
        .summary-box p {
            margin: 0.5rem 0;
        }
    </style>
</head>
<body>
    <a href="index.php" style="position:fixed; top:20px; left:20px; z-index:100;" class="button">
         <i class="ri-arrow-left-line"></i> Home
    </a>
    <section class="history-section">
        <div class="history-container">
            <h2 class="history-title">
                <?php echo ($detail_pesanan) ? 'Detail Pesanan' : 'Your Order History'; ?>
            </h2>
            
            <?php if ($detail_pesanan && $info_pesanan): ?>
                <a href="riwayat.php" class="back-link">&larr;  Back to Order List</a>
                <h3 style="color:var(--white-color); margin-bottom:1rem;">Order #<?php echo $info_pesanan['id_pesanan']; ?></h3>
                <table>
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Quantity</th>
                            <th>Unit Price</th>
                            <th>Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $total_sebelum_diskon = 0;
                        while($item = $detail_pesanan->fetch_assoc()): 
                            $total_sebelum_diskon += $item['subtotal'];
                        ?>
                        <tr>
                            <td><?php echo htmlspecialchars($item['nama_produk']); ?></td>
                            <td><?php echo $item['jumlah']; ?></td>
                            <td>Rp <?php echo number_format($item['harga']); ?></td>
                            <td>Rp <?php echo number_format($item['subtotal']); ?></td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
                <div class="summary-box">
                    <p>Subtotal: Rp <?php echo number_format($total_sebelum_diskon); ?></p>
                    <p style="color: var(--first-color);">Discount: - Rp <?php echo number_format($info_pesanan['diskon']); ?></p>
                    <h3>Grand Total: Rp <?php echo number_format($info_pesanan['total_harga']); ?></h3>
                </div>

            <?php else: ?>
                <?php if ($daftar_pesanan->num_rows > 0): ?>
                <table>
                    <thead>
                        <tr>
                            <th>Order ID</th>
                            <th>Date</th>
                            <th>Total</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($pesanan = $daftar_pesanan->fetch_assoc()): ?>
                        <tr>
                            <td>#<?php echo $pesanan['id_pesanan']; ?></td>
                            <td><?php echo date('d M Y, H:i', strtotime($pesanan['tanggal_pesanan'])); ?></td>
                            <td>Rp <?php echo number_format($pesanan['total_harga']); ?></td>
                            <td>
                                <a href="riwayat.php?id_pesanan=<?php echo $pesanan['id_pesanan']; ?>" class="detail-link">View Details</a>
                                <a href="struk.php?id_pesanan=<?php echo $pesanan['id_pesanan']; ?>" class="detail-link" style="margin-left: 1rem;">Print Receipt</a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
                <?php else: ?>
                    <p style="text-align: center; color: var(--white-color);"> You have no order history yet.</p>
                <?php endif; ?>
            <?php endif; ?>

        </div>
    </section>
</body>
</html>