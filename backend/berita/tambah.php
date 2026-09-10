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
    $status = mysqli_real_escape_string($conn, $_POST['status']);

    // Buat slug dari judul
    $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $judul)));
    $slug = trim($slug, '-');

    // Upload gambar
    $gambar = null;
    if (!empty($_FILES['gambar']['name'])) {

        // Validasi ukuran & tipe file
        $allowed_types = ['image/jpeg', 'image/png', 'image/jpg', 'image/webp'];
        $file_type = mime_content_type($_FILES['gambar']['tmp_name']);
        $max_size = 5 * 1024 * 1024; // 5MB

        if (in_array($file_type, $allowed_types)) {
            if ($_FILES['gambar']['size'] <= $max_size) {
                $target_dir = "../../uploads/";
                if (!is_dir($target_dir)) {
                    mkdir($target_dir, 0777, true);
                }
                
                $extension = strtolower(pathinfo($_FILES['gambar']['name'], PATHINFO_EXTENSION));
                $file_name = time() . '_' . uniqid() . '.' . $extension;
                $target_file = $target_dir . $file_name;

                if (move_uploaded_file($_FILES['gambar']['tmp_name'], $target_file)) {
                    $gambar = $file_name;
                } else {
                    $error = "Gagal mengupload gambar.";
                }
            } else {
                $error = "Ukuran gambar terlalu besar. Maksimal 5MB.";
            }
        } else {
            $error = "Format gambar tidak diperbolehkan. Gunakan JPG, JPEG, PNG, atau WEBP.";
        }
    }

    // Simpan ke database jika tidak ada error
    if (!isset($error)) {
        $query = mysqli_query($conn, "INSERT INTO berita (judul, slug, konten, gambar, kategori, penulis, status) 
                                      VALUES ('$judul', '$slug', '$konten', '$gambar', '$kategori', '$penulis', '$status')");

        if ($query) {
            header("Location: index.php?status=created");
            exit;
        } else {
            $error = "Gagal menyimpan berita: " . mysqli_error($conn);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Berita | Admin SMP Muhammadiyah 6 Krian</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@500;600;700;800&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">

    <!-- Material Icons -->
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
<body class="bg-slate-50 font-sans text-slate-700 antialiased">

<!-- NAVBAR -->
<nav class="bg-primary text-white shadow-lg sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">
            <!-- Logo -->
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-accent rounded-xl flex items-center justify-center font-headline font-extrabold text-primary text-lg shadow">
                    S
                </div>
                <div>
                    <p class="font-headline font-bold text-lg leading-tight">Admin Panel</p>
                    <p class="text-xs text-white/60">SMP Muhammadiyah 6 Krian</p>
                </div>
            </div>
            <!-- Menu -->
            <div class="flex items-center gap-2 sm:gap-3">
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

<!-- CONTENT -->
<main class="max-w-5xl mx-auto px-4 sm:px-6 py-8">

    <!-- Header -->
    <div class="mb-7">
        <div class="flex items-center gap-3 mb-2">
            <div class="w-11 h-11 bg-blue-100 text-primary rounded-xl flex items-center justify-center">
                <span class="material-icons">add_circle</span>
            </div>
            <div>
                <h1 class="font-headline font-extrabold text-2xl sm:text-3xl text-primary">Tambah Berita</h1>
                <p class="text-sm text-slate-500">Lengkapi informasi berita di bawah ini.</p>
            </div>
        </div>
    </div>

    <!-- Error -->
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

        <!-- INFORMASI UTAMA -->
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="px-6 py-5 border-b border-slate-100">
                <h2 class="font-headline font-bold text-lg text-slate-800">Informasi Berita</h2>
                <p class="text-sm text-slate-500 mt-1">Masukkan informasi utama dari berita.</p>
            </div>

            <div class="p-6 space-y-6">

                <!-- Judul -->
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Judul Berita</label>
                    <input type="text" name="judul" required
                           class="w-full px-4 py-3.5 border border-slate-300 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition"
                           placeholder="Masukkan judul berita">
                </div>

                <!-- Kategori + Status -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <!-- Kategori -->
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Kategori</label>
                        <select name="kategori"
                                class="w-full px-4 py-3.5 border border-slate-300 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition">
                            <option value="umum">Umum</option>
                            <option value="akademik">Akademik</option>
                            <option value="kegiatan">Kegiatan</option>
                            <option value="prestasi">Prestasi</option>
                        </select>
                    </div>

                    <!-- Status -->
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Status Berita</label>
                        <select name="status"
                                class="w-full px-4 py-3.5 border border-slate-300 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition">
                            <option value="publish">Publish</option>
                            <option value="draft">Draft</option>
                        </select>
                    </div>
                </div>

                <!-- Penulis -->
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Penulis</label>
                    <div class="relative">
                        <span class="material-icons absolute left-4 top-1/2 -translate-y-1/2 text-slate-400">person</span>
                        <input type="text" name="penulis" value="Admin" required
                               class="w-full pl-12 pr-4 py-3.5 border border-slate-300 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition">
                    </div>
                </div>

                <!-- Konten -->
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <label class="block text-sm font-semibold text-slate-700">Konten Berita</label>
                        <span class="text-xs text-slate-400">Isi berita secara lengkap</span>
                    </div>
                    <textarea name="konten" rows="10" required
                              class="w-full px-4 py-3.5 border border-slate-300 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition resize-y"
                              placeholder="Tulis isi berita di sini..."></textarea>
                </div>

            </div>
        </div>

        <!-- GAMBAR -->
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="px-6 py-5 border-b border-slate-100">
                <h2 class="font-headline font-bold text-lg text-slate-800">Gambar Berita</h2>
                <p class="text-sm text-slate-500 mt-1">Gunakan gambar yang relevan dengan isi berita.</p>
            </div>

            <div class="p-6">
                <div>
                    <p class="text-sm font-semibold text-slate-700 mb-3">Upload Gambar</p>
                    <label for="gambar"
                           class="block border-2 border-dashed border-slate-300 hover:border-primary rounded-2xl p-7 text-center cursor-pointer transition bg-slate-50 hover:bg-blue-50/40">
                        <span class="material-icons text-4xl text-primary">cloud_upload</span>
                        <p class="font-semibold text-slate-700 mt-2">Klik untuk memilih gambar</p>
                        <p class="text-xs text-slate-400 mt-1">JPG, JPEG, PNG atau WEBP • Maksimal 5MB</p>
                        <p id="file-name" class="text-sm text-primary font-semibold mt-3 hidden"></p>
                        <input id="gambar" type="file" name="gambar" accept="image/jpeg,image/png,image/jpg,image/webp" class="hidden">
                    </label>
                </div>
            </div>
        </div>

        <!-- BUTTON -->
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-5">
            <div class="flex flex-col-reverse sm:flex-row sm:justify-between gap-3">
                <a href="index.php"
                   class="flex items-center justify-center gap-2 px-6 py-3.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl font-semibold text-sm transition">
                    <span class="material-icons text-lg">close</span>
                    Batal
                </a>
                <button type="submit"
                        class="flex items-center justify-center gap-2 px-7 py-3.5 bg-primary hover:bg-blue-900 text-white rounded-xl font-semibold text-sm transition shadow-lg shadow-blue-900/20">
                    <span class="material-icons text-lg">save</span>
                    Simpan Berita
                </button>
            </div>
        </div>

    </form>

</main>

<script>
    // Menampilkan nama file ketika memilih gambar
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