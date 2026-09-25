<?php
session_start();

include '../config/koneksi.php';

// Load kode rahasia
$reset_config_path = __DIR__ . '/../config/reset_code.php';
if (!file_exists($reset_config_path)) {
    header("Location: lupa-password.php?error=server");
    exit;
}

$reset_config = require $reset_config_path;

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: lupa-password.php");
    exit;
}

$reset_code       = trim($_POST['reset_code'] ?? '');
$new_password     = $_POST['new_password'] ?? '';
$confirm_password = $_POST['confirm_password'] ?? '';

// Validasi input
if ($reset_code === '' || $new_password === '' || $confirm_password === '') {
    header("Location: lupa-password.php?error=empty");
    exit;
}

// Cek kode rahasia
if ($reset_code !== $reset_config['reset_code']) {
    header("Location: lupa-password.php?error=1");
    exit;
}

// Cek panjang password
if (strlen($new_password) < 8) {
    header("Location: lupa-password.php?error=weak");
    exit;
}

// Cek konfirmasi password
if ($new_password !== $confirm_password) {
    header("Location: lupa-password.php?error=match");
    exit;
}

// Hash password baru
$hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

// Cari admin pertama (karena cuma 1 admin)
$result = mysqli_query($conn, "SELECT id, username FROM admin LIMIT 1");
$admin = mysqli_fetch_assoc($result);

if (!$admin) {
    header("Location: lupa-password.php?error=server");
    exit;
}

// Update password
$stmt = mysqli_prepare($conn, "UPDATE admin SET password = ? WHERE id = ?");
mysqli_stmt_bind_param($stmt, "si", $hashed_password, $admin['id']);

if (mysqli_stmt_execute($stmt)) {
    mysqli_stmt_close($stmt);

    // Catat aktivitas
    $_SESSION['username'] = $admin['username'];
    catat_aktivitas($conn, 'Auth', 'Reset Password', $admin['username']);

    header("Location: lupa-password.php?success=1");
    exit;
} else {
    mysqli_stmt_close($stmt);
    header("Location: lupa-password.php?error=server");
    exit;
}
?>