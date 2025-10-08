<?php
include 'auth.php';
include '../assets/koneksi.php';
$sql = "SELECT * FROM produk ORDER BY nama_produk ASC";
$result = $koneksi->query($sql);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Kelola Produk</title>
        <link rel="shortcut icon" href="../assets/img/favicon.png" type="image/x-icon">

        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/4.6.0/remixicon.min.css">

        <link rel="stylesheet" href="../assets/css/swiper-bundle.min.css">

        <link rel="stylesheet" href="../assets/css/styles.css">

        <link rel="stylesheet" href="admin_style.css">
</head>
<body>
    <?php include 'header.php'; // Menggunakan header terpisah ?>
    <div class="admin-container">
        <div class="card">
            <a href="form_produk.php" class="button">Tambah Produk Baru</a>
            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Produk</th>
                        <th>Harga</th>
                        <th>Stok</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $no = 1; // Inisialisasi nomor
                    while($row = $result->fetch_assoc()): 
                    ?>
                    <tr>
                        <td><?php echo $no++; ?></td> <td><?php echo htmlspecialchars($row['nama_produk']); ?></td>
                        <td>Rp <?php echo number_format($row['harga']); ?></td>
                        <td><?php echo $row['stok']; ?></td>
                        <td>
                            <a href="form_produk.php?id=<?php echo $row['id_produk']; ?>" class="action-btn edit-btn">Edit</a>
                            <a href="proses_produk.php?action=delete&id=<?php echo $row['id_produk']; ?>" onclick="return confirm('Yakin ingin menghapus?')" class="action-btn delete-btn">Hapus</a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>