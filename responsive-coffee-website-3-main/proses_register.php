<?php
// proses_register.php
include 'assets/koneksi.php';

$response = ['success' => false, 'message' => 'Registrasi gagal, coba lagi.'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = $_POST['nama'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $alamat = $_POST['alamat'] ?? '';
    $no_telpon = $_POST['no_telpon'] ?? '';

    if (empty($nama) || empty($email) || empty($password) || empty($alamat) || empty($no_telpon)) {
        $response['message'] = 'Semua field harus diisi.';
    } else {
        // Cek email
        $sql_check = "SELECT id_pelanggan FROM pelanggan WHERE email = ?";
        $stmt_check = $koneksi->prepare($sql_check);
        $stmt_check->bind_param("s", $email);
        $stmt_check->execute();
        if ($stmt_check->get_result()->num_rows > 0) {
            $response['message'] = 'Email sudah terdaftar.';
        } else {
            // Hash & Insert
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $sql_insert = "INSERT INTO pelanggan (nama, email, password, alamat, no_telpon) VALUES (?, ?, ?, ?, ?)";
            $stmt_insert = $koneksi->prepare($sql_insert);
            $stmt_insert->bind_param("sssss", $nama, $email, $hashed_password, $alamat, $no_telpon);

            if ($stmt_insert->execute()) {
                $response['success'] = true;
                $response['message'] = 'Registrasi berhasil! Silakan login.';
            }
        }
    }
}

header('Content-Type: application/json');
echo json_encode($response);
exit;