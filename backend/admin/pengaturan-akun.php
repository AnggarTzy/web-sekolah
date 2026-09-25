<?php
session_start();

include '../config/koneksi.php';

cek_login();

$username = $_SESSION['username'] ?? 'Admin';
$admin_id = $_SESSION['admin_id'] ?? 0;

$error = $_GET['error'] ?? '';
$success = $_GET['success'] ?? '';

$error_messages = [
    'empty'    => 'Semua field wajib diisi!',
    'wrong'    => 'Password lama salah!',
    'weak'     => 'Password baru minimal 8 karakter!',
    'match'    => 'Password baru dan konfirmasi tidak cocok!',
    'same'     => 'Password baru tidak boleh sama dengan password lama!',
    'username' => 'Username sudah dipakai admin lain!',
    'server'   => 'Terjadi kesalahan pada server.',
];
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengaturan Akun | Admin Panel</title>

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

    <style>
        .material-icons { font-size: 20px; vertical-align: middle; }
    </style>
</head>
<body class="bg-gray-50 font-sans text-slate-700 antialiased">

<!-- NAVBAR -->
<nav class="bg-primary text-white shadow-lg sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-accent rounded-lg flex items-center justify-center font-headline font-bold text-primary text-lg">S</div>
                <span class="font-headline font-bold text-lg">Admin Panel</span>
            </div>
            <div class="flex items-center gap-4">
                <span class="text-sm text-white/70 hidden sm:block">
                    Welcome, <span class="font-bold text-white"><?= htmlspecialchars($username) ?></span>
                </span>
                <a href="logout.php" class="px-4 py-2 bg-red-500 hover:bg-red-600 rounded-lg text-sm font-semibold transition">Logout</a>
            </div>
        </div>
    </div>
</nav>

