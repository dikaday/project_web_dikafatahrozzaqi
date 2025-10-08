<?php
include 'auth.php';
include '../assets/koneksi.php';

$action = $_GET['action'] ?? '';

// Proses Tambah
if ($action == 'add' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    // Variabel id_produk manual dihapus
    $nama = $_POST['nama_produk'];
    $harga = $_POST['harga'];
    $stok = $_POST['stok'];
    $gambar = $_POST['gambar'];
    
    $sql = "INSERT INTO produk (nama_produk, harga, stok, gambar) VALUES (?, ?, ?, ?)";
    $stmt = $koneksi->prepare($sql);
    // Tambahkan baris ini! Tipe data: s=string, d=double(harga), i=integer(stok)
    $stmt->bind_param("sdis", $nama, $harga, $stok, $gambar);
    $stmt->execute();
    header('Location: kelola_produk.php');
}

// Proses Edit
if ($action == 'edit' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ambil id dari POST (hidden input), bukan GET agar lebih aman
    $id_produk = $_POST['id_produk']; 
    $nama = $_POST['nama_produk'];
    $harga = $_POST['harga'];
    $stok = $_POST['stok'];
    $gambar = $_POST['gambar'];
    
    $sql = "UPDATE produk SET nama_produk=?, harga=?, stok=?, gambar=? WHERE id_produk=?";
    $stmt = $koneksi->prepare($sql);
    // Perbaiki tipe datanya di sini
    $stmt->bind_param("sdisi", $nama, $harga, $stok, $gambar, $id_produk);
    $stmt->execute();
    header('Location: kelola_produk.php');
}

// Proses Hapus
// Proses Hapus
if ($action == 'delete') {
    $id_produk = $_GET['id'];
    $sql = "DELETE FROM produk WHERE id_produk = ?";
    $stmt = $koneksi->prepare($sql);
    // Perbaiki tipe datanya di sini
    $stmt->bind_param("i", $id_produk);
    $stmt->execute();
    header('Location: kelola_produk.php');
}
?>