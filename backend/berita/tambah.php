<?php
session_start();

if (!isset($_SESSION['login'])) {
    header("Location: ../admin/login.php");
    exit;
}

include '../config/koneksi.php';

// Proses tambah berita
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $judul = mysqli_real_escape_string($conn, $_POST['judul']);
    $kategori = mysqli_real_escape_string($conn, $_POST['kategori']);
    $konten = mysqli_real_escape_string($conn, $_POST['konten']);
    $penulis = mysqli_real_escape_string($conn, $_POST['penulis']);
    $status = $_POST['status'];

    // Buat slug dari judul
    $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $judul)));

    // Upload gambar
    $gambar = null;
    if (!empty($_FILES['gambar']['name'])) {
        $target_dir = "../../uploads/";
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }
        $file_name = time() . "_" . basename($_FILES['gambar']['name']);
        $target_file = $target_dir . $file_name;
        
        if (move_uploaded_file($_FILES['gambar']['tmp_name'], $target_file)) {
            $gambar = $file_name;
        }
    }

    $query = mysqli_query($conn, "INSERT INTO berita (judul, slug, konten, gambar, kategori, penulis, status) 
                                  VALUES ('$judul', '$slug', '$konten', '$gambar', '$kategori', '$penulis', '$status')");

    if ($query) {
        header("Location: index.php");
        exit;
    } else {
        $error = "Gagal menyimpan berita: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Berita</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f4f6f9; margin: 0; padding: 20px; }
        .container { max-width: 600px; margin: auto; background: white; padding: 30px; border-radius: 12px; box-shadow: 0 4px 16px rgba(0,0,0,0.08); }
        h1 { color: #1A3C6E; }
        label { display: block; margin-top: 15px; font-weight: 600; }
        input, textarea, select { width: 100%; padding: 10px; margin-top: 5px; border: 1px solid #ddd; border-radius: 8px; font-size: 14px; }
        button { margin-top: 20px; background: #1A3C6E; color: white; border: none; padding: 12px 24px; border-radius: 8px; font-size: 14px; cursor: pointer; }
        button:hover { background: #2a5a8c; }
        .btn-back { display: inline-block; margin-top: 20px; color: #666; text-decoration: none; }
    </style>
</head>
<body>
<div class="container">
    <h1>Tambah Berita</h1>
    
    <?php if (isset($error)) : ?>
        <p style="color: red;"><?= $error ?></p>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data">
        <label>Judul</label>
        <input type="text" name="judul" required>
        
        <label>Kategori</label>
        <select name="kategori">
            <option value="umum">Umum</option>
            <option value="akademik">Akademik</option>
            <option value="kegiatan">Kegiatan</option>
            <option value="prestasi">Prestasi</option>
        </select>
        
        <label>Konten</label>
        <textarea name="konten" rows="6" required></textarea>
        
        <label>Penulis</label>
        <input type="text" name="penulis" value="Admin" required>
        
        <label>Gambar</label>
        <input type="file" name="gambar" accept="image/*">
        
        <label>Status</label>
        <select name="status">
            <option value="publish">Publish</option>
            <option value="draft">Draft</option>
        </select>
        
        <button type="submit">Simpan</button>
    </form>
    
    <a href="index.php" class="btn-back">← Kembali ke Daftar</a>
</div>
</body>
</html>