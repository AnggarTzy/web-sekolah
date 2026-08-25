<?php
session_start();

if (!isset($_SESSION['login'])) {
    header("Location: ../admin/login.php");
    exit;
}

include '../config/koneksi.php';

// Ambil ID dari URL (default 0 kalau tidak ada)
$id = $_GET['id'] ?? 0;

// Ambil data berita berdasarkan ID
$result = mysqli_query($conn, "SELECT * FROM berita WHERE id = '$id'");
$berita = mysqli_fetch_assoc($result);

// Jika tidak ditemukan
if (!$berita) {
    header("Location: index.php");
    exit;
}

// Proses update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $judul = mysqli_real_escape_string($conn, $_POST['judul']);
    $kategori = mysqli_real_escape_string($conn, $_POST['kategori']);
    $konten = mysqli_real_escape_string($conn, $_POST['konten']);
    $penulis = mysqli_real_escape_string($conn, $_POST['penulis']);
    $status = $_POST['status'];

    $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $judul)));

    // Upload gambar baru (jika ada)
    $gambar_lama = $berita['gambar'];
    $gambar = $gambar_lama;

    if (!empty($_FILES['gambar']['name'])) {
        $target_dir = "../../uploads/";
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }
        $file_name = time() . "_" . basename($_FILES['gambar']['name']);
        $target_file = $target_dir . $file_name;
        
        if (move_uploaded_file($_FILES['gambar']['tmp_name'], $target_file)) {
            // Hapus gambar lama
            if ($gambar_lama && file_exists($target_dir . $gambar_lama)) {
                unlink($target_dir . $gambar_lama);
            }
            $gambar = $file_name;
        }
    }

    $query = mysqli_query($conn, "UPDATE berita SET 
                                  judul = '$judul', 
                                  slug = '$slug', 
                                  konten = '$konten', 
                                  gambar = '$gambar', 
                                  kategori = '$kategori', 
                                  penulis = '$penulis', 
                                  status = '$status' 
                                  WHERE id = '$id'");

    if ($query) {
        header("Location: index.php");
        exit;
    } else {
        $error = "Gagal mengupdate berita: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Berita | Admin SMP Muhammadiyah 6 Krian</title>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@500;600;700;800&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    
    <!-- Google Material Icons -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#1A3C6E',
                        accent: '#C9A94A',
                    },
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                        headline: ['Plus Jakarta Sans', 'sans-serif'],
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-gray-50 font-sans text-slate-700 antialiased">

    <!-- ========== NAVBAR ========== -->
    <nav class="bg-primary text-white shadow-lg sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-accent rounded-lg flex items-center justify-center font-headline font-bold text-primary text-lg">
                        S
                    </div>
                    <span class="font-headline font-bold text-lg">Admin Panel</span>
                </div>
                
                <div class="flex items-center gap-4">
                    <a href="index.php" class="px-4 py-2 bg-white/10 hover:bg-white/20 rounded-lg text-sm font-semibold transition">
                        ← Kembali
                    </a>
                    <a href="../admin/logout.php" class="px-4 py-2 bg-red-500 hover:bg-red-600 rounded-lg text-sm font-semibold transition">
                        Logout
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- ========== KONTEN UTAMA ========== -->
    <main class="max-w-4xl mx-auto p-6 sm:p-8">
        
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-5 border-b border-gray-100">
                <h1 class="font-headline font-extrabold text-2xl text-primary">Edit Berita</h1>
                <p class="text-sm text-gray-500 mt-1">Perbarui informasi berita yang sudah ada.</p>
            </div>
            
            <div class="p-6">
                <?php if (isset($error)) : ?>
                    <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl mb-6 text-sm">
                        <?= $error ?>
                    </div>
                <?php endif; ?>

                <form method="POST" enctype="multipart/form-data" class="space-y-6">
                    
                    <!-- Judul -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Judul Berita</label>
                        <input type="text" name="judul" value="<?= htmlspecialchars($berita['judul']) ?>" required
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary focus:border-primary transition outline-none"
                               placeholder="Masukkan judul berita">
                    </div>

                    <!-- Kategori -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Kategori</label>
                        <select name="kategori" 
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary focus:border-primary transition outline-none">
                            <option value="umum" <?= $berita['kategori'] == 'umum' ? 'selected' : '' ?>>Umum</option>
                            <option value="akademik" <?= $berita['kategori'] == 'akademik' ? 'selected' : '' ?>>Akademik</option>
                            <option value="kegiatan" <?= $berita['kategori'] == 'kegiatan' ? 'selected' : '' ?>>Kegiatan</option>
                            <option value="prestasi" <?= $berita['kategori'] == 'prestasi' ? 'selected' : '' ?>>Prestasi</option>
                        </select>
                    </div>

                    <!-- Konten -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Konten</label>
                        <textarea name="konten" rows="8" required
                                  class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary focus:border-primary transition outline-none"
                                  placeholder="Tulis isi berita di sini..."><?= htmlspecialchars($berita['konten']) ?></textarea>
                    </div>

                    <!-- Penulis -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Penulis</label>
                        <input type="text" name="penulis" value="<?= htmlspecialchars($berita['penulis']) ?>" required
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary focus:border-primary transition outline-none">
                    </div>

                    <!-- Gambar Saat Ini -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Gambar Saat Ini</label>
                        <?php if ($berita['gambar']) : ?>
                            <div class="mb-3">
                                <img src="../../uploads/<?= $berita['gambar'] ?>" alt="Gambar" class="w-48 h-32 object-cover rounded-xl border border-gray-200">
                            </div>
                        <?php else : ?>
                            <p class="text-sm text-gray-400">Tidak ada gambar</p>
                        <?php endif; ?>
                        
                        <label class="block text-sm font-semibold text-gray-700 mb-2 mt-4">Ganti Gambar (jika ada)</label>
                        <input type="file" name="gambar" accept="image/*"
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary focus:border-primary transition outline-none file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-primary file:text-white hover:file:bg-primary-dark">
                    </div>

                    <!-- Status -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Status</label>
                        <select name="status" 
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary focus:border-primary transition outline-none">
                            <option value="publish" <?= $berita['status'] == 'publish' ? 'selected' : '' ?>>Publish</option>
                            <option value="draft" <?= $berita['status'] == 'draft' ? 'selected' : '' ?>>Draft</option>
                        </select>
                    </div>

                    <!-- Tombol Aksi -->
                    <div class="flex items-center gap-4 pt-4">
                        <button type="submit" 
                                class="px-6 py-3 bg-primary text-white rounded-xl font-semibold text-sm hover:bg-opacity-90 transition shadow-lg">
                            Simpan Perubahan
                        </button>
                        <a href="index.php" class="px-6 py-3 bg-gray-100 text-gray-700 rounded-xl font-semibold text-sm hover:bg-gray-200 transition">
                            Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>

    </main>

</body>
</html>