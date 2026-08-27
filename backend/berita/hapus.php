<?php
session_start();

if (!isset($_SESSION['login'])) {
    header("Location: ../admin/login.php");
    exit;
}

include '../config/koneksi.php';

// Ambil ID dari URL (default 0 kalau tidak ada)
$id = $_GET['id'] ?? 0;

// Ambil data berita untuk hapus gambar
$result = mysqli_query($conn, "SELECT gambar FROM berita WHERE id = '$id'");
$berita = mysqli_fetch_assoc($result);

// Hapus gambar dari folder uploads
if ($berita && $berita['gambar'] && file_exists("../../uploads/" . $berita['gambar'])) {
    unlink("../../uploads/" . $berita['gambar']);
}

// Hapus data dari database
$query = mysqli_query($conn, "DELETE FROM berita WHERE id = '$id'");

if ($query) {
    // Redirect ke index dengan pesan sukses
    header("Location: index.php?status=success");
    exit;
} else {
    // Redirect ke index dengan pesan gagal
    header("Location: index.php?status=error");
    exit;
}
?>