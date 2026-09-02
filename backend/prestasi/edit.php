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
    <title>Edit Prestasi | Admin SMP Muhammadiyah 6 Krian</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@500;600;700;800&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

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
<body class="bg-slate-50 font-sans text-slate-700 antialiased">

    <nav class="bg-primary text-white shadow-lg sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-accent rounded-xl flex items-center justify-center font-headline font-extrabold text-primary text-lg shadow">S</div>
                    <div>
                        <p class="font-headline font-bold text-lg leading-tight">Admin Panel</p>
                        <p class="text-xs text-white/60">SMP Muhammadiyah 6 Krian</p>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <a href="index.php" class="flex items-center gap-2 px-4 py-2 bg-white/10 hover:bg-white/20 rounded-xl text-sm font-semibold transition">
                        <span class="material-icons text-base">arrow_back</span>
                        <span class="hidden sm:inline">Kembali</span>
                    </a>
                    <a href="../admin/logout.php" class="flex items-center gap-2 px-4 py-2 bg-red-500 hover:bg-red-600 rounded-xl text-sm font-semibold transition">
                        <span class="material-icons text-base">logout</span>
                        <span class="hidden sm:inline">Logout</span>
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <main class="max-w-4xl mx-auto px-4 sm:px-6 py-8">
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="px-6 py-5 border-b border-slate-100">
                <div class="flex items-center gap-3">
                    <div class="w-11 h-11 bg-yellow-100 text-primary rounded-xl flex items-center justify-center">
                        <span class="material-icons">edit</span>
                    </div>
                    <div>
                        <h1 class="font-headline font-extrabold text-2xl text-primary">Edit Prestasi</h1>
                        <p class="text-sm text-slate-500">Perbarui informasi prestasi.</p>
                    </div>
                </div>
            </div>

            <div class="p-6">
                <?php if (isset($error)) : ?>
                    <div class="mb-6 flex items-start gap-3 bg-red-50 border border-red-200 text-red-700 px-5 py-4 rounded-2xl">
                        <span class="material-icons">error_outline</span>
                        <div>
                            <p class="font-semibold">Terjadi Kesalahan</p>
                            <p class="text-sm mt-1"><?= htmlspecialchars($error) ?></p>
                        </div>
                    </div>
                <?php endif; ?>

                <form method="POST" enctype="multipart/form-data" class="space-y-6">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Judul Prestasi</label>
                        <div class="relative">
                            <span class="material-icons absolute left-4 top-1/2 -translate-y-1/2 text-slate-400">title</span>
                            <input type="text" name="judul" value="<?= htmlspecialchars($prestasi['judul']) ?>" required class="w-full pl-12 pr-4 py-3.5 border border-slate-300 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Kategori</label>
                            <select name="kategori" class="w-full px-4 py-3.5 border border-slate-300 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition">
                                <option value="akademik" <?= $prestasi['kategori'] == 'akademik' ? 'selected' : '' ?>>Akademik</option>
                                <option value="olahraga" <?= $prestasi['kategori'] == 'olahraga' ? 'selected' : '' ?>>Olahraga</option>
                                <option value="seni" <?= $prestasi['kategori'] == 'seni' ? 'selected' : '' ?>>Seni</option>
                                <option value="teknologi" <?= $prestasi['kategori'] == 'teknologi' ? 'selected' : '' ?>>Teknologi</option>
                                <option value="umum" <?= $prestasi['kategori'] == 'umum' ? 'selected' : '' ?>>Umum</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Tingkat</label>
                            <select name="tingkat" class="w-full px-4 py-3.5 border border-slate-300 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition">
                                <option value="sekolah" <?= $prestasi['tingkat'] == 'sekolah' ? 'selected' : '' ?>>Sekolah</option>
                                <option value="kecamatan" <?= $prestasi['tingkat'] == 'kecamatan' ? 'selected' : '' ?>>Kecamatan</option>
                                <option value="kabupaten" <?= $prestasi['tingkat'] == 'kabupaten' ? 'selected' : '' ?>>Kabupaten</option>
                                <option value="provinsi" <?= $prestasi['tingkat'] == 'provinsi' ? 'selected' : '' ?>>Provinsi</option>
                                <option value="nasional" <?= $prestasi['tingkat'] == 'nasional' ? 'selected' : '' ?>>Nasional</option>
                                <option value="internasional" <?= $prestasi['tingkat'] == 'internasional' ? 'selected' : '' ?>>Internasional</option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Tahun</label>
                        <input type="number" name="tahun" min="2000" max="2100" value="<?= $prestasi['tahun'] ?>" required class="w-full px-4 py-3.5 border border-slate-300 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition">
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Deskripsi</label>
                        <textarea name="deskripsi" rows="5" class="w-full px-4 py-3.5 border border-slate-300 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition resize-y"><?= htmlspecialchars($prestasi['deskripsi']) ?></textarea>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Gambar Saat Ini</label>
                        <?php if ($prestasi['gambar']) : ?>
                            <div class="mb-3"><img src="../../uploads/<?= $prestasi['gambar'] ?>" class="w-48 h-32 object-cover rounded-xl border border-slate-200"></div>
                        <?php else : ?>
                            <p class="text-sm text-slate-400">Tidak ada gambar</p>
                        <?php endif; ?>
                        <label class="block text-sm font-semibold text-slate-700 mb-2 mt-4">Ganti Gambar (jika ada)</label>
                        <label for="gambar" class="block border-2 border-dashed border-slate-300 hover:border-primary rounded-2xl p-7 text-center cursor-pointer transition bg-slate-50 hover:bg-blue-50/40">
                            <span class="material-icons text-4xl text-primary">cloud_upload</span>
                            <p class="font-semibold text-slate-700 mt-2">Klik untuk memilih gambar</p>
                            <p class="text-xs text-slate-400 mt-1">JPG, JPEG, PNG atau WEBP • Maksimal 5MB</p>
                            <p id="file-name" class="text-sm text-primary font-semibold mt-3 hidden"></p>
                            <input id="gambar" type="file" name="gambar" accept="image/jpeg,image/png,image/jpg,image/webp" class="hidden">
                        </label>
                    </div>

                    <div class="flex items-center gap-4 pt-4">
                        <button type="submit" class="flex items-center justify-center gap-2 px-7 py-3.5 bg-primary hover:bg-blue-900 text-white rounded-xl font-semibold text-sm transition shadow-lg shadow-blue-900/20">
                            <span class="material-icons text-lg">save</span>
                            Simpan Perubahan
                        </button>
                        <a href="index.php" class="flex items-center justify-center gap-2 px-6 py-3.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl font-semibold text-sm transition">
                            <span class="material-icons text-lg">close</span>
                            Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </main>

    <script>
        const gambarInput = document.getElementById('gambar');
        const fileName = document.getElementById('file-name');
        gambarInput.addEventListener('change', function () {
            if (this.files.length > 0) {
                fileName.textContent = "File dipilih: " + this.files[0].name;
                fileName.classList.remove('hidden');
            } else {
                fileName.classList.add('hidden');
            }
        });
    </script>
</body>
</html>