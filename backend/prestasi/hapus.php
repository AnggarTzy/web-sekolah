<?php
session_start();

if (!isset($_SESSION['login'])) {
    header("Location: ../admin/login.php");
    exit;
}

include '../config/koneksi.php';

$id = $_GET['id'] ?? 0;

$result = mysqli_query($conn, "SELECT gambar FROM prestasi WHERE id = '$id'");
$prestasi = mysqli_fetch_assoc($result);

if ($prestasi && $prestasi['gambar'] && file_exists("../../uploads/" . $prestasi['gambar'])) {
    unlink("../../uploads/" . $prestasi['gambar']);
}

$query = mysqli_query($conn, "DELETE FROM prestasi WHERE id = '$id'");

if ($query) {
    header("Location: index.php?status=success");
    exit;
} else {
    header("Location: index.php?status=error");
    exit;
}
?>