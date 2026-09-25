<?php
session_start();

include '../config/koneksi.php';

cek_login();

$id = (int) ($_GET['id'] ?? 0);

$stmt = mysqli_prepare($conn, "SELECT * FROM fasilitas WHERE id = ?");
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$fasilitas = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt);

if (!$fasilitas) {
    header("Location: index.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    hapus_gambar($fasilitas['gambar']);

    $stmt = mysqli_prepare($conn, "DELETE FROM fasilitas WHERE id = ?");
    mysqli_stmt_bind_param($stmt, "i", $id);

    if (mysqli_stmt_execute($stmt)) {
        mysqli_stmt_close($stmt);
        catat_aktivitas($conn, 'Fasilitas', 'Menghapus', $fasilitas['nama']);
        header("Location: index.php?status=success");
        exit;
    } else {
        $error = "Gagal menghapus: " . mysqli_error($conn);
        mysqli_stmt_close($stmt);
    }
}
?>

<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hapus Fasilitas | Admin SMP Muhammadiyah 6 Krian</title>

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

<body class="bg-slate-50 font-sans text-slate-700 min-h-screen">

    <nav class="bg-primary text-white shadow-lg">
        <div class="w-full px-5 sm:px-8 lg:px-10">
            <div class="flex items-center justify-between h-16">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-accent rounded-xl flex items-center justify-center font-headline font-extrabold text-primary text-lg shadow">S</div>
                    <div>
                        <p class="font-headline font-bold text-lg leading-tight">Admin Panel</p>
                        <p class="text-xs text-white/60">SMP Muhammadiyah 6 Krian</p>
                    </div>
                </div>
                <a href="index.php" class="flex items-center gap-2 px-4 py-2 bg-white/10 hover:bg-white/20 rounded-xl text-sm font-semibold transition">
                    <span class="material-icons text-base">arrow_back</span>
                    <span class="hidden sm:inline">Kembali</span>
                </a>
            </div>
        </div>
    </nav>

    <main class="w-full min-h-[calc(100vh-64px)]">

        <section class="bg-red-50 border-b border-red-100">
            <div class="w-full px-5 sm:px-8 lg:px-12 py-10 sm:py-14">
                <div class="flex flex-col items-center text-center">
                    <div class="w-20 h-20 sm:w-24 sm:h-24 rounded-full bg-red-100 flex items-center justify-center">
                        <span class="material-icons text-4xl sm:text-5xl text-red-600">delete_forever</span>
                    </div>
                    <h1 class="font-headline font-extrabold text-3xl sm:text-4xl text-primary mt-6">Hapus Fasilitas?</h1>
                    <p class="text-slate-500 mt-2 text-sm sm:text-base">Tindakan ini tidak dapat dibatalkan.</p>
                </div>
            </div>
        </section>

        <section class="w-full px-5 sm:px-8 lg:px-12 py-8 sm:py-10">

            <?php if (isset($error)) : ?>
                <div class="mb-6 flex items-start gap-3 bg-red-50 border border-red-200 text-red-700 px-5 py-4 rounded-2xl">
                    <span class="material-icons">error_outline</span>
                    <div>
                        <p class="font-semibold">Terjadi Kesalahan</p>
                        <p class="text-sm mt-1"><?= htmlspecialchars($error) ?></p>
                    </div>
                </div>
            <?php endif; ?>

            <div class="bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden">
                <div class="px-5 sm:px-7 py-5 border-b border-slate-100">
                    <h2 class="font-headline font-bold text-lg sm:text-xl text-slate-800">Fasilitas yang akan dihapus</h2>
                    <p class="text-sm text-slate-500 mt-1">Periksa kembali data sebelum menghapusnya.</p>
                </div>

                <div class="p-5 sm:p-7">
                    <div class="flex flex-col md:flex-row gap-6 p-5 bg-slate-50 rounded-2xl border border-slate-200">

                        <div class="w-full md:w-72 lg:w-80 flex-shrink-0">
                            <?php if (!empty($fasilitas['gambar'])) : ?>
                                <img src="../../uploads/<?= htmlspecialchars($fasilitas['gambar']) ?>"
                                     alt="Gambar fasilitas"
                                     class="w-full h-52 md:h-48 lg:h-52 object-cover rounded-2xl border border-slate-200 shadow-sm">
                            <?php else : ?>
                                <div class="w-full h-52 md:h-48 lg:h-52 bg-slate-200 rounded-2xl flex items-center justify-center">
                                    <span class="material-icons text-5xl text-slate-400">image</span>
                                </div>
                            <?php endif; ?>
                        </div>

                        <div class="flex-1 min-w-0 flex flex-col justify-center">
                            <div class="mb-3">
                                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-blue-100 text-primary text-xs font-bold rounded-lg">
                                    <span class="material-icons text-sm">folder</span>
                                    <?= htmlspecialchars(ucfirst($fasilitas['kategori'])) ?>
                                </span>
                            </div>

                            <h3 class="font-headline font-extrabold text-2xl sm:text-3xl text-slate-800 leading-tight">
                                <?= htmlspecialchars($fasilitas['nama']) ?>
                            </h3>

                            <?php if (!empty($fasilitas['deskripsi'])) : ?>
                                <p class="text-slate-500 mt-4 leading-relaxed text-sm">
                                    <?= htmlspecialchars($fasilitas['deskripsi']) ?>
                                </p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-6 bg-amber-50 border border-amber-200 rounded-2xl p-5 sm:p-6">
                <div class="flex items-start gap-4">
                    <div class="w-11 h-11 flex-shrink-0 rounded-xl bg-amber-100 flex items-center justify-center">
                        <span class="material-icons text-amber-600">warning</span>
                    </div>
                    <div>
                        <h3 class="font-headline font-bold text-amber-800">Perhatian</h3>
                        <p class="text-sm text-amber-700 mt-1 leading-relaxed">
                            Fasilitas ini akan dihapus secara permanen dari database beserta gambar yang tersimpan. Data yang sudah dihapus tidak dapat dikembalikan.
                        </p>
                    </div>
                </div>
            </div>

            <div class="mt-6 bg-white border border-slate-200 rounded-2xl shadow-sm p-5">
                <form method="POST">
                    <div class="flex flex-col sm:flex-row sm:justify-end gap-3">
                        <a href="index.php"
                           class="flex items-center justify-center gap-2 px-7 py-3.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl font-semibold text-sm transition sm:min-w-[160px]">
                            <span class="material-icons text-lg">close</span>
                            Batal
                        </a>
                        <button type="submit"
                                class="flex items-center justify-center gap-2 px-7 py-3.5 bg-red-600 hover:bg-red-700 text-white rounded-xl font-semibold text-sm transition shadow-lg shadow-red-600/20 sm:min-w-[200px]">
                            <span class="material-icons text-lg">delete</span>
                            Ya, Hapus Fasilitas
                        </button>
                    </div>
                </form>
            </div>

        </section>
    </main>

</body>
</html>