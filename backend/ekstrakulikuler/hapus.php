<?php
session_start();

if (!isset($_SESSION['login'])) {
    header("Location: ../admin/login.php");
    exit;
}

include '../config/koneksi.php';

$id = $_GET['id'] ?? 0;

$result = mysqli_query($conn, "SELECT gambar FROM ekstrakurikuler WHERE id = '$id'");
$ekskul = mysqli_fetch_assoc($result);

if ($ekskul && $ekskul['gambar'] && file_exists("../../uploads/" . $ekskul['gambar'])) {
    unlink("../../uploads/" . $ekskul['gambar']);
}

$query = mysqli_query($conn, "DELETE FROM ekstrakurikuler WHERE id = '$id'");

if ($query) {
    header("Location: index.php?status=success");
    exit;
} else {
    header("Location: index.php?status=error");
    exit;
}
?>