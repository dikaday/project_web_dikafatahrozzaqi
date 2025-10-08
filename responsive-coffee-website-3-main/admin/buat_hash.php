<?php
// File ini hanya untuk membuat hash password secara manual.
// Setelah digunakan, file ini bisa dihapus.

$password_yang_diinginkan = 'admin123';

$hash = password_hash($password_yang_diinginkan, PASSWORD_DEFAULT);

echo 'Password: ' . $password_yang_diinginkan . '<br>';
echo 'Hash-nya adalah: <br>';
echo '<strong>' . $hash . '</strong>';
?>