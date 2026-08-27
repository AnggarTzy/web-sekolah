<?php
session_start();

if (!isset($_SESSION['login'])) {
    header("Location: ../admin/login.php");
    exit;
}

include '../config/koneksi.php';

// Ambil ID dari URL
$id = $_GET['id'] ?? 0;
$id = (int) $id;

// Ambil data berita
$result = mysqli_query($conn, "SELECT * FROM berita WHERE id = '$id'");
$berita = mysqli_fetch_assoc($result);

// Jika berita tidak ditemukan
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
    $status = mysqli_real_escape_string($conn, $_POST['status']);

    // Buat slug
    $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $judul)));
    $slug = trim($slug, '-');

    // Gambar lama
    $gambar_lama = $berita['gambar'];
    $gambar = $gambar_lama;

    // Upload gambar baru
    if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] !== UPLOAD_ERR_NO_FILE) {

        if ($_FILES['gambar']['error'] === UPLOAD_ERR_OK) {

            $target_dir = "../../uploads/";

            if (!is_dir($target_dir)) {
                mkdir($target_dir, 0777, true);
            }

            $allowed_types = ['image/jpeg', 'image/png', 'image/jpg', 'image/webp'];

            $file_type = mime_content_type($_FILES['gambar']['tmp_name']);

            if (in_array($file_type, $allowed_types)) {

                // Maksimal 5MB
                if ($_FILES['gambar']['size'] <= 5 * 1024 * 1024) {

                    $extension = strtolower(
                        pathinfo($_FILES['gambar']['name'], PATHINFO_EXTENSION)
                    );

                    $file_name = time() . '_' . uniqid() . '.' . $extension;
                    $target_file = $target_dir . $file_name;

                    if (move_uploaded_file($_FILES['gambar']['tmp_name'], $target_file)) {

                        // Hapus gambar lama
                        if (
                            !empty($gambar_lama) &&
                            file_exists($target_dir . $gambar_lama)
                        ) {
                            unlink($target_dir . $gambar_lama);
                        }

                        $gambar = $file_name;
                    }

                } else {
                    $error = "Ukuran gambar terlalu besar. Maksimal 5MB.";
                }

            } else {
                $error = "Format gambar tidak diperbolehkan. Gunakan JPG, JPEG, PNG, atau WEBP.";
            }

        } else {
            $error = "Terjadi kesalahan saat mengupload gambar.";
        }
    }

    // Update database jika tidak ada error
    if (!isset($error)) {

        $query = mysqli_query($conn, "UPDATE berita SET 
            judul = '$judul',
            slug = '$slug',
            konten = '$konten',
            gambar = '$gambar',
            kategori = '$kategori',
            penulis = '$penulis',
            status = '$status'
            WHERE id = '$id'
        ");

        if ($query) {
            header("Location: index.php?status=updated");
            exit;
        } else {
            $error = "Gagal mengupdate berita: " . mysqli_error($conn);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Edit Berita | Admin SMP Muhammadiyah 6 Krian</title>

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
                    <p class="font-headline font-bold text-lg leading-tight">
                        Admin Panel
                    </p>

                    <p class="text-xs text-white/60">
                        SMP Muhammadiyah 6 Krian
                    </p>
                </div>

            </div>

            <!-- Menu -->
            <div class="flex items-center gap-2 sm:gap-3">

                <a href="index.php"
                   class="flex items-center gap-2 px-4 py-2 bg-white/10 hover:bg-white/20 rounded-xl text-sm font-semibold transition">

                    <span class="material-icons text-base">
                        arrow_back
                    </span>

                    <span class="hidden sm:inline">
                        Kembali
                    </span>

                </a>

                <a href="../admin/logout.php"
                   class="flex items-center gap-2 px-4 py-2 bg-red-500 hover:bg-red-600 rounded-xl text-sm font-semibold transition">

                    <span class="material-icons text-base">
                        logout
                    </span>

                    <span class="hidden sm:inline">
                        Logout
                    </span>

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

                <span class="material-icons">
                    edit
                </span>

            </div>

            <div>

                <h1 class="font-headline font-extrabold text-2xl sm:text-3xl text-primary">
                    Edit Berita
                </h1>

                <p class="text-sm text-slate-500">
                    Perbarui informasi berita yang sudah ada.
                </p>

            </div>

        </div>

    </div>


    <!-- Error -->
    <?php if (isset($error)) : ?>

        <div class="mb-6 flex items-start gap-3 bg-red-50 border border-red-200 text-red-700 px-5 py-4 rounded-2xl">

            <span class="material-icons">
                error_outline
            </span>

            <div>
                <p class="font-semibold">
                    Terjadi Kesalahan
                </p>

                <p class="text-sm mt-1">
                    <?= htmlspecialchars($error) ?>
                </p>
            </div>

        </div>

    <?php endif; ?>


    <form method="POST"
          enctype="multipart/form-data"
          class="space-y-6">


        <!-- INFORMASI UTAMA -->
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">

            <div class="px-6 py-5 border-b border-slate-100">

                <h2 class="font-headline font-bold text-lg text-slate-800">
                    Informasi Berita
                </h2>

                <p class="text-sm text-slate-500 mt-1">
                    Masukkan informasi utama dari berita.
                </p>

            </div>


            <div class="p-6 space-y-6">

                <!-- Judul -->
                <div>

                    <label class="block text-sm font-semibold text-slate-700 mb-2">
                        Judul Berita
                    </label>

                    <input
                        type="text"
                        name="judul"
                        value="<?= htmlspecialchars($berita['judul']) ?>"
                        required
                        class="w-full px-4 py-3.5 border border-slate-300 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition"
                        placeholder="Masukkan judul berita">

                </div>


                <!-- Kategori + Status -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">

                    <!-- Kategori -->
                    <div>

                        <label class="block text-sm font-semibold text-slate-700 mb-2">
                            Kategori
                        </label>

                        <select
                            name="kategori"
                            class="w-full px-4 py-3.5 border border-slate-300 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition">

                            <option value="umum" <?= $berita['kategori'] == 'umum' ? 'selected' : '' ?>>
                                Umum
                            </option>

                            <option value="akademik" <?= $berita['kategori'] == 'akademik' ? 'selected' : '' ?>>
                                Akademik
                            </option>

                            <option value="kegiatan" <?= $berita['kategori'] == 'kegiatan' ? 'selected' : '' ?>>
                                Kegiatan
                            </option>

                            <option value="prestasi" <?= $berita['kategori'] == 'prestasi' ? 'selected' : '' ?>>
                                Prestasi
                            </option>

                        </select>

                    </div>


                    <!-- Status -->
                    <div>

                        <label class="block text-sm font-semibold text-slate-700 mb-2">
                            Status Berita
                        </label>

                        <select
                            name="status"
                            class="w-full px-4 py-3.5 border border-slate-300 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition">

                            <option value="publish" <?= $berita['status'] == 'publish' ? 'selected' : '' ?>>
                                Publish
                            </option>

                            <option value="draft" <?= $berita['status'] == 'draft' ? 'selected' : '' ?>>
                                Draft
                            </option>

                        </select>

                    </div>

                </div>


                <!-- Penulis -->
                <div>

                    <label class="block text-sm font-semibold text-slate-700 mb-2">
                        Penulis
                    </label>

                    <div class="relative">

                        <span class="material-icons absolute left-4 top-1/2 -translate-y-1/2 text-slate-400">
                            person
                        </span>

                        <input
                            type="text"
                            name="penulis"
                            value="<?= htmlspecialchars($berita['penulis']) ?>"
                            required
                            class="w-full pl-12 pr-4 py-3.5 border border-slate-300 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition">

                    </div>

                </div>


                <!-- Konten -->
                <div>

                    <div class="flex items-center justify-between mb-2">

                        <label class="block text-sm font-semibold text-slate-700">
                            Konten Berita
                        </label>

                        <span class="text-xs text-slate-400">
                            Isi berita secara lengkap
                        </span>

                    </div>

                    <textarea
                        name="konten"
                        rows="10"
                        required
                        class="w-full px-4 py-3.5 border border-slate-300 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition resize-y"
                        placeholder="Tulis isi berita di sini..."><?= htmlspecialchars($berita['konten']) ?></textarea>

                </div>

            </div>

        </div>


        <!-- GAMBAR -->
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">

            <div class="px-6 py-5 border-b border-slate-100">

                <h2 class="font-headline font-bold text-lg text-slate-800">
                    Gambar Berita
                </h2>

                <p class="text-sm text-slate-500 mt-1">
                    Gunakan gambar yang relevan dengan isi berita.
                </p>

            </div>


            <div class="p-6">

                <!-- Gambar lama -->
                <?php if (!empty($berita['gambar'])) : ?>

                    <div class="mb-6">

                        <p class="text-sm font-semibold text-slate-700 mb-3">
                            Gambar Saat Ini
                        </p>

                        <div class="relative group max-w-md">

                            <img
                                src="../../uploads/<?= htmlspecialchars($berita['gambar']) ?>"
                                alt="Gambar berita"
                                class="w-full h-56 object-cover rounded-2xl border border-slate-200 shadow-sm">

                            <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/60 to-transparent rounded-b-2xl p-4">

                                <p class="text-white text-xs truncate">
                                    <?= htmlspecialchars($berita['gambar']) ?>
                                </p>

                            </div>

                        </div>

                    </div>

                <?php else : ?>

                    <div class="mb-6 p-6 bg-slate-50 rounded-2xl border border-dashed border-slate-300 text-center">

                        <span class="material-icons text-4xl text-slate-300">
                            image
                        </span>

                        <p class="text-sm text-slate-500 mt-2">
                            Berita ini belum memiliki gambar.
                        </p>

                    </div>

                <?php endif; ?>


                <!-- Upload baru -->
                <div>

                    <p class="text-sm font-semibold text-slate-700 mb-3">
                        Ganti Gambar
                    </p>

                    <label
                        for="gambar"
                        class="block border-2 border-dashed border-slate-300 hover:border-primary rounded-2xl p-7 text-center cursor-pointer transition bg-slate-50 hover:bg-blue-50/40">

                        <span class="material-icons text-4xl text-primary">
                            cloud_upload
                        </span>

                        <p class="font-semibold text-slate-700 mt-2">
                            Klik untuk memilih gambar
                        </p>

                        <p class="text-xs text-slate-400 mt-1">
                            JPG, JPEG, PNG atau WEBP • Maksimal 5MB
                        </p>

                        <p id="file-name" class="text-sm text-primary font-semibold mt-3 hidden"></p>

                        <input
                            id="gambar"
                            type="file"
                            name="gambar"
                            accept="image/jpeg,image/png,image/jpg,image/webp"
                            class="hidden">

                    </label>

                </div>

            </div>

        </div>


        <!-- BUTTON -->
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-5">

            <div class="flex flex-col-reverse sm:flex-row sm:justify-between gap-3">

                <a
                    href="index.php"
                    class="flex items-center justify-center gap-2 px-6 py-3.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl font-semibold text-sm transition">

                    <span class="material-icons text-lg">
                        close
                    </span>

                    Batal

                </a>


                <button
                    type="submit"
                    class="flex items-center justify-center gap-2 px-7 py-3.5 bg-primary hover:bg-blue-900 text-white rounded-xl font-semibold text-sm transition shadow-lg shadow-blue-900/20">

                    <span class="material-icons text-lg">
                        save
                    </span>

                    Simpan Perubahan

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