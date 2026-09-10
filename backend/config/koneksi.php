<?php
$host = "localhost";
$user = "root";
$pass = "";
$db   = "db_sekolah";

$conn = mysqli_connect($host, $user, $pass, $db);

if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error() . 
        "<br>Host: $host | User: $user | DB: $db");
}
?>

<?php
$host = "localhost";
$user = "root";
$pass = "";
$db   = "db_sekolah";

$conn = mysqli_connect($host, $user, $pass, $db);

if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

// =====================================================
// FUNGSI UNTUK MENCATAT AKTIVITAS
// =====================================================
function catat_aktivitas($conn, $modul, $aksi, $judul) {
    $modul = mysqli_real_escape_string($conn, $modul);
    $aksi = mysqli_real_escape_string($conn, $aksi);
    $judul = mysqli_real_escape_string($conn, $judul);
    
    $query = mysqli_query($conn, "INSERT INTO aktivitas (modul, aksi, judul) 
                                  VALUES ('$modul', '$aksi', '$judul')");
    
    return $query ? true : false;
}
?>