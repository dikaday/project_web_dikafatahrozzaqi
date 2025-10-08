<?php
session_start();
// Path koneksi butuh '..' karena sekarang kita ada di dalam folder admin
include '../assets/koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $sql = "SELECT id_karyawan, nama_karyawan, password FROM karyawan WHERE username = ?";
    $stmt = $koneksi->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($user = $result->fetch_assoc()) {
        // Verifikasi password yang di-hash
        if (password_verify($password, $user['password'])) {
            $_SESSION['id_karyawan'] = $user['id_karyawan'];
            $_SESSION['nama_karyawan'] = $user['nama_karyawan'];
            header('Location: index.php'); // Arahkan ke dashboard admin
            exit;
        }
    }
    // Jika username tidak ditemukan atau password salah
    header('Location: login.php?error=1');
}
?>