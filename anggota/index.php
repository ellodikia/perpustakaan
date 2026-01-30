<?php
session_start();
$koneksi = mysqli_connect("localhost", "root", "", "db_gabriel_perpustakaan");

if (!isset($_SESSION['level']) || $_SESSION['level'] != 'siswa') {
    header("Location: ../login.php");
    exit;
}

$id_member = $_SESSION['id_anggota'];

$res_pinjam = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM transaksi WHERE id_anggota = '$id_member' AND status = 'dipinjam'");
$total_pinjam = mysqli_fetch_assoc($res_pinjam)['total'];

$tgl_skrg = date('Y-m-d');
$res_telat = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM transaksi WHERE id_anggota = '$id_member' AND status = 'dipinjam' AND jatuh_tempo < '$tgl_skrg'");
$total_telat = mysqli_fetch_assoc($res_telat)['total'];

$search_query = "";
if (isset($_GET['search'])) {
    $keyword = mysqli_real_escape_string($koneksi, $_GET['search']);
    $search_query = " WHERE judul_buku LIKE '%$keyword%' OR pengarang LIKE '%$keyword%'";
}
$res_buku = mysqli_query($koneksi, "SELECT * FROM buku $search_query LIMIT 8");
?>

<!doctype html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Anggota | BookLoan</title>
    <link rel="icon" href="../img/logo.jpeg" type="../image/png/jpeg" />
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <style>
        body { background-color: #0f172a; }
        .accent-rose { color: #e11d48; }
        .bg-accent-rose { background-color: #e11d48; }
    </style>
</head>
<body class="font-sans text-slate-300">
    
    <?php include "../include/header_member.php" ?>

    <div class="max-w-7xl mx-auto px-4 py-10 md:py-16">
        
        <div class="mb-12 flex flex-col md:flex-row justify-between items-center gap-6">
            <div class="text-center md:text-left">
                <h1 class="text-3xl md:text-5xl font-black text-white tracking-tight">
                    Halo, <span class="text-accent-rose"><?= ucfirst($_SESSION['username']) ?>!</span> 👋
                </h1>
                <p class="text-slate-400 mt-2 text-sm md:text-base font-medium">Mau baca buku apa hari ini?</p>
            </div>
            
            <form action="" method="GET" class="w-full md:w-96 relative">
                <input type="text" name="search" value="<?= isset($_GET['search']) ? $_GET['search'] : '' ?>"
                    placeholder="Cari judul atau pengarang..." 
                    class="w-full bg-slate-800 border border-slate-700 text-white pl-12 pr-4 py-4 rounded-2xl focus:ring-2 focus:ring-rose-500/50 focus:border-accent-rose outline-none transition-all font-bold text-sm">
                <i class="fa-solid fa-magnifying-glass absolute left-5 top-1/2 -translate-y-1/2 text-slate-500"></i>
                <button type="submit" class="hidden"></button>
            </form>
        </div>

        <div class="grid grid-cols-2 gap-3 md:gap-8 mb-12">
            <div class="bg-gradient-to-br from-rose-600 to-rose-800 rounded-2xl md:rounded-[2.5rem] p-5 md:p-8 flex justify-between items-center shadow-xl shadow-rose-900/40">
                <div>
                    <p class="text-rose-100 font-black uppercase text-[8px] md:text-xs tracking-widest">Dipinjam</p>
                    <h2 class="text-3xl md:text-6xl font-black text-white mt-1"><?= $total_pinjam ?></h2>
                </div>
                <i class="fa-solid fa-book-bookmark text-2xl md:text-5xl text-white/30"></i>
            </div>

            <div class="<?= $total_telat > 0 ? 'bg-orange-600 shadow-orange-900/40' : 'bg-slate-800 border border-slate-700' ?> rounded-2xl md:rounded-[2.5rem] p-5 md:p-8 flex justify-between items-center shadow-xl">
                <div>
                    <p class="<?= $total_telat > 0 ? 'text-orange-100' : 'text-slate-500' ?> font-black uppercase text-[8px] md:text-xs tracking-widest">Terlambat</p>
                    <h2 class="text-3xl md:text-6xl font-black text-white mt-1"><?= $total_telat ?></h2>
                </div>
                <i class="fa-solid fa-clock-rotate-left text-2xl md:text-5xl text-white/20"></i>
            </div>
        </div>

        <div class="space-y-10">
            <div class="flex flex-wrap gap-3">
                <a href="riwayat_peminjaman.php" class="bg-slate-800 hover:bg-slate-700 text-white px-6 py-4 rounded-xl font-black text-[10px] md:text-xs uppercase tracking-widest transition-all border border-slate-700 flex items-center gap-2">
                    <i class="fa-solid fa-clock-rotate-left"></i> Riwayat Saya
                </a>
            </div>

            <div>
                <h3 class="text-white font-black text-lg uppercase tracking-widest mb-6 flex items-center gap-3">
                    <span class="w-8 h-1 bg-accent-rose rounded-full"></span>
                    <?= isset($_GET['search']) ? 'Hasil Pencarian' : 'Koleksi Buku' ?>
                </h3>
                
                <div class="grid grid-cols-2 md:grid-cols-4 gap-3 md:gap-8">
                    <?php while($buku = mysqli_fetch_assoc($res_buku)): ?>
                    <div class="group bg-slate-800 rounded-2xl md:rounded-[2rem] p-3 md:p-4 border border-slate-700 hover:border-rose-500/50 transition-all duration-300">
                        <div class="h-40 md:h-64 w-full overflow-hidden rounded-xl md:rounded-[1.5rem] mb-4 relative">
                            <img src="../assets/img/<?= $buku['gambar'] ?>" 
                                 class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500"
                                 onerror="this.src='https://via.placeholder.com/400x600?text=Cover'">
                            <div class="absolute top-2 right-2 bg-slate-900/80 backdrop-blur-md px-2 py-1 rounded-lg text-[8px] md:text-[10px] font-black text-rose-400">
                                <?= $buku['stok'] ?> Unit
                            </div>
                        </div>
                        <h4 class="text-white font-black text-[10px] md:text-sm leading-tight line-clamp-2 mb-1 group-hover:text-rose-400 transition-colors">
                            <?= $buku['judul_buku'] ?>
                        </h4>
                        <p class="text-slate-500 text-[8px] md:text-[11px] font-bold italic mb-4"><?= $buku['pengarang'] ?></p>
                        
                        <a href="pinjam_aksi.php?id=<?= $buku['id_buku'] ?>" 
                           class="block text-center bg-slate-900 group-hover:bg-accent-rose text-slate-400 group-hover:text-white py-2 md:py-3 rounded-lg md:rounded-xl font-black text-[8px] md:text-[10px] uppercase tracking-widest transition-all">
                           Pinjam
                        </a>
                    </div>
                    <?php endwhile; ?>
                    
                    <?php if(mysqli_num_rows($res_buku) == 0): ?>
                        <div class="col-span-full py-20 text-center">
                            <i class="fa-solid fa-book-open text-5xl text-slate-700 mb-4"></i>
                            <p class="text-slate-500 font-bold tracking-widest uppercase">Buku tidak ditemukan...</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <?php include "../include/footer_member.php" ?>

</body>
</html>