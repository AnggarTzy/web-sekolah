<?php
session_start();

if (!isset($_SESSION['login'])) {
    header("Location: ../admin/login.php");
    exit;
}

include '../config/koneksi.php';

$id = $_GET['id'] ?? 0;

$result = mysqli_query($conn, "SELECT gambar FROM fasilitas WHERE id = '$id'");
$fasilitas = mysqli_fetch_assoc($result);

if ($fasilitas && $fasilitas['gambar'] && file_exists("../../uploads/" . $fasilitas['gambar'])) {
    unlink("../../uploads/" . $fasilitas['gambar']);
}

$query = mysqli_query($conn, "DELETE FROM fasilitas WHERE id = '$id'");

if ($query) {
    header("Location: index.php?status=success");
    exit;
} else {
    header("Location: index.php?status=error");
    exit;
}
?>