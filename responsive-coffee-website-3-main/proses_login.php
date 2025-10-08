<?php
// proses_login.php
session_start();
include 'assets/koneksi.php';

$response = ['success' => false, 'message' => 'Email atau password salah'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    if (!empty($email) && !empty($password)) {
        $sql = "SELECT id_pelanggan, nama, password FROM pelanggan WHERE email = ?";
        $stmt = $koneksi->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($user = $result->fetch_assoc()) {
            if (password_verify($password, $user['password'])) {
                $_SESSION['id_pelanggan'] = $user['id_pelanggan'];
                $_SESSION['nama_pelanggan'] = $user['nama'];
                
                $response['success'] = true;
                $response['message'] = 'Login berhasil!';
            }
        }
    } else {
        $response['message'] = 'Email dan password harus diisi.';
    }
}

header('Content-Type: application/json');
echo json_encode($response);
exit;