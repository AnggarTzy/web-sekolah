<?php
session_start();

if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit;
}

$username = $_SESSION['username'] ?? 'Admin';
?>

<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin | SMP Muhammadiyah 6 Krian</title>
    
    <!- Google Fonts ->
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
    
    <style>
        .material-icons {
            font-size: 20px;
            vertical-align: middle;
            margin-right: 4px;
        }
    </style>
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
                    <span class="text-sm text-white/70 hidden sm:block">Welcome, <span class="font-bold text-white"><?= htmlspecialchars($username) ?></span></span>
                    <a href="logout.php" class="px-4 py-2 bg-red-500 hover:bg-red-600 rounded-lg text-sm font-semibold transition">
                        Logout
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- ========== LAYOUT UTAMA ========== -->
    <div class="flex min-h-screen">
        
        <!-- ========== SIDEBAR ========== -->
        <aside class="w-64 bg-white border-r border-gray-200 hidden md:flex flex-col fixed h-full pt-6">
            <div class="px-6 space-y-2">
                <a href="dashboard.php" class="flex items-center gap-3 px-4 py-3 rounded-xl bg-primary text-white font-semibold text-sm transition">
                    <span class="material-icons">dashboard</span>
                    Dashboard
                </a>
                <a href="../berita/index.php" class="flex items-center gap-3 px-4 py-3 rounded-xl text-gray-600 hover:bg-gray-50 hover:text-primary font-semibold text-sm transition">
                    <span class="material-icons">article</span>
                    Kelola Berita
                </a>
                <a href="#" class="flex items-center gap-3 px-4 py-3 rounded-xl text-gray-600 hover:bg-gray-50 hover:text-primary font-semibold text-sm transition">
                    <span class="material-icons">emoji_events</span>
                    Kelola Prestasi
                </a>
                <a href="#" class="flex items-center gap-3 px-4 py-3 rounded-xl text-gray-600 hover:bg-gray-50 hover:text-primary font-semibold text-sm transition">
                    <span class="material-icons">groups</span>
                    Kelola Guru
                </a>
                <a href="#" class="flex items-center gap-3 px-4 py-3 rounded-xl text-gray-600 hover:bg-gray-50 hover:text-primary font-semibold text-sm transition">
                    <span class="material-icons">apartment</span>
                    Kelola Fasilitas
                </a>
                <a href="#" class="flex items-center gap-3 px-4 py-3 rounded-xl text-gray-600 hover:bg-gray-50 hover:text-primary font-semibold text-sm transition">
                    <span class="material-icons">sports_kabaddi</span>
                    Kelola Ekstrakurikuler
                </a>
            </div>
        </aside>

        <!-- ========== KONTEN UTAMA ========== -->
        <main class="flex-1 md:ml-64 p-6 sm:p-8">
            
            <!-- Header Section -->
            <div class="mb-8">
                <h1 class="font-headline font-extrabold text-3xl text-primary">Dashboard</h1>
                <p class="text-gray-500 mt-1">Selamat datang kembali di panel administrasi.</p>
            </div>

            <!-- Kartu Statistik -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 hover:shadow-md transition">
                    <div class="w-12 h-12 bg-blue-50 rounded-xl flex items-center justify-center mb-4">
                        <span class="text-2xl">📰</span>
                    </div>
                    <p class="text-3xl font-headline font-bold text-gray-800">12</p>
                    <p class="text-sm text-gray-500 mt-1">Total Berita</p>
                </div>

                <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 hover:shadow-md transition">
                    <div class="w-12 h-12 bg-yellow-50 rounded-xl flex items-center justify-center mb-4">
                        <span class="text-2xl">🏆</span>
                    </div>
                    <p class="text-3xl font-headline font-bold text-gray-800">8</p>
                    <p class="text-sm text-gray-500 mt-1">Total Prestasi</p>
                </div>

                <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 hover:shadow-md transition">
                    <div class="w-12 h-12 bg-green-50 rounded-xl flex items-center justify-center mb-4">
                        <span class="text-2xl">👩‍🏫</span>
                    </div>
                    <p class="text-3xl font-headline font-bold text-gray-800">45</p>
                    <p class="text-sm text-gray-500 mt-1">Total Guru</p>
                </div>

                <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 hover:shadow-md transition">
                    <div class="w-12 h-12 bg-purple-50 rounded-xl flex items-center justify-center mb-4">
                        <span class="text-2xl">🏫</span>
                    </div>
                    <p class="text-3xl font-headline font-bold text-gray-800">24</p>
                    <p class="text-sm text-gray-500 mt-1">Ekstrakurikuler</p>
                </div>
            </div>

            <!- Tabel Berita Terbaru ->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center">
                    <h2 class="font-headline font-bold text-lg text-primary">Berita Terbaru</h2>
                    <a href="../berita/index.php" class="text-sm text-primary hover:underline">Lihat Semua →</a>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left font-semibold text-gray-500">ID</th>
                                <th class="px-6 py-3 text-left font-semibold text-gray-500">Judul</th>
                                <th class="px-6 py-3 text-left font-semibold text-gray-500">Kategori</th>
                                <th class="px-6 py-3 text-left font-semibold text-gray-500">Tanggal</th>
                                <th class="px-6 py-3 text-left font-semibold text-gray-500">Status</th>
                                <th class="px-6 py-3 text-left font-semibold text-gray-500">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-6 py-4">1</td>
                                <td class="px-6 py-4 font-medium text-gray-800">Juara 1 Lomba Robotik</td>
                                <td class="px-6 py-4">Prestasi</td>
                                <td class="px-6 py-4">12 Agu 2026</td>
                                <td class="px-6 py-4">
                                    <span class="px-2 py-1 bg-green-100 text-green-700 rounded-full text-xs font-semibold">Published</span>
                                </td>
                                <td class="px-6 py-4">
                                    <a href="../berita/edit.php" class="text-blue-600 hover:underline">Edit</a>
                                    <span class="text-gray-300 mx-2">|</span>
                                    <a href="../berita/hapus.php" class="text-red-600 hover:underline">Hapus</a>
                                </td>
                            </tr>
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-6 py-4">2</td>
                                <td class="px-6 py-4 font-medium text-gray-800">Bakti Sosial Ramadhan</td>
                                <td class="px-6 py-4">Kegiatan</td>
                                <td class="px-6 py-4">8 Agu 2026</td>
                                <td class="px-6 py-4">
                                    <span class="px-2 py-1 bg-green-100 text-green-700 rounded-full text-xs font-semibold">Published</span>
                                </td>
                                <td class="px-6 py-4">
                                    <a href="../berita/edit.php" class="text-blue-600 hover:underline">Edit</a>
                                    <span class="text-gray-300 mx-2">|</span>
                                    <a href="../berita/hapus.php" class="text-red-600 hover:underline">Hapus</a>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

        </main>
    </div>

</body>
</html>