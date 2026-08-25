<?php
session_start();

// Path BENAR: naik 1 level dari admin ke backend, lalu masuk ke config
include '../config/koneksi.php';

// Cek koneksi
if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

$username = $_POST['username'];
$password = $_POST['password'];

// Query login
$query = mysqli_query($conn, "SELECT * FROM admin WHERE username='$username' AND password='$password'");

if (!$query) {
    die("Query error: " . mysqli_error($conn));
}

if (mysqli_num_rows($query) > 0) {
    $_SESSION['login'] = true;
    $_SESSION['username'] = $username;
    header("Location: dashboard.php");
} else {
    header("Location: login.php?error=1");
}
?>