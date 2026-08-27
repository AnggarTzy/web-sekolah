<?php
session_start();

if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit;
}

include '../config/koneksi.php';

$username = $_SESSION['username'] ?? 'Admin';


/* =====================================================
   TOTAL BERITA
===================================================== */

$query_total_berita = mysqli_query(
    $conn,
    "SELECT COUNT(*) AS total FROM berita"
);

$total_berita = 0;

if ($query_total_berita) {
    $data_total = mysqli_fetch_assoc($query_total_berita);
    $total_berita = $data_total['total'];
}


/* =====================================================
   BERITA TERBARU
===================================================== */

$query_berita = mysqli_query(
    $conn,
    "SELECT id, judul, kategori, status, gambar, tanggal_posting
     FROM berita
     ORDER BY tanggal_posting DESC
     LIMIT 2"
);

?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Dashboard Admin | Sistem Informasi Sekolah</title>


    <!-- =================================================
         GOOGLE FONTS
    ================================================== -->

    <link rel="preconnect" href="https://fonts.googleapis.com">

    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link
        href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@500;600;700;800&family=Inter:wght@400;500;600&display=swap"
        rel="stylesheet"
    >


    <!-- =================================================
         MATERIAL ICONS
    ================================================== -->

    <link
        href="https://fonts.googleapis.com/icon?family=Material+Icons"
        rel="stylesheet"
    >


    <!-- =================================================
         TAILWIND CSS
    ================================================== -->

    <script src="https://cdn.tailwindcss.com"></script>

    <script>

        tailwind.config = {

            theme: {

                extend: {

                    colors: {

                        primary: '#1A3C6E',

                        accent: '#C9A94A'

                    },

                    fontFamily: {

                        sans: ['Inter', 'sans-serif'],

                        headline: [
                            'Plus Jakarta Sans',
                            'sans-serif'
                        ]

                    }

                }

            }

        }

    </script>


    <style>

        .material-icons {
            font-size: 20px;
            vertical-align: middle;
        }

    </style>

</head>


