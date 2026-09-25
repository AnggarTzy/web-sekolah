<?php
session_start();

// Path BENAR: naik 1 level dari admin ke backend, lalu masuk ke config
include '../config/koneksi.php';

// Cek koneksi
if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

// Hanya terima request POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: login.php");
    exit;
}

$username = trim($_POST['username'] ?? '');
$password = $_POST['password'] ?? '';

// Validasi input kosong
if ($username === '' || $password === '') {
    header("Location: login.php?error=empty");
    exit;
}

// =====================================================
// QUERY LOGIN — PAKAI PREPARED STATEMENT
// =====================================================
$stmt = mysqli_prepare($conn, "SELECT id, username, password FROM admin WHERE username = ?");

if (!$stmt) {
    header("Location: login.php?error=server");
    exit;
}

mysqli_stmt_bind_param($stmt, "s", $username);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$admin = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt);

// =====================================================
// CEK PASSWORD PAKAI password_verify()
// =====================================================
if ($admin && password_verify($password, $admin['password'])) {
    
    // Regenerate session ID — cegah session fixation
    session_regenerate_id(true);
    
    $_SESSION['login']    = true;
    $_SESSION['username'] = $admin['username'];
    $_SESSION['admin_id'] = $admin['id'];
    
    header("Location: dashboard.php");
    exit;
}

// Login gagal
header("Location: login.php?error=1");
exit;
?>