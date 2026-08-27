<?php
session_start();

if (!isset($_SESSION['login'])) {
    header("Location: ../admin/login.php");
    exit;
}

include '../config/koneksi.php';

$id = $_GET['id'] ?? 0;

$result = mysqli_query($conn, "SELECT foto FROM guru WHERE id = '$id'");
$guru = mysqli_fetch_assoc($result);

if ($guru && $guru['foto'] && file_exists("../../uploads/" . $guru['foto'])) {
    unlink("../../uploads/" . $guru['foto']);
}

$query = mysqli_query($conn, "DELETE FROM guru WHERE id = '$id'");

if ($query) {
    header("Location: index.php?status=success");
    exit;
} else {
    header("Location: index.php?status=error");
    exit;
}
?>