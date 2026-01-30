<?php
session_start();
$koneksi = mysqli_connect("localhost", "root", "", "db_gabriel_perpustakaan");

if (!isset($_SESSION['level']) || $_SESSION['level'] != 'siswa') {
    header("Location: ../login.php");
    exit();
}

$id_member = $_SESSION['id_anggota'];

if (isset($_GET['id_buku'])) {
    $id_buku = mysqli_real_escape_string($koneksi, $_GET['id_buku']);
    $tgl_pinjam = date('Y-m-d');
    $jatuh_tempo = date('Y-m-d', strtotime('+7 days'));

    $cek_double = mysqli_query($koneksi, "SELECT * FROM transaksi WHERE id_anggota = '$id_member' AND id_buku = '$id_buku' AND status = 'dipinjam'");
    
    if (mysqli_num_rows($cek_double) > 0) {
        echo "<script>alert('Anda masih meminjam buku ini!'); window.location='pinjam_buku.php';</script>";
        exit();
    }

    $cek_stok = mysqli_query($koneksi, "SELECT stok_tersedia, judul_buku FROM buku WHERE id_buku = '$id_buku'");
    $b = mysqli_fetch_assoc($cek_stok);

    if ($b && $b['stok_tersedia'] > 0) {
        $query_pinjam = "INSERT INTO transaksi (id_anggota, id_buku, tanggal_pinjam, jatuh_tempo, status) 
                         VALUES ('$id_member', '$id_buku', '$tgl_pinjam', '$jatuh_tempo', 'dipinjam')";
        
        if (mysqli_query($koneksi, $query_pinjam)) {
            mysqli_query($koneksi, "UPDATE buku SET stok_tersedia = stok_tersedia - 1 WHERE id_buku = '$id_buku'");
            echo "<script>alert('Buku \"".$b['judul_buku']."\" berhasil dipinjam!'); window.location='riwayat_peminjaman.php';</script>";
            exit();
        }
    } else {
        echo "<script>alert('Maaf, stok baru saja habis!'); window.location='pinjam_buku.php';</script>";
        exit();
    }
}

$where_clause = "WHERE stok_tersedia > 0";
if (isset($_GET['search']) && !empty($_GET['search'])) {
    $keyword = mysqli_real_escape_string($koneksi, $_GET['search']);
    $where_clause .= " AND (judul_buku LIKE '%$keyword%' OR pengarang LIKE '%$keyword%')";
}

$query_buku = "SELECT * FROM buku $where_clause ORDER BY judul_buku ASC";
$daftar_buku = mysqli_query($koneksi, $query_buku);
?>

<!doctype html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Katalog Buku | BookLoan</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <style>
        body { background-color: #0f172a; scroll-behavior: smooth; }
        .accent-rose { color: #e11d48; }
        .bg-accent-rose { background-color: #e11d48; }
        .book-aspect { aspect-ratio: 2 / 3; }
    </style>
</head>
<body class="font-sans text-slate-300 min-h-screen flex flex-col">
    
    <?php include "../include/header_member.php" ?>

    <main class="max-w-7xl mx-auto px-4 py-8 md:py-12 flex-grow w-full">
        <div class="flex flex-col md:flex-row justify-between items-center gap-6 mb-12">
            <div>
                <h1 class="text-3xl md:text-5xl font-black text-white uppercase tracking-tighter">
                    Katalog <span class="text-accent-rose">Buku</span>
                </h1>
                <p class="text-slate-500 text-xs md:text-sm font-bold uppercase tracking-widest mt-2">Temukan ilmu dalam setiap lembaran</p>
            </div>

            <form action="" method="GET" class="w-full md:w-96 relative group">
                <input type="text" name="search" value="<?= htmlspecialchars($_GET['search'] ?? '') ?>"
                    placeholder="Cari judul atau pengarang..." 
                    class="w-full bg-slate-800/50 border border-slate-700 text-white pl-12 pr-4 py-4 rounded-2xl focus:ring-2 focus:ring-rose-500/50 focus:border-accent-rose outline-none transition-all font-bold text-xs backdrop-blur-sm">
                <i class="fa-solid fa-magnifying-glass absolute left-5 top-1/2 -translate-y-1/2 text-slate-500 text-xs group-focus-within:text-rose-500 transition-colors"></i>
            </form>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6 md:gap-10">
            <?php if (mysqli_num_rows($daftar_buku) > 0): ?>
                <?php while($row = mysqli_fetch_assoc($daftar_buku)): ?>
                <div class="group bg-slate-800/30 rounded-[2rem] border border-slate-700/50 overflow-hidden hover:border-rose-500/50 transition-all duration-500 shadow-2xl flex flex-col hover:-translate-y-2">
                    
                    <div class="book-aspect bg-slate-900 relative overflow-hidden">
                        <img src="../assets/img/<?= $row['gambar'] ?: 'default_book.png' ?>" 
                             class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700 opacity-90 group-hover:opacity-100" 
                             alt="<?= htmlspecialchars($row['judul_buku']) ?>"
                             onerror="this.src='https://via.placeholder.com/400x600?text=No+Cover'">
                        
                        <div class="absolute top-4 right-4 bg-slate-900/80 backdrop-blur-md border border-white/10 text-rose-400 text-[10px] font-black px-3 py-1.5 rounded-xl shadow-lg">
                            <?= $row['stok_tersedia'] ?> UNIT
                        </div>
                    </div>

                    <div class="p-5 md:p-8 flex-1 flex flex-col">
                        <h3 class="text-white font-black text-sm md:text-lg leading-tight mb-2 line-clamp-2 group-hover:text-rose-400 transition-colors uppercase tracking-tight">
                            <?= $row['judul_buku'] ?>
                        </h3>
                        <p class="text-[10px] md:text-xs text-slate-500 mb-6 italic font-bold uppercase tracking-widest">
                            <?= $row['pengarang'] ?>
                        </p>
                        
                        <div class="mt-auto">
                            <a href="?id_buku=<?= $row['id_buku'] ?>" 
                               onclick="return confirm('Pinjam buku ini? Durasi pinjam adalah 7 hari.')"
                               class="block w-full text-center bg-slate-900 border border-slate-700 hover:bg-accent-rose hover:border-accent-rose text-slate-300 hover:text-white font-black py-3.5 rounded-2xl transition-all text-[10px] md:text-xs uppercase tracking-[0.2em] shadow-lg active:scale-95">
                                Pinjam Sekarang
                            </a>
                        </div>
                    </div>
                </div>
                <?php endwhile; ?>
            <?php else: ?>
                <div class="col-span-full py-32 text-center">
                    <div class="w-24 h-24 bg-slate-800/50 rounded-full flex items-center justify-center mx-auto mb-6 border border-slate-700 shadow-inner">
                        <i class="fa-solid fa-book-open text-slate-600 text-3xl"></i>
                    </div>
                    <p class="text-slate-500 font-black uppercase tracking-[0.3em] text-xs">Buku tidak tersedia</p>
                    <a href="pinjam_buku.php" class="inline-block mt-6 px-8 py-3 bg-slate-800 text-white rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-slate-700 transition-all shadow-lg">Reset Pencarian</a>
                </div>
            <?php endif; ?>
        </div>
    </main>

    <?php include "../include/footer_member.php" ?>

</body>
</html>