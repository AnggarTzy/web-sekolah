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


// ======================================================
// PROSES HAPUS
// ======================================================

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Hapus gambar dari folder uploads
    if (
        !empty($berita['gambar']) &&
        file_exists("../../uploads/" . $berita['gambar'])
    ) {
        unlink("../../uploads/" . $berita['gambar']);
    }

    // Hapus data dari database
    $query = mysqli_query(
        $conn,
        "DELETE FROM berita WHERE id = '$id'"
    );

    if ($query) {

        header("Location: index.php?status=deleted");
        exit;

    } else {

        $error = "Gagal menghapus berita: " . mysqli_error($conn);

    }
}
?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0">

    <title>Hapus Berita | Admin SMP Muhammadiyah 6 Krian</title>


    <!-- Google Fonts -->

    <link rel="preconnect" href="https://fonts.googleapis.com">

    <link
        rel="preconnect"
        href="https://fonts.gstatic.com"
        crossorigin>

    <link
        href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@500;600;700;800&family=Inter:wght@400;500;600&display=swap"
        rel="stylesheet">


    <!-- Material Icons -->

    <link
        href="https://fonts.googleapis.com/icon?family=Material+Icons"
        rel="stylesheet">


    <!-- Tailwind -->

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


<body class="bg-slate-50 font-sans text-slate-700 min-h-screen">


<!-- =====================================================
     NAVBAR
====================================================== -->

<nav class="bg-primary text-white shadow-lg">

    <div class="w-full px-5 sm:px-8 lg:px-10">

        <div class="flex items-center justify-between h-16">


            <!-- LOGO -->

            <div class="flex items-center gap-3">

                <div
                    class="w-10 h-10 bg-accent rounded-xl flex items-center justify-center font-headline font-extrabold text-primary text-lg shadow">

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


            <!-- KEMBALI -->

            <a
                href="index.php"
                class="flex items-center gap-2 px-4 py-2 bg-white/10 hover:bg-white/20 rounded-xl text-sm font-semibold transition">

                <span class="material-icons text-base">
                    arrow_back
                </span>

                <span class="hidden sm:inline">
                    Kembali
                </span>

            </a>

        </div>

    </div>

</nav>



<!-- =====================================================
     MAIN
====================================================== -->

