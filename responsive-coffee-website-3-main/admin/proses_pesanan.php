<?php
include 'auth.php';
include '../assets/koneksi.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_pesanan = $_POST['id_pesanan'];
    $status = $_POST['status'];
    $sql = "UPDATE pesanan SET status = ? WHERE id_pesanan = ?";
    $stmt = $koneksi->prepare($sql);
    $stmt->bind_param("si", $status, $id_pesanan);
    if ($stmt->execute()) {
        header('Location: detail_pesanan.php?id=' . $id_pesanan);
    } else {
        echo "Gagal mengupdate status.";
    }
}
?>