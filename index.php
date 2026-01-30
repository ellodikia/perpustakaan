<?php
$koneksi = mysqli_connect("localhost", "root", "", "db_gabriel_perpustakaan");

$tampilkan_semua = isset($_GET['view']) && $_GET['view'] == 'all';

$res_buku_stat = mysqli_query($koneksi, "SELECT SUM(stok) as total FROM buku");
$total_buku = mysqli_fetch_assoc($res_buku_stat)['total'] ?? 0;

$res_agt_stat = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM anggota");
$total_anggota = mysqli_fetch_assoc($res_agt_stat)['total'] ?? 0;

if ($tampilkan_semua) {
    $query_buku = "SELECT * FROM buku ORDER BY judul_buku ASC";
} else {
    $query_buku = "SELECT b.*, COUNT(t.id_buku) as total_dipinjam 
                   FROM buku b 
                   LEFT JOIN transaksi t ON b.id_buku = t.id_buku 
                   GROUP BY b.id_buku 
                   ORDER BY total_dipinjam DESC 
                   LIMIT 4";
}
$res_tampilan = mysqli_query($koneksi, $query_buku);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="BookLoan, baca buku, pinjam buku">
    <title>E-Perpustakaan Gabriel</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="icon" href="img/logo.jpeg" type="image/png/jpeg" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <style>
        body { background-color: #fff1f2; }
        .text-main { color: #881337; }
        .bg-main { background-color: #881337; }
        .accent-rose { color: #e11d48; }
        .bg-accent-rose { background-color: #e11d48; }
        .bg-dark-slate { background-color: #0f172a; }
        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;  
            overflow: hidden;
        }
    </style>
</head>
<body class="font-sans text-slate-800 selection:bg-rose-200">

    <nav class="p-4 md:p-5 bg-white/80 backdrop-blur-md border-b border-rose-100 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto flex justify-between items-center">
            <a href="index.php" class="text-xl md:text-2xl font-black tracking-tighter text-main flex items-center gap-2">
                <div class="w-8 h-8 md:w-10 md:h-10 bg-accent-rose rounded-lg md:rounded-xl flex items-center justify-center shadow-lg">
                    <i class="fa-solid fa-bookmark text-white text-[10px] md:text-sm"></i>
                </div>
                BookLoan</span>
            </a>
            <a href="login.php" class="bg-dark-slate hover:bg-rose-900 text-white px-4 py-2 md:px-6 md:py-2.5 rounded-lg md:rounded-xl font-bold transition-all text-xs md:text-sm shadow-lg">
                Sign In
            </a>
        </div>
    </nav>

    <?php if (!$tampilkan_semua): ?>
    <div class="bg-dark-slate py-16 md:py-32 px-6 relative overflow-hidden">
        <div class="absolute top-0 right-0 -translate-y-1/2 translate-x-1/2 w-64 h-64 md:w-96 md:h-96 bg-rose-600/20 rounded-full blur-3xl"></div>
        <div class="max-w-7xl mx-auto flex flex-col items-center text-center relative z-10">
            <h1 class="text-3xl md:text-7xl font-black text-white mb-6 md:mb-8 leading-tight">
                Baca Buku <br><span class="text-accent-rose">Tanpa Batas.</span>
            </h1>
            <p class="text-slate-400 text-xs md:text-xl max-w-2xl mb-8 md:mb-12 leading-relaxed">
                Platform literasi digital modern untuk memudahkan manajemen koleksi buku secara real-time.
            </p>
            <div class="flex flex-row justify-center gap-3 md:gap-5 w-full">
                <a href="login.php" class="bg-accent-rose hover:bg-rose-500 text-white px-5 py-3 md:px-10 md:py-4 rounded-xl md:rounded-2xl font-black text-[10px] md:text-base transition-all shadow-xl">Mulai</a>
                <a href="?view=all" class="bg-slate-800 hover:bg-slate-700 text-white border border-slate-700 px-5 py-3 md:px-10 md:py-4 rounded-xl md:rounded-2xl font-bold text-[10px] md:text-base transition-all">Katalog</a>
            </div>
        </div>
    </div>

    <div class="max-w-6xl mx-auto px-4 -mt-10 md:-mt-16 mb-16 md:mb-20 relative z-20">
        <div class="grid grid-cols-3 gap-2 md:gap-6">
            <div class="bg-white p-3 md:p-10 rounded-xl md:rounded-[2rem] shadow-xl shadow-rose-900/5 flex flex-col items-center text-center border border-rose-50">
                <p class="text-slate-400 text-[7px] md:text-xs font-black uppercase tracking-tighter md:tracking-[0.2em] mb-1">Koleksi</p>
                <h3 class="text-lg md:text-4xl font-black text-main"><?= $total_buku ?></h3>
            </div>
            <div class="bg-white p-3 md:p-10 rounded-xl md:rounded-[2rem] shadow-xl shadow-rose-900/5 flex flex-col items-center text-center border border-rose-50">
                <p class="text-slate-400 text-[7px] md:text-xs font-black uppercase tracking-tighter md:tracking-[0.2em] mb-1">Anggota</p>
                <h3 class="text-lg md:text-4xl font-black text-main"><?= $total_anggota ?></h3>
            </div>
            <div class="bg-accent-rose p-3 md:p-10 rounded-xl md:rounded-[2rem] shadow-xl shadow-rose-600/30 flex flex-col items-center text-center">
                <p class="text-rose-100 text-[7px] md:text-xs font-black uppercase tracking-tighter md:tracking-[0.2em] mb-1">Status</p>
                <h3 class="text-lg md:text-4xl font-black text-white italic">On</h3>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <div class="max-w-7xl mx-auto px-4 pb-24 <?= $tampilkan_semua ? 'pt-10' : '' ?>">
        <div class="flex justify-between items-end mb-10 md:mb-16">
            <div>
                <h2 class="text-lg md:text-4xl font-black text-slate-900 uppercase tracking-tighter">
                    <?= $tampilkan_semua ? 'E-Catalog' : 'Populer' ?>
                </h2>
                <div class="h-1 md:h-2 w-10 md:w-20 bg-accent-rose mt-2 rounded-full"></div>
            </div>
            <a href="<?= $tampilkan_semua ? 'index.php' : '?view=all' ?>" class="flex items-center gap-2 text-main font-black text-[9px] md:text-sm uppercase tracking-widest transition-all">
                <?= $tampilkan_semua ? 'Home' : 'Semua' ?>
                <i class="fa-solid fa-arrow-right"></i>
            </a>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3 md:gap-10">
            <?php while($buku = mysqli_fetch_assoc($res_tampilan)): ?>
            <div class="group bg-white rounded-[1.2rem] md:rounded-[2.5rem] p-2 md:p-4 border border-rose-100 flex flex-col h-full shadow-sm hover:shadow-xl transition-all duration-300">
                <div class="h-44 md:h-80 w-full overflow-hidden rounded-[1rem] md:rounded-[2rem] bg-rose-50 relative mb-3 md:mb-6">
                    <img src="assets/img/<?= $buku['gambar'] ?>" 
                         alt="<?= $buku['judul_buku'] ?>" 
                         class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" 
                         onerror="this.src='https://via.placeholder.com/400x600?text=Cover'">
                    <div class="absolute bottom-2 left-2 right-2 bg-white/90 backdrop-blur-md px-2 py-1 md:px-4 md:py-2 rounded-lg md:rounded-2xl flex justify-between items-center shadow-sm">
                        <span class="text-[7px] md:text-[10px] font-black text-rose-800 uppercase">Stok</span>
                        <span class="text-[8px] md:text-xs font-black text-main"><?= $buku['stok'] ?></span>
                    </div>
                </div>
                
                <div class="px-1 flex flex-col flex-grow">
                    <div class="flex-grow mb-4 md:mb-8">
                        <h3 class="text-[11px] md:text-xl font-black text-slate-900 leading-tight mb-1 md:mb-3 line-clamp-2">
                            <?= $buku['judul_buku'] ?>
                        </h3>
                        <p class="text-[9px] md:text-xs font-bold italic text-slate-400 truncate"><?= $buku['pengarang'] ?></p>
                    </div>
                    
                    <a href="login.php" class="block w-full text-center bg-rose-50 hover:bg-accent-rose text-main hover:text-white py-2.5 md:py-4 rounded-lg md:rounded-2xl font-black text-[9px] md:text-xs uppercase tracking-tighter md:tracking-[0.2em] transition-all">
                        Pinjam
                    </a>
                </div>
            </div>
            <?php endwhile; ?>
        </div>
    </div>

<?php include 'include/footer_member.php'; ?>

</body>
</html>