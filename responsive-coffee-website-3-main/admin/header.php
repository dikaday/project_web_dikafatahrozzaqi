<header class="admin-header">
    <h1><i class="ri-cup-line"></i> STARCOFFEE Admin</h1>
    <nav class="admin-nav">
        <span>Halo, <?php echo htmlspecialchars($_SESSION['nama_karyawan']); ?>!</span>
        <a href="index.php">Dashboard</a>
        <a href="kelola_produk.php">Kelola Produk</a>
        <a href="kelola_pesanan.php">Kelola Pesanan</a>
        <a href="logout.php">Logout</a>
    </nav>
</header>