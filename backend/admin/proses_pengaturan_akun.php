<?php
session_start();

include '../config/koneksi.php';

cek_login();

$admin_id = $_SESSION['admin_id'] ?? 0;
$current_username = $_SESSION['username'] ?? '';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: pengaturan-akun.php");
    exit;
}

$new_username     = trim($_POST['new_username'] ?? '');
$old_password     = $_POST['old_password'] ?? '';
$new_password     = $_POST['new_password'] ?? '';
$confirm_password = $_POST['confirm_password'] ?? '';

// Validasi dasar
if ($new_username === '' || $old_password === '') {
    header("Location: pengaturan-akun.php?error=empty");
    exit;
}

// Ambil data admin dari DB
$stmt = mysqli_prepare($conn, "SELECT id, username, password FROM admin WHERE id = ?");
mysqli_stmt_bind_param($stmt, "i", $admin_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$admin = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt);

if (!$admin) {
    header("Location: pengaturan-akun.php?error=server");
    exit;
}

// Verifikasi password lama
if (!password_verify($old_password, $admin['password'])) {
    header("Location: pengaturan-akun.php?error=wrong");
    exit;
}

$update_username = false;
$update_password = false;

// Cek apakah username berubah
if ($new_username !== $admin['username']) {
    // Cek username udah dipakai atau belum
    $stmt = mysqli_prepare($conn, "SELECT id FROM admin WHERE username = ? AND id != ?");
    mysqli_stmt_bind_param($stmt, "si", $new_username, $admin_id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_store_result($stmt);

    if (mysqli_stmt_num_rows($stmt) > 0) {
        mysqli_stmt_close($stmt);
        header("Location: pengaturan-akun.php?error=username");
        exit;
    }
    mysqli_stmt_close($stmt);

    $update_username = true;
}

// Cek apakah password diganti
if ($new_password !== '') {
    // Cek panjang
    if (strlen($new_password) < 8) {
        header("Location: pengaturan-akun.php?error=weak");
        exit;
    }

    // Cek konfirmasi
    if ($new_password !== $confirm_password) {
        header("Location: pengaturan-akun.php?error=match");
        exit;
    }

    // Cek password baru != password lama
    if (password_verify($new_password, $admin['password'])) {
        header("Location: pengaturan-akun.php?error=same");
        exit;
    }

    $update_password = true;
}

// Nggak ada yang berubah
if (!$update_username && !$update_password) {
    header("Location: pengaturan-akun.php");
    exit;
}

// Build query dinamis
if ($update_username && $update_password) {
    $hashed = password_hash($new_password, PASSWORD_DEFAULT);
    $stmt = mysqli_prepare($conn, "UPDATE admin SET username = ?, password = ? WHERE id = ?");
    mysqli_stmt_bind_param($stmt, "ssi", $new_username, $hashed, $admin_id);
    $status = 'both';
} elseif ($update_username) {
    $stmt = mysqli_prepare($conn, "UPDATE admin SET username = ? WHERE id = ?");
    mysqli_stmt_bind_param($stmt, "si", $new_username, $admin_id);
    $status = 'username';
} else {
    $hashed = password_hash($new_password, PASSWORD_DEFAULT);
    $stmt = mysqli_prepare($conn, "UPDATE admin SET password = ? WHERE id = ?");
    mysqli_stmt_bind_param($stmt, "si", $hashed, $admin_id);
    $status = 'password';
}

if (mysqli_stmt_execute($stmt)) {
    mysqli_stmt_close($stmt);

    // Update session
    $_SESSION['username'] = $new_username;

    // Catat aktivitas
    if ($update_username && $update_password) {
        catat_aktivitas($conn, 'Akun', 'Ganti Username & Password', $new_username);
    } elseif ($update_username) {
        catat_aktivitas($conn, 'Akun', 'Ganti Username', $new_username);
    } else {
        catat_aktivitas($conn, 'Akun', 'Ganti Password', $new_username);
    }

    header("Location: pengaturan-akun.php?success=" . $status);
    exit;
} else {
    mysqli_stmt_close($stmt);
    header("Location: pengaturan-akun.php?error=server");
    exit;
}
?>