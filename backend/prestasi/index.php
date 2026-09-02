<?php
session_start();

if (!isset($_SESSION['login'])) {
    header("Location: ../admin/login.php");
    exit;
}

include '../config/koneksi.php';

$status = $_GET['status'] ?? '';
if ($status === 'success') {
    $notif = '<div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl mb-4">✅ Berhasil!</div>';
} elseif ($status === 'error') {
    $notif = '<div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl mb-4">❌ Gagal!</div>';
} else {
    $notif = '';
}

$query = mysqli_query($conn, "SELECT * FROM prestasi ORDER BY tahun DESC");
?>

<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Prestasi | Admin SMP Muhammadiyah 6 Krian</title>

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
        .material-icons { font-size: 20px; vertical-align: middle; margin-right: 4px; }
    </style>
</head>
<body class="bg-gray-50 font-sans text-slate-700 antialiased">

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
                    <a href="../admin/dashboard.php" class="flex items-center gap-2 px-4 py-2 bg-white/10 hover:bg-white/20 rounded-xl text-sm font-semibold transition">
                        <span class="material-icons text-base">dashboard</span>
                        <span class="hidden sm:inline">Dashboard</span>
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
    <main class="max-w-7xl mx-auto p-6 sm:p-8">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8">
            <div>
                <div class="flex items-center gap-3 mb-2">
                    <div class="w-11 h-11 bg-yellow-100 text-primary rounded-xl flex items-center justify-center">
                        <span class="material-icons">emoji_events</span>
                    </div>
                    <div>
                        <h1 class="font-headline font-extrabold text-2xl sm:text-3xl text-primary">Kelola Prestasi</h1>
                        <p class="text-sm text-slate-500">Manajemen data prestasi siswa.</p>
                    </div>
                </div>
            </div>
            <a href="tambah.php" class="flex items-center gap-2 px-6 py-3 bg-primary text-white rounded-xl font-semibold text-sm hover:bg-opacity-90 transition shadow-lg">
                <span class="material-icons text-lg">add</span>
                Tambah Prestasi
            </a>
        </div>

        <?= $notif ?>

        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="px-6 py-4 text-left font-semibold text-slate-500">ID</th>
                            <th class="px-6 py-4 text-left font-semibold text-slate-500">Gambar</th>
                            <th class="px-6 py-4 text-left font-semibold text-slate-500">Judul</th>
                            <th class="px-6 py-4 text-left font-semibold text-slate-500">Kategori</th>
                            <th class="px-6 py-4 text-left font-semibold text-slate-500">Tingkat</th>
                            <th class="px-6 py-4 text-left font-semibold text-slate-500">Tahun</th>
                            <th class="px-6 py-4 text-left font-semibold text-slate-500">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        <?php while ($row = mysqli_fetch_assoc($query)) : ?>
                        <tr class="hover:bg-slate-50 transition">
                            <td class="px-6 py-4"><?= $row['id'] ?></td>
                            <td class="px-6 py-4">
                                <?php if ($row['gambar']) : ?>
                                    <img src="../../uploads/<?= $row['gambar'] ?>" class="w-20 h-14 object-cover rounded-lg border border-slate-200">
                                <?php else : ?>
                                    <span class="text-slate-400">-</span>
                                <?php endif; ?>
                            </td>
                            <td class="px-6 py-4 font-medium text-slate-800 max-w-xs truncate"><?= htmlspecialchars($row['judul']) ?></td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-blue-50 text-blue-700 rounded-lg text-xs font-semibold">
                                    <span class="material-icons text-sm">folder</span>
                                    <?= htmlspecialchars($row['kategori']) ?>
                                </span>
                            </td>
                            <td class="px-6 py-4"><?= htmlspecialchars($row['tingkat']) ?></td>
                            <td class="px-6 py-4"><?= $row['tahun'] ?></td>
                            <td class="px-6 py-4">
                                <a href="edit.php?id=<?= $row['id'] ?>" class="text-blue-600 hover:text-blue-800 font-semibold mr-3">Edit</a>
                                <a href="hapus.php?id=<?= $row['id'] ?>" onclick="return confirm('Yakin mau hapus?')" class="text-red-600 hover:text-red-800 font-semibold">Hapus</a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</body>
</html>