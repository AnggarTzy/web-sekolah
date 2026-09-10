<?php
session_start();

if (!isset($_SESSION['login'])) {
    header("Location: ../admin/login.php");
    exit;
}

include '../config/koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = mysqli_real_escape_string($conn, $_POST['nama']);
    $jabatan = mysqli_real_escape_string($conn, $_POST['jabatan']);
    $bidang_studi = mysqli_real_escape_string($conn, $_POST['bidang_studi']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);

    $foto = null;
    if (!empty($_FILES['foto']['name'])) {
        $target_dir = "../../uploads/";
        if (!is_dir($target_dir)) mkdir($target_dir, 0777, true);
        $file_name = time() . "_" . basename($_FILES['foto']['name']);
        if (move_uploaded_file($_FILES['foto']['tmp_name'], $target_dir . $file_name)) {
            $foto = $file_name;
        }
    }

    $query = mysqli_query($conn, "INSERT INTO guru (nama, jabatan, bidang_studi, foto, email) 
                                  VALUES ('$nama', '$jabatan', '$bidang_studi', '$foto', '$email')");

    if ($query) {
        header("Location: index.php");
        exit;
    } else {
        $error = "Gagal menyimpan: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Guru | Admin SMP Muhammadiyah 6 Krian</title>

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

    <!-- NAVBAR -->
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

    <!-- KONTEN -->
    <main class="max-w-4xl mx-auto px-4 sm:px-6 py-8">
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="px-6 py-5 border-b border-slate-100">
                <div class="flex items-center gap-3">
                    <div class="w-11 h-11 bg-green-100 text-primary rounded-xl flex items-center justify-center">
                        <span class="material-icons">add_circle</span>
                    </div>
                    <div>
                        <h1 class="font-headline font-extrabold text-2xl text-primary">Tambah Guru</h1>
                        <p class="text-sm text-slate-500">Lengkapi informasi guru di bawah ini.</p>
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
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Nama Lengkap</label>
                        <div class="relative">
                            <span class="material-icons absolute left-4 top-1/2 -translate-y-1/2 text-slate-400">person</span>
                            <input type="text" name="nama" required class="w-full pl-12 pr-4 py-3.5 border border-slate-300 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition" placeholder="Masukkan nama lengkap guru">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Jabatan</label>
                        <div class="relative">
                            <span class="material-icons absolute left-4 top-1/2 -translate-y-1/2 text-slate-400">work</span>
                            <input type="text" name="jabatan" class="w-full pl-12 pr-4 py-3.5 border border-slate-300 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition" placeholder="Contoh: Guru Matematika">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Bidang Studi</label>
                        <div class="relative">
                            <span class="material-icons absolute left-4 top-1/2 -translate-y-1/2 text-slate-400">book</span>
                            <input type="text" name="bidang_studi" class="w-full pl-12 pr-4 py-3.5 border border-slate-300 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition" placeholder="Contoh: Matematika">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Email</label>
                        <div class="relative">
                            <span class="material-icons absolute left-4 top-1/2 -translate-y-1/2 text-slate-400">mail</span>
                            <input type="email" name="email" class="w-full pl-12 pr-4 py-3.5 border border-slate-300 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition" placeholder="Contoh: guru@sekolah.sch.id">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Foto</label>
                        <label for="foto" class="block border-2 border-dashed border-slate-300 hover:border-primary rounded-2xl p-7 text-center cursor-pointer transition bg-slate-50 hover:bg-blue-50/40">
                            <span class="material-icons text-4xl text-primary">cloud_upload</span>
                            <p class="font-semibold text-slate-700 mt-2">Klik untuk memilih foto</p>
                            <p class="text-xs text-slate-400 mt-1">JPG, JPEG, PNG atau WEBP • Maksimal 5MB</p>
                            <p id="file-name" class="text-sm text-primary font-semibold mt-3 hidden"></p>
                            <input id="foto" type="file" name="foto" accept="image/jpeg,image/png,image/jpg,image/webp" class="hidden">
                        </label>
                    </div>

                    <div class="flex items-center gap-4 pt-4">
                        <button type="submit" class="flex items-center justify-center gap-2 px-7 py-3.5 bg-primary hover:bg-blue-900 text-white rounded-xl font-semibold text-sm transition shadow-lg shadow-blue-900/20">
                            <span class="material-icons text-lg">save</span>
                            Simpan Guru
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
        const fotoInput = document.getElementById('foto');
        const fileName = document.getElementById('file-name');
        fotoInput.addEventListener('change', function () {
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