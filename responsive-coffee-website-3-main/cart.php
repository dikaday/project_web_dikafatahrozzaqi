<?php
// cart.php - File untuk operasi keranjang belanja
session_start();
include 'assets/koneksi.php';

// Fungsi untuk mendapatkan session ID
function getSessionId() {
    if (!isset($_SESSION['cart_session_id'])) {
        $_SESSION['cart_session_id'] = session_id() . '_' . time();
    }
    return $_SESSION['cart_session_id'];
}

// Fungsi untuk mendapatkan semua item dalam keranjang
function getCartItems($koneksi) {
    $id_sesi = getSessionId();
    
    $sql = "SELECT k.*, p.nama_produk, p.harga, p.gambar 
            FROM keranjang k 
            JOIN produk p ON k.id_produk = p.id_produk 
            WHERE k.id_sesi = ?
            ORDER BY k.dibuat_pada DESC";
    
    $stmt = $koneksi->prepare($sql);
    $stmt->bind_param("s", $id_sesi);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $items = [];
    while ($row = $result->fetch_assoc()) {
        $items[] = $row;
    }
    
    return $items;
}

// Fungsi untuk menghitung total item dan harga
function getCartSummary($koneksi) {
    $items = getCartItems($koneksi);
    $total_item = 0;
    $total_harga = 0;
    
    foreach ($items as $item) {
        $total_item += $item['jumlah'];
        $total_harga += $item['jumlah'] * $item['harga'];
    }
    
    return [
        'total_item' => $total_item,
        'total_harga' => $total_harga,
        'items' => $items
    ];
}

// Menangani request AJAX
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $response = ['success' => false, 'message' => ''];
    
    $action = $_POST['action'] ?? '';
    
    switch ($action) {
        case 'add':
            $id_produk = $_POST['id_produk'] ?? '';
            $jumlah = (int)($_POST['jumlah'] ?? 1);
            
            if (empty($id_produk)) {
                $response['message'] = ' Invalid product ID';
                break;
            }
            
            // Cek apakah produk ada
            $sql_check = "SELECT id_produk, stok FROM produk WHERE id_produk = ?";
            $stmt_check = $koneksi->prepare($sql_check);
            $stmt_check->bind_param("s", $id_produk);
            $stmt_check->execute();
            $produk = $stmt_check->get_result()->fetch_assoc();
            
            if (!$produk) {
                $response['message'] = 'Product not found';
                break;
            }
            
            if ($produk['stok'] < $jumlah) {
                $response['message'] = 'Insufficient stock';
                break;
            }
            
            $id_sesi = getSessionId();
            
            // Cek apakah item sudah ada di keranjang
            $sql_exist = "SELECT id_keranjang, jumlah FROM keranjang WHERE id_sesi = ? AND id_produk = ?";
            $stmt_exist = $koneksi->prepare($sql_exist);
            $stmt_exist->bind_param("ss", $id_sesi, $id_produk);
            $stmt_exist->execute();
            $existing = $stmt_exist->get_result()->fetch_assoc();
            
            if ($existing) {
                // Update jumlah jika item sudah ada
                $new_jumlah = $existing['jumlah'] + $jumlah;
                
                if ($new_jumlah > $produk['stok']) {
                    $response['message'] = 'Insufficient stock';
                    break;
                }
                
                $sql_update = "UPDATE keranjang SET jumlah = ?, diperbarui_pada = CURRENT_TIMESTAMP WHERE id_keranjang = ?";
                $stmt_update = $koneksi->prepare($sql_update);
                $stmt_update->bind_param("ii", $new_jumlah, $existing['id_keranjang']);
                
                if ($stmt_update->execute()) {
                    $response['success'] = true;
                    $response['message'] = 'Item added to cart.';
                }
            } else {
                // Tambah item baru
                $sql_insert = "INSERT INTO keranjang (id_sesi, id_produk, jumlah) VALUES (?, ?, ?)";
                $stmt_insert = $koneksi->prepare($sql_insert);
                $stmt_insert->bind_param("ssi", $id_sesi, $id_produk, $jumlah);
                
                if ($stmt_insert->execute()) {
                    $response['success'] = true;
                    $response['message'] = 'Item added to cart.';
                }
            }
            break;
            
        case 'update':
            $id_keranjang = (int)($_POST['id_keranjang'] ?? 0);
            $jumlah = (int)($_POST['jumlah'] ?? 1);
            
            if ($jumlah < 1) {
                $response['message'] = 'Minimum quantity is 1.';
                break;
            }
            
            $id_sesi = getSessionId();
            
            // Cek stok produk
            $sql_check_stock = "SELECT p.stok FROM keranjang k 
                               JOIN produk p ON k.id_produk = p.id_produk 
                               WHERE k.id_keranjang = ? AND k.id_sesi = ?";
            $stmt_check_stock = $koneksi->prepare($sql_check_stock);
            $stmt_check_stock->bind_param("is", $id_keranjang, $id_sesi);
            $stmt_check_stock->execute();
            $stok_result = $stmt_check_stock->get_result()->fetch_assoc();
            
            if (!$stok_result || $jumlah > $stok_result['stok']) {
                $response['message'] = 'Insufficient stock';
                break;
            }
            
            $sql_update = "UPDATE keranjang SET jumlah = ?, diperbarui_pada = CURRENT_TIMESTAMP 
                          WHERE id_keranjang = ? AND id_sesi = ?";
            $stmt_update = $koneksi->prepare($sql_update);
            $stmt_update->bind_param("iis", $jumlah, $id_keranjang, $id_sesi);
            
            if ($stmt_update->execute()) {
                $response['success'] = true;
                $response['message'] = 'Cart updated successfully';
            }
            break;
            
        case 'remove':
            $id_keranjang = (int)($_POST['id_keranjang'] ?? 0);
            $id_sesi = getSessionId();
            
            $sql_delete = "DELETE FROM keranjang WHERE id_keranjang = ? AND id_sesi = ?";
            $stmt_delete = $koneksi->prepare($sql_delete);
            $stmt_delete->bind_param("is", $id_keranjang, $id_sesi);
            
            if ($stmt_delete->execute()) {
                $response['success'] = true;
                $response['message'] = 'Item removed from cart successfully';
            }
            break;
            
        case 'get_cart':
            $response['success'] = true;
            $response['data'] = getCartSummary($koneksi);
            break;
            
        case 'clear_cart':
            $id_sesi = getSessionId();
            $sql_clear = "DELETE FROM keranjang WHERE id_sesi = ?";
            $stmt_clear = $koneksi->prepare($sql_clear);
            $stmt_clear->bind_param("s", $id_sesi);
            
            if ($stmt_clear->execute()) {
                $response['success'] = true;
                $response['message'] = 'Cart cleared successfully';
            }
            break;
            
        default:
            $response['message'] = 'Invalid action';
    }
    
    // Return JSON response
    header('Content-Type: application/json');
    echo json_encode($response);
    exit;
}

// Jika tidak ada POST request, tampilkan halaman keranjang
$cart_summary = getCartSummary($koneksi);
?>