<?php
// checkout.php
session_start();
include 'assets/koneksi.php';

function getSessionId() {
    if (!isset($_SESSION['cart_session_id'])) {
        return null;
    }
    return $_SESSION['cart_session_id'];
}

$response = ['success' => false, 'message' => 'An unknown error occurred.'];
$id_sesi = getSessionId();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $id_sesi) {
    
    $koneksi->begin_transaction();

    try {
        $sql_get_cart = "SELECT k.id_produk, k.jumlah, p.nama_produk, p.harga, p.stok FROM keranjang k JOIN produk p ON k.id_produk = p.id_produk WHERE k.id_sesi = ?";
        $stmt_get_cart = $koneksi->prepare($sql_get_cart);
        $stmt_get_cart->bind_param("s", $id_sesi);
        $stmt_get_cart->execute();
        $cart_items = $stmt_get_cart->get_result()->fetch_all(MYSQLI_ASSOC);

        if (count($cart_items) === 0) {
            throw new Exception("Keranjang Anda kosong.");
        }

        $total_harga_sebelum_diskon = 0;
        foreach ($cart_items as $item) {
            if ($item['jumlah'] > $item['stok']) {
                throw new Exception("Stok untuk produk " . htmlspecialchars($item['nama_produk']) . " tidak mencukupi.");
            }
            $total_harga_sebelum_diskon += $item['jumlah'] * $item['harga'];
        }
        
        $id_pelanggan = 1;
        $diskon = 0;
        $total_harga_final = $total_harga_sebelum_diskon;
        $pesan_sukses = "Order placed successfully!";

        $guest_nama = null;
        $guest_email = null;
        $guest_telpon = null;
        $guest_alamat = null;

        if (isset($_SESSION['id_pelanggan'])) {
            $id_pelanggan = $_SESSION['id_pelanggan'];
            $diskon = $total_harga_sebelum_diskon * 0.10;
            $total_harga_final = $total_harga_sebelum_diskon - $diskon;
            $pesan_sukses = "Order placed successfully! You received a 10% discount!";
        } else {
            $guest_nama = $_POST['nama'] ?? '';
            $guest_email = $_POST['email'] ?? '';
            $guest_telpon = $_POST['no_telpon'] ?? '';
            $guest_alamat = $_POST['alamat'] ?? '';

            if (empty($guest_nama) || empty($guest_email) || empty($guest_telpon) || empty($guest_alamat)) {
                throw new Exception("Data diri harus diisi lengkap.");
            }
        }

        $id_karyawan = 1;

        $sql_insert_pesanan = "INSERT INTO pesanan (id_pelanggan, id_karyawan, nama_pelanggan, email_pelanggan, no_telpon, alamat_pengiriman, tanggal_pesanan, total_harga, diskon) VALUES (?, ?, ?, ?, ?, ?, NOW(), ?, ?)";
        $stmt_insert_pesanan = $koneksi->prepare($sql_insert_pesanan);
        $stmt_insert_pesanan->bind_param("iissssdd", $id_pelanggan, $id_karyawan, $guest_nama, $guest_email, $guest_telpon, $guest_alamat, $total_harga_final, $diskon);
        $stmt_insert_pesanan->execute();
        
        $id_pesanan_baru = $koneksi->insert_id;

        $sql_insert_detail = "INSERT INTO detail_pesanan (id_pesanan, id_produk, jumlah, subtotal) VALUES (?, ?, ?, ?)";
        $stmt_insert_detail = $koneksi->prepare($sql_insert_detail);

        $sql_update_stok = "UPDATE produk SET stok = stok - ? WHERE id_produk = ?";
        $stmt_update_stok = $koneksi->prepare($sql_update_stok);

        foreach ($cart_items as $item) {
            $subtotal = $item['jumlah'] * $item['harga'];
            $stmt_insert_detail->bind_param("iiid", $id_pesanan_baru, $item['id_produk'], $item['jumlah'], $subtotal);
            $stmt_insert_detail->execute();

            $stmt_update_stok->bind_param("is", $item['jumlah'], $item['id_produk']);
            $stmt_update_stok->execute();
        }

        $sql_clear_cart = "DELETE FROM keranjang WHERE id_sesi = ?";
        $stmt_clear_cart = $koneksi->prepare($sql_clear_cart);
        $stmt_clear_cart->bind_param("s", $id_sesi);
        $stmt_clear_cart->execute();
        
        $koneksi->commit();
        
        $response['success'] = true;
        $response['message'] = $pesan_sukses;
        $response['order_id'] = $id_pesanan_baru;

    } catch (Exception $e) {
        $koneksi->rollback();
        $response['message'] = "Checkout failed: " . $e->getMessage();
    }
} else {
    $response['message'] = "Invalid request or the cart is empty.";
}

header('Content-Type: application/json');
echo json_encode($response);
exit;