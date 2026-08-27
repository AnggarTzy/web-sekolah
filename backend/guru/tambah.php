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
    <title>Tambah Guru | Admin</title>
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
                <h1 class="font-headline font-extrabold text-2xl text-primary">Tambah Guru</h1>
            </div>
            <div class="p-6">
                <?php if (isset($error)) : ?>
                    <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl mb-6 text-sm"><?= $error ?></div>
                <?php endif; ?>

                <form method="POST" enctype="multipart/form-data" class="space-y-6">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Nama Lengkap</label>
                        <input type="text" name="nama" required class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary focus:border-primary transition outline-none" placeholder="Masukkan nama lengkap guru">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Jabatan</label>
                        <input type="text" name="jabatan" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary focus:border-primary transition outline-none" placeholder="Contoh: Guru Matematika">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Bidang Studi</label>
                        <input type="text" name="bidang_studi" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary focus:border-primary transition outline-none" placeholder="Contoh: Matematika">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Email</label>
                        <input type="email" name="email" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary focus:border-primary transition outline-none" placeholder="Contoh: guru@sekolah.sch.id">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Foto</label>
                        <input type="file" name="foto" accept="image/*" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary focus:border-primary transition outline-none">
                    </div>
                    <div class="flex items-center gap-4 pt-4">
                        <button type="submit" class="px-6 py-3 bg-primary text-white rounded-xl font-semibold text-sm hover:bg-opacity-90 transition shadow-lg">Simpan</button>
                        <a href="index.php" class="px-6 py-3 bg-gray-100 text-gray-700 rounded-xl font-semibold text-sm hover:bg-gray-200 transition">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </main>
</body>
</html>