<main class="w-full min-h-[calc(100vh-64px)]">


    <!-- HEADER HAPUS -->

    <section class="bg-red-50 border-b border-red-100">

        <div class="w-full px-5 sm:px-8 lg:px-12 py-10 sm:py-14">

            <div class="flex flex-col items-center text-center">

                <!-- ICON -->

                <div
                    class="w-20 h-20 sm:w-24 sm:h-24 rounded-full bg-red-100 flex items-center justify-center">

                    <span class="material-icons text-4xl sm:text-5xl text-red-600">
                        delete_forever
                    </span>

                </div>


                <!-- TITLE -->

                <h1
                    class="font-headline font-extrabold text-3xl sm:text-4xl text-primary mt-6">

                    Hapus Berita?

                </h1>


                <p class="text-slate-500 mt-2 text-sm sm:text-base">

                    Tindakan ini tidak dapat dibatalkan.

                </p>

            </div>

        </div>

    </section>



    <!-- =================================================
         CONTENT
    ================================================== -->

    <section class="w-full px-5 sm:px-8 lg:px-12 py-8 sm:py-10">


        <!-- ERROR -->

        <?php if (isset($error)) : ?>

            <div
                class="mb-6 flex items-start gap-3 bg-red-50 border border-red-200 text-red-700 px-5 py-4 rounded-2xl">

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



        <!-- =================================================
             BERITA
        ================================================== -->

        <div
            class="bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden">


            <!-- TITLE SECTION -->

            <div class="px-5 sm:px-7 py-5 border-b border-slate-100">

                <h2
                    class="font-headline font-bold text-lg sm:text-xl text-slate-800">

                    Berita yang akan dihapus

                </h2>

                <p class="text-sm text-slate-500 mt-1">

                    Periksa kembali data berita sebelum menghapusnya.

                </p>

            </div>



            <!-- NEWS PREVIEW -->

            <div class="p-5 sm:p-7">


                <div
                    class="flex flex-col md:flex-row gap-6 p-5 bg-slate-50 rounded-2xl border border-slate-200">


                    <!-- IMAGE -->

                    <div class="w-full md:w-72 lg:w-80 flex-shrink-0">

                        <?php if (!empty($berita['gambar'])) : ?>

                            <img
                                src="../../uploads/<?= htmlspecialchars($berita['gambar']) ?>"
                                alt="Thumbnail berita"
                                class="w-full h-52 md:h-48 lg:h-52 object-cover rounded-2xl border border-slate-200 shadow-sm">

                        <?php else : ?>

                            <div
                                class="w-full h-52 md:h-48 lg:h-52 bg-slate-200 rounded-2xl flex items-center justify-center">

                                <span
                                    class="material-icons text-5xl text-slate-400">

                                    image

                                </span>

                            </div>

                        <?php endif; ?>

                    </div>



                    <!-- INFO -->

                    <div class="flex-1 min-w-0 flex flex-col justify-center">


                        <!-- KATEGORI -->

                        <div class="mb-3">

                            <span
                                class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-blue-100 text-primary text-xs font-bold rounded-lg">

                                <span class="material-icons text-sm">
                                    folder
                                </span>

                                <?= htmlspecialchars(ucfirst($berita['kategori'])) ?>

                            </span>

                        </div>



                        <!-- JUDUL -->

                        <h3
                            class="font-headline font-extrabold text-2xl sm:text-3xl text-slate-800 leading-tight">

                            <?= htmlspecialchars($berita['judul']) ?>

                        </h3>



                        <!-- PENULIS -->

                        <div
                            class="flex items-center gap-2 mt-5 text-sm text-slate-500">

                            <span
                                class="w-8 h-8 rounded-lg bg-white border border-slate-200 flex items-center justify-center">

                                <span class="material-icons text-base">
                                    person
                                </span>

                            </span>

                            <div>

                                <p class="text-xs text-slate-400">
                                    Penulis
                                </p>

                                <p class="font-semibold text-slate-700">
                                    <?= htmlspecialchars($berita['penulis']) ?>
                                </p>

                            </div>

                        </div>



                        <!-- STATUS -->

                        <div
                            class="flex items-center gap-2 mt-4">

                            <span
                                class="w-8 h-8 rounded-lg bg-white border border-slate-200 flex items-center justify-center">

                                <span class="material-icons text-base">
                                    public
                                </span>

                            </span>

                            <div>

                                <p class="text-xs text-slate-400">
                                    Status
                                </p>

                                <p class="font-semibold text-slate-700">

                                    <?= htmlspecialchars(ucfirst($berita['status'])) ?>

                                </p>

                            </div>

                        </div>


                    </div>

                </div>

            </div>

        </div>



        <!-- =================================================
             WARNING
        ================================================== -->

        <div
            class="mt-6 bg-amber-50 border border-amber-200 rounded-2xl p-5 sm:p-6">

            <div class="flex items-start gap-4">

                <div
                    class="w-11 h-11 flex-shrink-0 rounded-xl bg-amber-100 flex items-center justify-center">

                    <span class="material-icons text-amber-600">
                        warning
                    </span>

                </div>


                <div>

                    <h3
                        class="font-headline font-bold text-amber-800">

                        Perhatian

                    </h3>


                    <p
                        class="text-sm text-amber-700 mt-1 leading-relaxed">

                        Berita ini akan dihapus secara permanen dari database beserta gambar yang tersimpan. Data yang sudah dihapus tidak dapat dikembalikan.

                    </p>

                </div>

            </div>

        </div>



        <!-- =================================================
             BUTTON
        ================================================== -->

        <div
            class="mt-6 bg-white border border-slate-200 rounded-2xl shadow-sm p-5">


            <form method="POST">


                <div
                    class="flex flex-col sm:flex-row sm:justify-end gap-3">


                    <!-- BATAL -->

                    <a
                        href="index.php"
                        class="flex items-center justify-center gap-2 px-7 py-3.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl font-semibold text-sm transition sm:min-w-[160px]">

                        <span class="material-icons text-lg">
                            close
                        </span>

                        Batal

                    </a>



                    <!-- HAPUS -->

                    <button
                        type="submit"
                        class="flex items-center justify-center gap-2 px-7 py-3.5 bg-red-600 hover:bg-red-700 text-white rounded-xl font-semibold text-sm transition shadow-lg shadow-red-600/20 sm:min-w-[200px]">

                        <span class="material-icons text-lg">
                            delete
                        </span>

                        Ya, Hapus Berita

                    </button>


                </div>


            </form>

        </div>


    </section>


</main>



<!-- FOOTER -->

<footer class="w-full px-5 sm:px-8 lg:px-12 pb-8">

    <p class="text-center text-xs text-slate-400">

        Data akan dihapus secara permanen setelah konfirmasi.

    </p>

</footer>


</body>
</html>