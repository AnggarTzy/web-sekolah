<?php
/**
 * =====================================================
 * KONEKSI DATABASE + FUNGSI HELPER
 * =====================================================
 * 
 * Revisi:
 * - Kredensial dipindah ke credential.php (biar aman)
 * - catat_aktivitas() sekarang nyatet username
 * - Tambah fungsi: cek_login(), hitung_total(), buat_slug(),
 *   upload_gambar(), hapus_gambar()
 */

// =====================================================
// LOAD KREDENSIAL
// =====================================================
$cred_path = __DIR__ . '/credential.php';

if (!file_exists($cred_path)) {
    die("File credential.php tidak ditemukan. Copy dari credential.example.php dulu.");
}

$cred = require $cred_path;

$host = $cred['host'];
$user = $cred['user'];
$pass = $cred['pass'];
$db   = $cred['db'];

$conn = mysqli_connect($host, $user, $pass, $db);

if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error() . 
        "<br>Host: $host | User: $user | DB: $db");
}

// Set charset biar aman dari karakter aneh
mysqli_set_charset($conn, "utf8mb4");


// =====================================================
// FUNGSI UNTUK MENCATAT AKTIVITAS
// =====================================================
function catat_aktivitas($conn, $modul, $aksi, $judul) {
    // Ambil username dari session (kalau ada)
    $username = $_SESSION['username'] ?? 'System';
    
    // Pakai prepared statement biar aman
    $stmt = mysqli_prepare(
        $conn,
        "INSERT INTO aktivitas (modul, aksi, judul, username) 
         VALUES (?, ?, ?, ?)"
    );
    
    if (!$stmt) {
        return false;
    }
    
    mysqli_stmt_bind_param($stmt, "ssss", $modul, $aksi, $judul, $username);
    $result = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    
    return $result ? true : false;
}


// =====================================================
// FUNGSI: CEK LOGIN (dipakai di setiap halaman admin)
// =====================================================
function cek_login() {
    if (!isset($_SESSION['login']) || $_SESSION['login'] !== true) {
        header("Location: ../admin/login.php");
        exit;
    }
}


// =====================================================
// FUNGSI: HITUNG TOTAL BARIS TABEL
// =====================================================
function hitung_total($conn, $tabel) {
    // Whitelist tabel — cegah SQL injection
    $allowed = ['berita', 'prestasi', 'guru', 'fasilitas', 'ekstrakurikuler', 'galeri'];
    
    if (!in_array($tabel, $allowed)) {
        return 0;
    }
    
    $query = mysqli_query($conn, "SELECT COUNT(*) AS total FROM $tabel");
    return $query ? (int) mysqli_fetch_assoc($query)['total'] : 0;
}


// =====================================================
// FUNGSI: BUAT SLUG DARI JUDUL
// =====================================================
function buat_slug($teks) {
    $slug = strtolower(trim($teks));
    $slug = preg_replace('/[^a-z0-9-]+/', '-', $slug);
    $slug = trim($slug, '-');
    return $slug;
}


// =====================================================
// FUNGSI: UPLOAD GAMBAR
// Return: ['success' => bool, 'filename' => string|null, 'error' => string|null]
// =====================================================
function upload_gambar($file, $target_dir = "../../uploads/") {
    // Cek ada file atau nggak
    if (!isset($file) || $file['error'] === UPLOAD_ERR_NO_FILE) {
        return ['success' => false, 'filename' => null, 'error' => 'Tidak ada file'];
    }
    
    if ($file['error'] !== UPLOAD_ERR_OK) {
        return ['success' => false, 'filename' => null, 'error' => 'Gagal upload file'];
    }
    
    // Validasi tipe
    $allowed_types = ['image/jpeg', 'image/png', 'image/jpg', 'image/webp'];
    $file_type = mime_content_type($file['tmp_name']);
    
    if (!in_array($file_type, $allowed_types)) {
        return ['success' => false, 'filename' => null, 'error' => 'Format gambar tidak diperbolehkan. Gunakan JPG, JPEG, PNG, atau WEBP.'];
    }
    
    // Validasi ukuran (max 5MB)
    if ($file['size'] > 5 * 1024 * 1024) {
        return ['success' => false, 'filename' => null, 'error' => 'Ukuran gambar terlalu besar. Maksimal 5MB.'];
    }
    
    // Buat folder kalau belum ada
    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0777, true);
    }
    
    // Generate nama file unik
    $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    $file_name = time() . '_' . uniqid() . '.' . $extension;
    $target_file = $target_dir . $file_name;
    
    if (!move_uploaded_file($file['tmp_name'], $target_file)) {
        return ['success' => false, 'filename' => null, 'error' => 'Gagal menyimpan file'];
    }
    
    return ['success' => true, 'filename' => $file_name, 'error' => null];
}


// =====================================================
// FUNGSI: HAPUS GAMBAR DARI FOLDER
// =====================================================
function hapus_gambar($filename, $target_dir = "../../uploads/") {
    if (empty($filename)) {
        return false;
    }
    
    $path = $target_dir . $filename;
    if (file_exists($path)) {
        return unlink($path);
    }
    
    return false;
}
?>