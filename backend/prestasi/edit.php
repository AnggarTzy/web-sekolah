<?php
session_start();

if (!isset($_SESSION['login'])) {
    header("Location: ../admin/login.php");
    exit;
}

include '../config/koneksi.php';

$id = $_GET['id'] ?? 0;
$result = mysqli_query($conn, "SELECT * FROM prestasi WHERE id = '$id'");
$prestasi = mysqli_fetch_assoc($result);

if (!$prestasi) {
    header("Location: index.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $judul = mysqli_real_escape_string($conn, $_POST['judul']);
    $kategori = mysqli_real_escape_string($conn, $_POST['kategori']);
    $tingkat = mysqli_real_escape_string($conn, $_POST['tingkat']);
    $tahun = mysqli_real_escape_string($conn, $_POST['tahun']);
    $deskripsi = mysqli_real_escape_string($conn, $_POST['deskripsi']);

    $gambar_lama = $prestasi['gambar'];
    $gambar = $gambar_lama;

    if (!empty($_FILES['gambar']['name'])) {
        $target_dir = "../../uploads/";
        if (!is_dir($target_dir)) mkdir($target_dir, 0777, true);
        $file_name = time() . "_" . basename($_FILES['gambar']['name']);
        if (move_uploaded_file($_FILES['gambar']['tmp_name'], $target_dir . $file_name)) {
            if ($gambar_lama && file_exists($target_dir . $gambar_lama)) unlink($target_dir . $gambar_lama);
            $gambar = $file_name;
        }
    }

    $query = mysqli_query($conn, "UPDATE prestasi SET 
                                  judul = '$judul', kategori = '$kategori', tingkat = '$tingkat', 
                                  tahun = '$tahun', deskripsi = '$deskripsi', gambar = '$gambar' 
                                  WHERE id = '$id'");

    if ($query) {
        header("Location: index.php");
        exit;
    } else {
        $error = "Gagal mengupdate: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Prestasi | Admin</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@500;600;700;800&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: { primary: '#1A3C6E', accent: '#C9A94A' },
                    fontFamily: { sans: ['Inter', 'sans-serif'], headline: ['Plus Jakarta Sans', 'sans-serif'] }
                }
            }
        }
    </script>
</head>
<body class="bg-gray-50 font-sans text-slate-700 antialiased">

    <nav class="bg-primary text-white shadow-lg sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-accent rounded-lg flex items-center justify-center font-headline font-bold text-primary text-lg">S</div>
                    <span class="font-headline font-bold text-lg">Admin Panel</span>
                </div>
                <div class="flex items-center gap-4">
                    <a href="index.php" class="px-4 py-2 bg-white/10 hover:bg-white/20 rounded-lg text-sm font-semibold transition">← Kembali</a>
                    <a href="../admin/logout.php" class="px-4 py-2 bg-red-500 hover:bg-red-600 rounded-lg text-sm font-semibold transition">Logout</a>
                </div>
            </div>
        </div>
    </nav>

    <main class="max-w-4xl mx-auto p-6 sm:p-8">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-5 border-b border-gray-100">
                <h1 class="font-headline font-extrabold text-2xl text-primary">Edit Prestasi</h1>
            </div>
            <div class="p-6">
                <?php if (isset($error)) : ?>
                    <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl mb-6 text-sm"><?= $error ?></div>
                <?php endif; ?>

                <form method="POST" enctype="multipart/form-data" class="space-y-6">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Judul Prestasi</label>
                        <input type="text" name="judul" value="<?= htmlspecialchars($prestasi['judul']) ?>" required class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary focus:border-primary transition outline-none">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Kategori</label>
                        <select name="kategori" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary focus:border-primary transition outline-none">
                            <option value="akademik" <?= $prestasi['kategori'] == 'akademik' ? 'selected' : '' ?>>Akademik</option>
                            <option value="olahraga" <?= $prestasi['kategori'] == 'olahraga' ? 'selected' : '' ?>>Olahraga</option>
                            <option value="seni" <?= $prestasi['kategori'] == 'seni' ? 'selected' : '' ?>>Seni</option>
                            <option value="teknologi" <?= $prestasi['kategori'] == 'teknologi' ? 'selected' : '' ?>>Teknologi</option>
                            <option value="umum" <?= $prestasi['kategori'] == 'umum' ? 'selected' : '' ?>>Umum</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Tingkat</label>
                        <select name="tingkat" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary focus:border-primary transition outline-none">
                            <option value="sekolah" <?= $prestasi['tingkat'] == 'sekolah' ? 'selected' : '' ?>>Sekolah</option>
                            <option value="kecamatan" <?= $prestasi['tingkat'] == 'kecamatan' ? 'selected' : '' ?>>Kecamatan</option>
                            <option value="kabupaten" <?= $prestasi['tingkat'] == 'kabupaten' ? 'selected' : '' ?>>Kabupaten</option>
                            <option value="provinsi" <?= $prestasi['tingkat'] == 'provinsi' ? 'selected' : '' ?>>Provinsi</option>
                            <option value="nasional" <?= $prestasi['tingkat'] == 'nasional' ? 'selected' : '' ?>>Nasional</option>
                            <option value="internasional" <?= $prestasi['tingkat'] == 'internasional' ? 'selected' : '' ?>>Internasional</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Tahun</label>
                        <input type="number" name="tahun" min="2000" max="2100" value="<?= $prestasi['tahun'] ?>" required class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary focus:border-primary transition outline-none">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Deskripsi</label>
                        <textarea name="deskripsi" rows="5" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary focus:border-primary transition outline-none"><?= htmlspecialchars($prestasi['deskripsi']) ?></textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Gambar Saat Ini</label>
                        <?php if ($prestasi['gambar']) : ?>
                            <div class="mb-3"><img src="../../uploads/<?= $prestasi['gambar'] ?>" class="w-48 h-32 object-cover rounded-xl border border-gray-200"></div>
                        <?php else : ?>
                            <p class="text-sm text-gray-400">Tidak ada gambar</p>
                        <?php endif; ?>
                        <label class="block text-sm font-semibold text-gray-700 mb-2 mt-4">Ganti Gambar (jika ada)</label>
                        <input type="file" name="gambar" accept="image/*" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary focus:border-primary transition outline-none">
                    </div>
                    <div class="flex items-center gap-4 pt-4">
                        <button type="submit" class="px-6 py-3 bg-primary text-white rounded-xl font-semibold text-sm hover:bg-opacity-90 transition shadow-lg">Simpan Perubahan</button>
                        <a href="index.php" class="px-6 py-3 bg-gray-100 text-gray-700 rounded-xl font-semibold text-sm hover:bg-gray-200 transition">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </main>
</body>
</html>