<body class="bg-gray-50 font-sans text-slate-700 antialiased">


    <!-- =================================================
         NAVBAR
    ================================================== -->

    <nav class="bg-primary text-white shadow-lg sticky top-0 z-50">

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="flex items-center justify-between h-16">


                <!-- LOGO -->

                <div class="flex items-center gap-3">

                    <div
                        class="w-10 h-10 bg-accent rounded-lg flex items-center justify-center font-headline font-bold text-primary text-lg"
                    >
                        S
                    </div>

                    <span
                        class="font-headline font-bold text-lg"
                    >
                        Admin Panel
                    </span>

                </div>


                <!-- NAVBAR KANAN -->

                <div class="flex items-center gap-4">

                    <span
                        class="text-sm text-white/70 hidden sm:block"
                    >

                        Welcome,

                        <span class="font-bold text-white">

                            <?= htmlspecialchars($username) ?>

                        </span>

                    </span>


                    <a
                        href="logout.php"
                        class="px-4 py-2 bg-red-500 hover:bg-red-600 rounded-lg text-sm font-semibold transition"
                    >
                        Logout
                    </a>

                </div>

            </div>

        </div>

    </nav>



    <!-- =================================================
         LAYOUT UTAMA
    ================================================== -->

    <div class="flex min-h-screen">


        <!-- =================================================
             SIDEBAR
        ================================================== -->

        <aside
            class="w-64 bg-white border-r border-gray-200 hidden md:flex flex-col fixed h-full pt-6"
        >

            <div class="px-6 space-y-2">


                <!-- DASHBOARD -->

                <a
                    href="dashboard.php"
                    class="flex items-center gap-3 px-4 py-3 rounded-xl bg-primary text-white font-semibold text-sm transition"
                >

                    <span class="material-icons">
                        dashboard
                    </span>

                    Dashboard

                </a>


                <!-- BERITA -->

                <a
                    href="../berita/index.php"
                    class="flex items-center gap-3 px-4 py-3 rounded-xl text-gray-600 hover:bg-gray-50 hover:text-primary font-semibold text-sm transition"
                >

                    <span class="material-icons">
                        article
                    </span>

                    Kelola Berita

                </a>


                <!-- PRESTASI -->

                <a
                    href="../prestasi/index.php"
                    class="flex items-center gap-3 px-4 py-3 rounded-xl text-gray-600 hover:bg-gray-50 hover:text-primary font-semibold text-sm transition"
                >

                    <span class="material-icons">
                        emoji_events
                    </span>

                    Kelola Prestasi

                </a>


                <!-- GURU -->

                <a
                    href="../guru/index.php"
                    class="flex items-center gap-3 px-4 py-3 rounded-xl text-gray-600 hover:bg-gray-50 hover:text-primary font-semibold text-sm transition"
                >

                    <span class="material-icons">
                        groups
                    </span>

                    Kelola Guru

                </a>


                <!-- FASILITAS -->

                <a
                    href="#"
                    class="flex items-center gap-3 px-4 py-3 rounded-xl text-gray-600 hover:bg-gray-50 hover:text-primary font-semibold text-sm transition"
                >

                    <span class="material-icons">
                        apartment
                    </span>

                    Kelola Fasilitas

                </a>


                <!-- EKSTRAKURIKULER -->

                <a
                    href="#"
                    class="flex items-center gap-3 px-4 py-3 rounded-xl text-gray-600 hover:bg-gray-50 hover:text-primary font-semibold text-sm transition"
                >

                    <span class="material-icons">
                        sports_kabaddi
                    </span>

                    Kelola Ekstrakurikuler

                </a>

            </div>

        </aside>



        <!-- =================================================
             KONTEN UTAMA
        ================================================== -->

        <main class="flex-1 md:ml-64 p-6 sm:p-8">


            <!-- =================================================
                 HEADER
            ================================================== -->

            <div class="mb-8">

                <h1
                    class="font-headline font-extrabold text-3xl text-primary"
                >
                    Dashboard
                </h1>

                <p class="text-gray-500 mt-1">
                    Selamat datang kembali di panel administrasi.
                </p>

            </div>



            <!-- =================================================
                 KARTU STATISTIK
            ================================================== -->

            <div
                class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8"
            >


                <!-- TOTAL BERITA -->

                <div
                    class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 hover:shadow-md transition"
                >

                    <div
                        class="w-12 h-12 bg-blue-50 rounded-xl flex items-center justify-center mb-4"
                    >

                        <span class="text-2xl">
                            📰
                        </span>

                    </div>

                    <p
                        class="text-3xl font-headline font-bold text-gray-800"
                    >

                        <?= $total_berita ?>

                    </p>

                    <p class="text-sm text-gray-500 mt-1">
                        Total Berita
                    </p>

                </div>



                <!-- TOTAL PRESTASI -->

                <div
                    class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 hover:shadow-md transition"
                >

                    <div
                        class="w-12 h-12 bg-yellow-50 rounded-xl flex items-center justify-center mb-4"
                    >

                        <span class="text-2xl">
                            🏆
                        </span>

                    </div>

                    <p
                        class="text-3xl font-headline font-bold text-gray-800"
                    >
                        8
                    </p>

                    <p class="text-sm text-gray-500 mt-1">
                        Total Prestasi
                    </p>

                </div>



                <!-- TOTAL GURU -->

                <div
                    class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 hover:shadow-md transition"
                >

                    <div
                        class="w-12 h-12 bg-green-50 rounded-xl flex items-center justify-center mb-4"
                    >

                        <span class="text-2xl">
                            👩‍🏫
                        </span>

                    </div>

                    <p
                        class="text-3xl font-headline font-bold text-gray-800"
                    >
                        45
                    </p>

                    <p class="text-sm text-gray-500 mt-1">
                        Total Guru
                    </p>

                </div>



                <!-- TOTAL EKSTRAKURIKULER -->

                <div
                    class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 hover:shadow-md transition"
                >

                    <div
                        class="w-12 h-12 bg-purple-50 rounded-xl flex items-center justify-center mb-4"
                    >

                        <span class="text-2xl">
                            🏫
                        </span>

                    </div>

                    <p
                        class="text-3xl font-headline font-bold text-gray-800"
                    >
                        24
                    </p>

                    <p class="text-sm text-gray-500 mt-1">
                        Ekstrakurikuler
                    </p>

                </div>

            </div>



            <!-- =================================================
                 BERITA TERBARU
            ================================================== -->

            <div
                class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden"
            >


                <!-- HEADER BERITA -->

                <div
                    class="px-6 py-4 border-b border-gray-100 flex justify-between items-center"
                >

                    <h2
                        class="font-headline font-bold text-lg text-primary"
                    >
                        Berita Terbaru
                    </h2>


                    <a
                        href="../berita/index.php"
                        class="text-sm text-primary hover:underline"
                    >
                        Lihat Semua →
                    </a>

                </div>



                <!-- TABEL -->

                <div class="overflow-x-auto">

                    <table class="w-full text-sm">


                        <!-- HEADER TABEL -->

                        <thead class="bg-gray-50">

                            <tr>

                                <th
                                    class="px-6 py-3 text-left font-semibold text-gray-500"
                                >
                                    Gambar
                                </th>

                                <th
                                    class="px-6 py-3 text-left font-semibold text-gray-500"
                                >
                                    ID
                                </th>

                                <th
                                    class="px-6 py-3 text-left font-semibold text-gray-500"
                                >
                                    Judul
                                </th>

                                <th
                                    class="px-6 py-3 text-left font-semibold text-gray-500"
                                >
                                    Kategori
                                </th>

                                <th
                                    class="px-6 py-3 text-left font-semibold text-gray-500"
                                >
                                    Tanggal
                                </th>

                                <th
                                    class="px-6 py-3 text-left font-semibold text-gray-500"
                                >
                                    Status
                                </th>

                                <th
                                    class="px-6 py-3 text-left font-semibold text-gray-500"
                                >
                                    Aksi
                                </th>

                            </tr>

                        </thead>



                        <!-- ISI TABEL -->

                        <tbody class="divide-y divide-gray-100">


                            <?php if ($query_berita && mysqli_num_rows($query_berita) > 0): ?>


                                <?php while ($berita = mysqli_fetch_assoc($query_berita)): ?>


                                    <tr
                                        class="hover:bg-gray-50 transition"
                                    >


                                        <!-- =================================================
                                             GAMBAR
                                        ================================================== -->

                                        <td class="px-6 py-4">

                                            <?php if (!empty($berita['gambar'])): ?>

                                                <img
                                                    src="../../uploads/<?= htmlspecialchars($berita['gambar']) ?>"
                                                    alt="<?= htmlspecialchars($berita['judul']) ?>"
                                                    class="w-16 h-12 object-cover rounded-lg border border-gray-200 shadow-sm"
                                                    onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';"
                                                >

                                                <!-- FALLBACK JIKA GAMBAR GAGAL -->

                                                <div
                                                    class="hidden w-16 h-12 bg-gray-100 rounded-lg items-center justify-center border border-gray-200"
                                                >

                                                    <span
                                                        class="material-icons text-gray-400"
                                                    >
                                                        image
                                                    </span>

                                                </div>

                                            <?php else: ?>

                                                <!-- JIKA TIDAK ADA GAMBAR -->

                                                <div
                                                    class="w-16 h-12 bg-gray-100 rounded-lg flex items-center justify-center border border-gray-200"
                                                >

                                                    <span
                                                        class="material-icons text-gray-400"
                                                    >
                                                        image
                                                    </span>

                                                </div>

                                            <?php endif; ?>

                                        </td>



                                        <!-- =================================================
                                             ID
                                        ================================================== -->

                                        <td class="px-6 py-4">

                                            <?= htmlspecialchars(
                                                $berita['id']
                                            ) ?>

                                        </td>



                                        <!-- =================================================
                                             JUDUL
                                        ================================================== -->

                                        <td
                                            class="px-6 py-4 font-medium text-gray-800 max-w-xs"
                                        >

                                            <div
                                                class="truncate"
                                                title="<?= htmlspecialchars($berita['judul']) ?>"
                                            >

                                                <?= htmlspecialchars(
                                                    $berita['judul']
                                                ) ?>

                                            </div>

                                        </td>



                                        <!-- =================================================
                                             KATEGORI
                                        ================================================== -->

                                        <td class="px-6 py-4">

                                            <span
                                                class="px-2 py-1 bg-blue-50 text-blue-600 rounded-full text-xs font-medium"
                                            >

                                                <?= htmlspecialchars(
                                                    ucfirst($berita['kategori'])
                                                ) ?>

                                            </span>

                                        </td>



                                        <!-- =================================================
                                             TANGGAL
                                        ================================================== -->

                                        <td class="px-6 py-4 whitespace-nowrap">

                                            <?= date(
                                                'd M Y',
                                                strtotime(
                                                    $berita['tanggal_posting']
                                                )
                                            ) ?>

                                        </td>



                                        <!-- =================================================
                                             STATUS
                                        ================================================== -->

                                        <td class="px-6 py-4">


                                            <?php if ($berita['status'] === 'publish'): ?>

                                                <span
                                                    class="px-2 py-1 bg-green-100 text-green-700 rounded-full text-xs font-semibold"
                                                >
                                                    Published
                                                </span>

                                            <?php else: ?>

                                                <span
                                                    class="px-2 py-1 bg-yellow-100 text-yellow-700 rounded-full text-xs font-semibold"
                                                >
                                                    Draft
                                                </span>

                                            <?php endif; ?>


                                        </td>



                                        <!-- =================================================
                                             AKSI
                                        ================================================== -->

                                        <td
                                            class="px-6 py-4 whitespace-nowrap"
                                        >


                                            <a
                                                href="../berita/edit.php?id=<?= $berita['id'] ?>"
                                                class="text-blue-600 hover:underline"
                                            >
                                                Edit
                                            </a>


                                            <span
                                                class="text-gray-300 mx-2"
                                            >
                                                |
                                            </span>


                                            <a
                                                href="../berita/hapus.php?id=<?= $berita['id'] ?>"
                                                class="text-red-600 hover:underline"
                                                onclick="return confirm('Yakin ingin menghapus berita ini?')"
                                            >
                                                Hapus
                                            </a>


                                        </td>


                                    </tr>


                                <?php endwhile; ?>


                            <?php else: ?>


                                <!-- =================================================
                                     BELUM ADA BERITA
                                ================================================== -->

                                <tr>

                                    <td
                                        colspan="7"
                                        class="px-6 py-10 text-center"
                                    >

                                        <div
                                            class="flex flex-col items-center justify-center"
                                        >

                                            <div
                                                class="w-14 h-14 bg-gray-100 rounded-full flex items-center justify-center mb-3"
                                            >

                                                <span
                                                    class="material-icons text-gray-400"
                                                    style="font-size: 28px;"
                                                >
                                                    article
                                                </span>

                                            </div>


                                            <p
                                                class="text-gray-500 font-medium"
                                            >
                                                Belum ada berita
                                            </p>


                                            <p
                                                class="text-gray-400 text-xs mt-1"
                                            >
                                                Berita yang kamu tambahkan akan muncul di sini.
                                            </p>

                                        </div>

                                    </td>

                                </tr>


                            <?php endif; ?>


                        </tbody>

                    </table>

                </div>

            </div>

        </main>

    </div>

</body>

</html>