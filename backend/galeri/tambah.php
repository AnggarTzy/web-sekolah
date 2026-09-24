<?php
session_start();

if (!isset($_SESSION['login'])) {
    header("Location: ../admin/login.php");
    exit;
}

include '../config/koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $judul = mysqli_real_escape_string($conn, $_POST['judul']);
    $tentang = mysqli_real_escape_string($conn, $_POST['tentang']);
    $tanggal = mysqli_real_escape_string($conn, $_POST['tanggal']);

    $gambar = null;

    if (!empty($_FILES['gambar']['name'])) {

        $allowed_types = ['image/jpeg', 'image/png', 'image/jpg', 'image/webp'];
        $file_type = mime_content_type($_FILES['gambar']['tmp_name']);
        $max_size = 5 * 1024 * 1024;

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

    } else {
        $error = "Foto kegiatan wajib diupload.";
    }

    if (!isset($error)) {
        $query = mysqli_query(
            $conn,
            "INSERT INTO galeri (judul, tentang, gambar, tanggal)
             VALUES ('$judul', '$tentang', '$gambar', '$tanggal')"
        );

        if ($query) {
            catat_aktivitas($conn, 'Galeri', 'Menambah', $judul);
            header("Location: index.php?status=created");
            exit;
        } else {
            $error = "Gagal menyimpan galeri: " . mysqli_error($conn);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Galeri | Admin SMP Muhammadiyah 6 Krian</title>

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
<body class="bg-slate-50 font-sans text-slate-700">

    <nav class="bg-primary text-white shadow-lg sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-accent rounded-xl flex items-center justify-center font-headline font-extrabold text-primary text-lg shadow">S</div>
                    <div>
                        <p class="font-headline font-bold text-lg">Admin Panel</p>
                        <p class="text-xs text-white/60">SMP Muhammadiyah 6 Krian</p>
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    <a href="index.php" class="flex items-center gap-2 px-4 py-2 bg-white/10 hover:bg-white/20 rounded-xl text-sm font-semibold transition">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M19 12H5"></path>
                            <path d="M12 19l-7-7 7-7"></path>
                        </svg>
                        Kembali
                    </a>
                    <a href="../admin/logout.php" class="flex items-center gap-2 px-4 py-2 bg-red-500 hover:bg-red-600 rounded-xl text-sm font-semibold transition">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                            <polyline points="16 17 21 12 16 7"></polyline>
                            <line x1="21" y1="12" x2="9" y2="12"></line>
                        </svg>
                        Logout
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <main class="max-w-5xl mx-auto px-4 sm:px-6 py-8">

        <div class="mb-7">
            <div class="flex items-center gap-3">
                <div class="w-11 h-11 bg-blue-100 text-primary rounded-xl flex items-center justify-center">
                    <span class="material-icons">add_photo_alternate</span>
                </div>
                <div>
                    <h1 class="font-headline font-extrabold text-2xl sm:text-3xl text-primary">Tambah Galeri</h1>
                    <p class="text-sm text-slate-500">Tambahkan dokumentasi kegiatan sekolah.</p>
                </div>
            </div>
        </div>

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

            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                <div class="px-6 py-5 border-b border-slate-100">
                    <h2 class="font-headline font-bold text-lg text-slate-800">Informasi Kegiatan</h2>
                    <p class="text-sm text-slate-500 mt-1">Masukkan informasi kegiatan yang didokumentasikan.</p>
                </div>

                <div class="p-6 space-y-6">

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Nama Kegiatan</label>
                        <input type="text" name="judul" required
                               class="w-full px-4 py-3.5 border border-slate-300 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none"
                               placeholder="Contoh: Upacara HUT Kemerdekaan RI">
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Tentang</label>
                        <textarea name="tentang" rows="5"
                                  class="w-full px-4 py-3.5 border border-slate-300 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none resize-y"
                                  placeholder="Tentang kegiatan..."></textarea>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Tanggal Kegiatan</label>
                        <input type="date" name="tanggal" required value="<?= date('Y-m-d') ?>"
                               class="w-full px-4 py-3.5 border border-slate-300 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none">
                    </div>

                </div>
            </div>

            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                <div class="px-6 py-5 border-b border-slate-100">
                    <h2 class="font-headline font-bold text-lg text-slate-800">Foto Kegiatan</h2>
                    <p class="text-sm text-slate-500 mt-1">Upload satu foto untuk kegiatan ini.</p>
                </div>

                <div class="p-6">
                    <label for="gambar"
                           class="block border-2 border-dashed border-slate-300 hover:border-primary rounded-2xl p-7 text-center cursor-pointer transition bg-slate-50 hover:bg-blue-50/40">
                        <span class="material-icons text-4xl text-primary">cloud_upload</span>
                        <p class="font-semibold text-slate-700 mt-2">Klik untuk memilih foto</p>
                        <p class="text-xs text-slate-400 mt-1">JPG, JPEG, PNG atau WEBP • Maksimal 5MB</p>
                        <p id="file-name" class="text-sm text-primary font-semibold mt-3 hidden"></p>
                        <input id="gambar" type="file" name="gambar" accept="image/jpeg,image/png,image/jpg,image/webp" class="hidden" required>
                    </label>
                </div>
            </div>

            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-5">
                <div class="flex flex-col-reverse sm:flex-row sm:justify-between gap-3">
                    <a href="index.php"
                       class="flex items-center justify-center gap-2 px-6 py-3.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl font-semibold text-sm">
                        <span class="material-icons">close</span>
                        Batal
                    </a>
                    <button type="submit"
                            class="flex items-center justify-center gap-2 px-7 py-3.5 bg-primary hover:bg-blue-900 text-white rounded-xl font-semibold text-sm shadow-lg">
                        <span class="material-icons">save</span>
                        Simpan Galeri
                    </button>
                </div>
            </div>

        </form>

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