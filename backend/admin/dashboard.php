<?php
session_start();

if(!isset($_SESSION['login'])){
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Dashboard</title>
</head>

<body>

<h1>Dashboard Admin</h1>

<hr>

<ul>

<li>Kelola Berita</li>
<li>Kelola Prestasi</li>
<li>Kelola Guru</li>
<li>Kelola Fasilitas</li>
<li>Kelola Ekstrakurikuler</li>

</ul>

<a href="logout.php">Logout</a>

</body>
</html>