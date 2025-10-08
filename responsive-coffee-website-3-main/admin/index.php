<?php 
include 'auth.php';
include '../assets/koneksi.php'; // Tambahkan koneksi ke database

// Tentukan batas stok rendah, misalnya 10
$batas_stok_rendah = 10;

// Kueri untuk mengambil produk dengan stok rendah
$sql_stok = "SELECT nama_produk, stok FROM produk WHERE stok <= ? ORDER BY stok ASC";
$stmt_stok = $koneksi->prepare($sql_stok);
$stmt_stok->bind_param("i", $batas_stok_rendah);
$stmt_stok->execute();
$produk_stok_rendah = $stmt_stok->get_result();

// Kueri untuk menghitung pesanan hari ini
$tanggal_hari_ini = date('Y-m-d');
$sql_pesanan = "SELECT COUNT(id_pesanan) as jumlah, SUM(total_harga) as total 
                FROM pesanan 
                WHERE DATE(tanggal_pesanan) = ?";
$stmt_pesanan = $koneksi->prepare($sql_pesanan);
$stmt_pesanan->bind_param("s", $tanggal_hari_ini);
$stmt_pesanan->execute();
$info_pesanan_hari_ini = $stmt_pesanan->get_result()->fetch_assoc();

?>
<!DOCTYPE html>
<html>
<head>
    <title>Dashboard Admin</title>
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
    <style>
        .dashboard-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 1.5rem;
        }
        .kpi-card {
            background-color: var(--dark-color);
            color: var(--white-color);
            padding: 1.5rem;
            border-radius: 8px;
        }
        .kpi-card h3 {
            font-size: var(--h3-font-size);
            margin: 0 0 0.5rem 0;
            color: #ccc;
        }
        .kpi-card .value {
            font-size: var(--big-font-size);
            font-weight: var(--font-semi-bold);
            font-family: var(--second-font);
        }
        .low-stock-list li {
            display: flex;
            justify-content: space-between;
            padding: 0.5rem 0;
            border-bottom: 1px solid #eee;
        }
        .low-stock-list span {
            font-weight: var(--font-semi-bold);
            color: #e74c3c;
        }
    </style>
</head>
<body>
    <?php include 'header.php'; ?>
    <div class="admin-container">
        <div class="dashboard-grid">
            <div class="kpi-card">
                <h3>Pesanan Hari Ini</h3>
                <p class="value"><?php echo $info_pesanan_hari_ini['jumlah'] ?? 0; ?></p>
            </div>
            <div class="kpi-card">
                <h3>Pendapatan Hari Ini</h3>
                <p class="value">Rp <?php echo number_format($info_pesanan_hari_ini['total'] ?? 0); ?></p>
            </div>
        </div>

        <div class="card">
            <h3><i class="ri-error-warning-line"></i> Produk dengan Stok Menipis (<= <?php echo $batas_stok_rendah; ?>)</h3>
            <?php if ($produk_stok_rendah->num_rows > 0): ?>
                <ul class="low-stock-list">
                    <?php while($produk = $produk_stok_rendah->fetch_assoc()): ?>
                        <li>
                            <?php echo htmlspecialchars($produk['nama_produk']); ?>
                            <span>Stok: <?php echo $produk['stok']; ?></span>
                        </li>
                    <?php endwhile; ?>
                </ul>
            <?php else: ?>
                <p>👍 Semua stok produk dalam kondisi aman.</p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>