<div class="flex min-h-screen">

    <!-- SIDEBAR -->
    <aside class="w-64 bg-white border-r border-gray-200 hidden md:flex flex-col fixed h-full pt-6">
        <div class="px-6 space-y-2 overflow-y-auto">
            <a href="dashboard.php" class="flex items-center gap-3 px-4 py-3 rounded-xl text-gray-600 hover:bg-gray-50 hover:text-primary font-semibold text-sm transition">
                <span class="material-icons">dashboard</span> Dashboard
            </a>
            <a href="../berita/index.php" class="flex items-center gap-3 px-4 py-3 rounded-xl text-gray-600 hover:bg-gray-50 hover:text-primary font-semibold text-sm transition">
                <span class="material-icons">article</span> Kelola Berita
            </a>
            <a href="../prestasi/index.php" class="flex items-center gap-3 px-4 py-3 rounded-xl text-gray-600 hover:bg-gray-50 hover:text-primary font-semibold text-sm transition">
                <span class="material-icons">emoji_events</span> Kelola Prestasi
            </a>
            <a href="../guru/index.php" class="flex items-center gap-3 px-4 py-3 rounded-xl text-gray-600 hover:bg-gray-50 hover:text-primary font-semibold text-sm transition">
                <span class="material-icons">groups</span> Kelola Guru
            </a>
            <a href="../fasilitas/index.php" class="flex items-center gap-3 px-4 py-3 rounded-xl text-gray-600 hover:bg-gray-50 hover:text-primary font-semibold text-sm transition">
                <span class="material-icons">apartment</span> Kelola Fasilitas
            </a>
            <a href="../ekstrakurikuler/index.php" class="flex items-center gap-3 px-4 py-3 rounded-xl text-gray-600 hover:bg-gray-50 hover:text-primary font-semibold text-sm transition">
                <span class="material-icons">sports_kabaddi</span> Kelola Ekstrakurikuler
            </a>
            <a href="../galeri/index.php" class="flex items-center gap-3 px-4 py-3 rounded-xl text-gray-600 hover:bg-gray-50 hover:text-primary font-semibold text-sm transition">
                <span class="material-icons">photo_library</span> Kelola Galeri
            </a>

            <!-- DIVIDER -->
            <div class="border-t border-gray-200 my-3"></div>

            <!-- PENGATURAN AKUN -->
            <a href="pengaturan-akun.php" class="flex items-center gap-3 px-4 py-3 rounded-xl bg-primary text-white font-semibold text-sm transition">
                <span class="material-icons">settings</span> Pengaturan Akun
            </a>
        </div>
    </aside>

    <!-- KONTEN -->
    <main class="flex-1 md:ml-64 p-6 sm:p-8">

        <div class="mb-8">
            <h1 class="font-headline font-extrabold text-3xl text-primary">Pengaturan Akun</h1>
            <p class="text-gray-500 mt-1">Ganti username dan password akun Anda.</p>
        </div>

        <!-- NOTIFIKASI -->
        <?php if ($error && isset($error_messages[$error])): ?>
            <div class="mb-6 flex items-start gap-3 bg-red-50 border border-red-200 text-red-700 px-5 py-4 rounded-2xl">
                <span class="material-icons">error_outline</span>
                <div>
                    <p class="font-semibold">Terjadi Kesalahan</p>
                    <p class="text-sm mt-1"><?= htmlspecialchars($error_messages[$error]) ?></p>
                </div>
            </div>
        <?php endif; ?>

        <?php if ($success): ?>
            <div class="mb-6 flex items-start gap-3 bg-green-50 border border-green-200 text-green-700 px-5 py-4 rounded-2xl">
                <span class="material-icons">check_circle</span>
                <div>
                    <p class="font-semibold">Berhasil!</p>
                    <p class="text-sm mt-1">
                        <?php if ($success === 'username'): ?>
                            Username berhasil diubah.
                        <?php elseif ($success === 'password'): ?>
                            Password berhasil diubah. Gunakan password baru saat login berikutnya.
                        <?php elseif ($success === 'both'): ?>
                            Username dan password berhasil diubah.
                        <?php else: ?>
                            Data berhasil diperbarui.
                        <?php endif; ?>
                    </p>
                </div>
            </div>
        <?php endif; ?>

        <!-- FORM -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden max-w-2xl">

            <div class="px-6 py-4 border-b border-gray-100">
                <h2 class="font-headline font-bold text-lg text-primary">Informasi Akun</h2>
                <p class="text-sm text-gray-500 mt-1">Perbarui username dan password Anda.</p>
            </div>

            <form method="POST" action="proses_pengaturan_akun.php" class="p-6 space-y-5">

                <!-- USERNAME LAMA -->
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Username Saat Ini</label>
                    <input type="text" value="<?= htmlspecialchars($username) ?>" disabled
                        class="w-full px-4 py-3 border border-gray-200 rounded-xl bg-gray-50 text-gray-500 cursor-not-allowed">
                </div>

                <!-- USERNAME BARU -->
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Username Baru</label>
                    <div class="relative">
                        <span class="material-icons absolute left-4 top-1/2 -translate-y-1/2 text-slate-400">person</span>
                        <input type="text" name="new_username" value="<?= htmlspecialchars($username) ?>" required
                            class="w-full pl-12 pr-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition">
                    </div>
                    <p class="text-xs text-slate-400 mt-1">Biarkan sama kalau tidak ingin ganti username.</p>
                </div>

                <!-- DIVIDER -->
                <div class="border-t border-gray-100 pt-5">
                    <h3 class="font-headline font-bold text-sm text-primary mb-1">Ganti Password</h3>
                    <p class="text-xs text-gray-500">Kosongkan kalau tidak ingin ganti password.</p>
                </div>

                <!-- PASSWORD LAMA -->
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">
                        Password Lama
                        <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <span class="material-icons absolute left-4 top-1/2 -translate-y-1/2 text-slate-400">lock</span>
                        <input type="password" id="old_password" name="old_password" required
                            class="w-full pl-12 pr-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition"
                            placeholder="Masukkan password lama untuk verifikasi">
                    </div>
                    <p class="text-xs text-slate-400 mt-1">Wajib diisi untuk verifikasi keamanan.</p>
                </div>

                <!-- PASSWORD BARU -->
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Password Baru</label>
                    <div class="relative">
                        <span class="material-icons absolute left-4 top-1/2 -translate-y-1/2 text-slate-400">lock_open</span>
                        <input type="password" id="new_password" name="new_password" minlength="8"
                            class="w-full pl-12 pr-12 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition"
                            placeholder="Minimal 8 karakter (kosongkan jika tidak ganti)">
                        <button type="button" onclick="togglePw('new_password', this)"
                            class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 hover:text-primary">
                            <span class="material-icons">visibility</span>
                        </button>
                    </div>
                </div>

                <!-- KONFIRMASI PASSWORD BARU -->
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Konfirmasi Password Baru</label>
                    <div class="relative">
                        <span class="material-icons absolute left-4 top-1/2 -translate-y-1/2 text-slate-400">lock_open</span>
                        <input type="password" id="confirm_password" name="confirm_password" minlength="8"
                            class="w-full pl-12 pr-12 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition"
                            placeholder="Ulangi password baru">
                        <button type="button" onclick="togglePw('confirm_password', this)"
                            class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 hover:text-primary">
                            <span class="material-icons">visibility</span>
                        </button>
                    </div>
                </div>

                <!-- TOMBOL -->
                <div class="flex flex-col-reverse sm:flex-row sm:justify-between gap-3 pt-3 border-t border-gray-100">
                    <a href="dashboard.php"
                        class="flex items-center justify-center gap-2 px-6 py-3 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl font-semibold text-sm transition">
                        <span class="material-icons text-lg">close</span>
                        Batal
                    </a>
                    <button type="submit"
                        class="flex items-center justify-center gap-2 px-7 py-3 bg-primary hover:bg-blue-900 text-white rounded-xl font-semibold text-sm transition shadow-lg shadow-blue-900/20">
                        <span class="material-icons text-lg">save</span>
                        Simpan Perubahan
                    </button>
                </div>

            </form>
        </div>

    </main>
</div>

<script>
    const form = document.querySelector('form');
    const inputs = form.querySelectorAll('input[type="text"], input[type="password"]');

    inputs.forEach((input, index) => {
        input.addEventListener('keydown', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                
                if (index < inputs.length - 1) {
                    // Pindah ke field berikutnya
                    inputs[index + 1].focus();
                } else {
                    // Di field terakhir → submit form
                    form.submit();
                }
            }
        });
    });
</script>

</body>
</html>