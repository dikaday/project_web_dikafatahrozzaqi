<?php
// admin/auth.php
session_start();
if (!isset($_SESSION['id_karyawan'])) {
    header('Location: login.php');
    exit;
}
?>