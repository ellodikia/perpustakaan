<?php
session_start();
$koneksi = mysqli_connect("localhost", "root", "", "db_gabriel_perpustakaan");

if (!isset($_SESSION['level']) || $_SESSION['level'] != 'siswa') {
    header("Location: ../login.php");
    exit;
}

$id_member = $_SESSION['id_anggota'];

if (isset($_GET['id_buku'])) {
    $id_buku = mysqli_real_escape_string($koneksi, $_GET['id_buku']);
    $tgl_pinjam = date('Y-m-d');
    $jatuh_tempo = date('Y-m-d', strtotime('+7 days'));

    $cek = mysqli_query($koneksi, "SELECT stok_tersedia FROM buku WHERE id_buku = '$id_buku'");
    $b = mysqli_fetch_assoc($cek);

    if ($b['stok_tersedia'] > 0) {
        $query_pinjam = "INSERT INTO transaksi (id_anggota, id_buku, tanggal_pinjam, jatuh_tempo, status) 
                         VALUES ('$id_member', '$id_buku', '$tgl_pinjam', '$jatuh_tempo', 'dipinjam')";
        
        if (mysqli_query($koneksi, $query_pinjam)) {
            mysqli_query($koneksi, "UPDATE buku SET stok_tersedia = stok_tersedia - 1 WHERE id_buku = '$id_buku'");
            echo "<script>alert('Buku berhasil dipinjam!'); window.location='riwayat_peminjaman.php';</script>";
        }
    } else {
        echo "<script>alert('Maaf, stok baru saja habis!'); window.location='pinjam_buku.php';</script>";
    }
}

$where_clause = "WHERE stok_tersedia > 0";
if (isset($_GET['search'])) {
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
    <link rel="icon" href="../img/logo.jpeg" type="../image/png/jpeg" />
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <style>
        body { background-color: #0f172a; }
        .accent-rose { color: #e11d48; }
        .bg-accent-rose { background-color: #e11d48; }
        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;  
            overflow: hidden;
        }
    </style>
</head>
<body class="font-sans text-slate-300 min-h-screen flex flex-col">
    
    <?php include "../include/header_member.php" ?>

    <div class="max-w-7xl mx-auto px-4 py-8 md:py-12 flex-grow w-full">
        <div class="flex flex-col md:flex-row justify-between items-center gap-6 mb-12">
            <div>
                <h1 class="text-2xl md:text-4xl font-black text-white uppercase tracking-tighter">
                    Katalog <span class="text-accent-rose">Buku</span>
                </h1>
                <div class="h-1 w-12 bg-accent-rose mt-2 rounded-full"></div>
            </div>

            <form action="" method="GET" class="w-full md:w-96 relative">
                <input type="text" name="search" value="<?= isset($_GET['search']) ? $_GET['search'] : '' ?>"
                    placeholder="Cari judul atau pengarang..." 
                    class="w-full bg-slate-800 border border-slate-700 text-white pl-12 pr-4 py-3.5 rounded-2xl focus:ring-2 focus:ring-rose-500/50 focus:border-accent-rose outline-none transition-all font-bold text-xs">
                <i class="fa-solid fa-magnifying-glass absolute left-5 top-1/2 -translate-y-1/2 text-slate-500 text-xs"></i>
            </form>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 md:gap-8">
            <?php if (mysqli_num_rows($daftar_buku) > 0): ?>
                <?php while($row = mysqli_fetch_assoc($daftar_buku)): ?>
                <div class="group bg-slate-800/50 rounded-[1.5rem] md:rounded-[2rem] border border-slate-700 overflow-hidden hover:border-rose-500/50 transition-all duration-300 shadow-xl flex flex-col">
                    
                    <div class="h-44 md:h-72 bg-slate-900 relative overflow-hidden">
                        <img src="../assets/img/<?= $row['gambar'] ?: 'default_book.png' ?>" 
                             class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500 opacity-90" 
                             alt="cover"
                             onerror="this.src='https://via.placeholder.com/400x600?text=No+Cover'">
                        
                        <div class="absolute top-3 right-3 bg-slate-900/90 backdrop-blur-md border border-white/10 text-rose-400 text-[8px] md:text-[10px] font-black px-2 py-1 rounded-lg">
                            STOK: <?= $row['stok_tersedia'] ?>
                        </div>
                    </div>

                    <div class="p-3 md:p-6 flex-1 flex flex-col">
                        <h3 class="text-white font-black text-xs md:text-base leading-tight mb-1 line-clamp-2 group-hover:text-rose-400 transition-colors">
                            <?= $row['judul_buku'] ?>
                        </h3>
                        <p class="text-[9px] md:text-xs text-slate-500 mb-4 italic font-medium truncate">
                            By: <?= $row['pengarang'] ?>
                        </p>
                        
                        <div class="mt-auto">
                            <a href="?id_buku=<?= $row['id_buku'] ?>" 
                               onclick="return confirm('Pinjam buku <?= $row['judul_buku'] ?>?')"
                               class="block w-full text-center bg-slate-900 hover:bg-accent-rose text-slate-400 hover:text-white font-black py-2.5 md:py-3 rounded-xl transition-all text-[9px] md:text-xs uppercase tracking-widest">
                                Pinjam
                            </a>
                        </div>
                    </div>
                </div>
                <?php endwhile; ?>
            <?php else: ?>
                <div class="col-span-full py-24 text-center">
                    <div class="w-20 h-20 bg-slate-800 rounded-full flex items-center justify-center mx-auto mb-4 border border-slate-700">
                        <i class="fa-solid fa-book-open text-slate-600 text-2xl"></i>
                    </div>
                    <p class="text-slate-500 font-bold uppercase tracking-widest text-xs">Buku tidak tersedia atau tidak ditemukan</p>
                    <a href="pinjam_buku.php" class="inline-block mt-4 text-accent-rose text-xs font-black uppercase border-b border-rose-500/30">Segarkan Katalog</a>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <?php include "../include/footer_member.php" ?>

</body>
</html>