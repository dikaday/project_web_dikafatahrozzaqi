<?php
session_start();
// Jika sudah login, langsung arahkan ke dashboard
if (isset($_SESSION['id_karyawan'])) {
    header('Location: index.php');
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin Login - STARCOFFEE</title>
    <link rel="stylesheet" href="../assets/css/styles.css">
    <style>
        body { background-color: var(--body-white-color); display: flex; align-items: center; justify-content: center; min-height: 100vh; }
        .login-container { max-width: 400px; width: 100%; padding: 2rem; background-color: var(--dark-color); border-radius: 8px; box-shadow: 0 8px 24px hsla(166, 85%, 8%, .2); }
        .login-title { color: var(--white-color); text-align: center; margin-bottom: 2rem; font-family: var(--second-font); }
        .form-group { margin-bottom: 1rem; }
        label { color: var(--white-color); margin-bottom: .5rem; display: block; }
        input { width: 100%; padding: .75rem; border-radius: 4px; border: none; font-size: var(--normal-font-size); }
    </style>
</head>
<body>
    <div class="login-container">
        <h2 class="login-title">Admin / Kasir Login</h2>
        <?php if (isset($_GET['error'])) { echo '<p style="color: #e74c3c; text-align:center;">Username atau password salah!</p>'; } ?>
        <form action="proses_login.php" method="POST">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" name="username" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" name="password" required>
            </div>
            <button type="submit" class="button" style="width: 100%;">Login</button>
        </form>
    </div>
</body>
</html>