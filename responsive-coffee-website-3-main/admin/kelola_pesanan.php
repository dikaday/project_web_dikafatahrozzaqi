<?php
include 'auth.php';
include '../assets/koneksi.php';
$sql = "SELECT p.*, pl.nama as nama_pelanggan FROM pesanan p JOIN pelanggan pl ON p.id_pelanggan = pl.id_pelanggan ORDER BY p.tanggal_pesanan DESC";
$result = $koneksi->query($sql);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Kelola Pesanan</title>
        <!--=============== FAVICON ===============-->
        <link rel="shortcut icon" href="../assets/img/favicon.png" type="image/x-icon">

        <!--=============== REMIXICONS ===============-->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/4.6.0/remixicon.min.css">

        <!--=============== SWIPER CSS ===============-->
        <link rel="stylesheet" href="../assets/css/swiper-bundle.min.css">

        <!--=============== CSS ===============-->
        <link rel="stylesheet" href="../assets/css/styles.css">

        <!-- ============== admin CSS ========== -->
        <link rel="stylesheet" href="admin_style.css">
</head>
<body>
    <?php include 'header.php'; ?>
    <div class="admin-container">
        <div class="card">
            <table>
                <thead><tr><th>ID Pesanan</th><th>Pelanggan</th><th>Tanggal</th><th>Total</th><th>Status</th><th>Aksi</th></tr></thead>
                <tbody>
                    <?php while($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td>#<?php echo $row['id_pesanan']; ?></td>
                        <td><?php echo htmlspecialchars($row['nama_pelanggan']); ?></td>
                        <td><?php echo date('d M Y H:i', strtotime($row['tanggal_pesanan'])); ?></td>
                        <td>Rp <?php echo number_format($row['total_harga']); ?></td>
                        <td><?php echo htmlspecialchars($row['status']); ?></td>
                        <td><a href="detail_pesanan.php?id=<?php echo $row['id_pesanan']; ?>" class="action-btn view-btn">Detail</a></td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>