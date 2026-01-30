<?php
$koneksi = mysqli_connect("localhost", "root", "", "db_gabriel_perpustakaan");

$totalBuku = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) as total FROM buku"))['total'];
$totalAnggota = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) as total FROM anggota"))['total'];
$sedangDipinjam = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) as total FROM transaksi WHERE status = 'dipinjam'"))['total'];
$stokKritis = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) as total FROM buku WHERE stok_tersedia < 3"))['total'];

$queryTransaksi = "SELECT t.*, a.nama_siswa, b.judul_buku 
                   FROM transaksi t
                   JOIN anggota a ON t.id_anggota = a.id_anggota
                   JOIN buku b ON t.id_buku = b.id_buku
                   ORDER BY t.tanggal_pinjam DESC LIMIT 5";
$daftarTransaksi = mysqli_query($koneksi, $queryTransaksi);
?>

<!doctype html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Admin Dashboard | G-LIB</title>
    <link rel="icon" href="../img/logo.jpeg" type="../image/png/jpeg" />
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        body { background-color: #0f172a; }
        ::-webkit-scrollbar { width: 5px; }
        ::-webkit-scrollbar-thumb { background: #1e293b; border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: #e11d48; }
    </style>
</head>
<body class="font-sans text-slate-300 overflow-x-hidden" x-data>

<body class="font-sans text-slate-300 overflow-x-hidden" x-data>

    <div class="flex min-h-screen relative">
        <?php include "../include/sidebar_admin.php" ?>

        <main class="flex-1 w-full min-h-screen flex flex-col overflow-x-hidden">
            
            <div class="lg:hidden bg-slate-900/95 backdrop-blur border-b border-slate-800 px-4 py-3 flex justify-between items-center sticky top-0 z-40">
                <div class="flex items-center gap-2">
                    <div class="w-7 h-7 bg-rose-500 rounded flex items-center justify-center text-white">
                        <i class="fa-solid fa-book-bookmark text-xs"></i>
                    </div>
                    <span class="text-white font-black uppercase tracking-tighter text-sm">BookLoan</span>
                </div>
                <button @click="$dispatch('toggle-sidebar')" class="p-2 bg-slate-800 rounded-lg text-slate-400 active:scale-95 transition-all">
                    <i class="fa-solid fa-bars-staggered text-lg"></i>
                </button>
            </div>

            <div class="p-4 md:p-10 lg:p-12">
                
                <div class="mb-8 flex flex-row justify-between items-center gap-2">
                    <div>
                        <h1 class="text-xl md:text-4xl font-black text-white uppercase tracking-tighter leading-none">
                            Admin <span class="text-rose-500">Dashboard</span>
                        </h1>
                        <p class="text-slate-500 text-[8px] md:text-xs font-bold uppercase tracking-[0.2em] mt-1">Sistem Perpus</p>
                    </div>
                    <div class="flex items-center gap-2 bg-slate-800/40 p-1.5 pr-3 rounded-xl border border-slate-700/50">
                        <div class="w-7 h-7 md:w-10 md:h-10 bg-rose-500 rounded-lg flex items-center justify-center text-white shadow-lg shadow-rose-500/20">
                            <i class="fa-solid fa-shield-halved text-xs md:text-base"></i>
                        </div>
                        <span class="text-[8px] md:text-[10px] font-black uppercase tracking-widest text-white hidden xs:block">Admin</span>
                    </div>
                </div>

                <div class="grid grid-cols-2 xl:grid-cols-4 gap-3 md:gap-6 mb-10">
                    <div class="bg-slate-800/50 p-4 md:p-6 rounded-2xl md:rounded-[2rem] border border-slate-700/50 hover:border-blue-500/30 transition-all group relative overflow-hidden">
                        <div class="p-2 bg-blue-500/10 rounded-lg text-blue-500 w-fit mb-2 md:mb-4 text-xs md:text-base"><i class="fa-solid fa-book"></i></div>
                        <p class="text-[8px] md:text-[10px] font-black uppercase tracking-widest text-slate-500">Buku</p>
                        <h3 class="text-xl md:text-3xl font-black text-white mt-1 group-hover:text-blue-400 transition-colors"><?= $totalBuku ?></h3>
                    </div>

                    <div class="bg-slate-800/50 p-4 md:p-6 rounded-2xl md:rounded-[2rem] border border-slate-700/50 hover:border-emerald-500/30 transition-all group relative overflow-hidden">
                        <div class="p-2 bg-emerald-500/10 rounded-lg text-emerald-500 w-fit mb-2 md:mb-4 text-xs md:text-base"><i class="fa-solid fa-users"></i></div>
                        <p class="text-[8px] md:text-[10px] font-black uppercase tracking-widest text-slate-500">Anggota</p>
                        <h3 class="text-xl md:text-3xl font-black text-white mt-1 group-hover:text-emerald-400 transition-colors"><?= $totalAnggota ?></h3>
                    </div>

                    <div class="bg-slate-800/50 p-4 md:p-6 rounded-2xl md:rounded-[2rem] border border-slate-700/50 hover:border-orange-500/30 transition-all group relative overflow-hidden">
                        <div class="p-2 bg-orange-500/10 rounded-lg text-orange-500 w-fit mb-2 md:mb-4 text-xs md:text-base"><i class="fa-solid fa-hand-holding-heart"></i></div>
                        <p class="text-[8px] md:text-[10px] font-black uppercase tracking-widest text-slate-500">Pinjam</p>
                        <h3 class="text-xl md:text-3xl font-black text-orange-400 mt-1"><?= $sedangDipinjam ?></h3>
                    </div>

                    <div class="bg-slate-800/50 p-4 md:p-6 rounded-2xl md:rounded-[2rem] border border-slate-700/50 hover:border-rose-500/50 transition-all group relative overflow-hidden">
                        <div class="p-2 bg-rose-500/10 rounded-lg text-rose-500 w-fit mb-2 md:mb-4 animate-pulse text-xs md:text-base"><i class="fa-solid fa-triangle-exclamation"></i></div>
                        <p class="text-[8px] md:text-[10px] font-black uppercase tracking-widest text-slate-500">Kritis</p>
                        <h3 class="text-xl md:text-3xl font-black text-rose-500 mt-1"><?= $stokKritis ?></h3>
                    </div>
                </div>

                <div class="bg-slate-800/40 rounded-3xl md:rounded-[2.5rem] border border-slate-700/50 overflow-hidden backdrop-blur-sm">
                    <div class="p-5 md:p-8 border-b border-slate-700/50 flex flex-row justify-between items-center gap-4">
                        <div class="text-left">
                            <h2 class="text-sm md:text-lg font-black text-white uppercase tracking-tighter leading-none">Transaksi</h2>
                            <p class="text-[7px] md:text-[10px] font-bold text-slate-500 uppercase tracking-widest mt-1">Real-time update</p>
                        </div>
                        <a href="transaksi.php" class="text-[8px] md:text-[10px] font-black text-rose-500 border border-rose-500/30 px-3 py-2 md:px-6 md:py-3 rounded-lg md:rounded-xl hover:bg-rose-500 hover:text-white transition-all uppercase tracking-widest">Lihat Semua</a>
                    </div>
                    
                    <div class="overflow-x-auto custom-scrollbar">
                        <table class="w-full text-left text-xs md:text-sm whitespace-nowrap min-w-[600px] md:min-w-full">
                            <thead class="bg-slate-900/50 text-slate-400 uppercase text-[9px] md:text-[10px] font-black tracking-[0.2em]">
                                <tr>
                                    <th class="px-5 md:px-8 py-4 md:py-5">Siswa</th>
                                    <th class="px-5 md:px-8 py-4 md:py-5">Buku</th>
                                    <th class="px-5 md:px-8 py-4 md:py-5">Tanggal</th>
                                    <th class="px-5 md:px-8 py-4 md:py-5 text-right">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-700/30 text-slate-400">
                                <?php if(mysqli_num_rows($daftarTransaksi) > 0): ?>
                                    <?php while($row = mysqli_fetch_assoc($daftarTransaksi)): ?>
                                    <tr class="hover:bg-slate-700/20 transition-all group">
                                        <td class="px-5 md:px-8 py-4 md:py-6 flex items-center gap-2 md:gap-3">
                                            <div class="w-6 h-6 md:w-8 md:h-8 bg-slate-700 rounded flex items-center justify-center text-[8px] md:text-[10px] font-black text-white uppercase"><?= substr($row['nama_siswa'], 0, 1) ?></div>
                                            <span class="font-bold text-white text-[10px] md:text-sm group-hover:text-rose-400 transition-colors uppercase"><?= $row['nama_siswa'] ?></span>
                                        </td>
                                        <td class="px-5 md:px-8 py-4 md:py-6 text-[10px] md:text-sm"><?= $row['judul_buku'] ?></td>
                                        <td class="px-5 md:px-8 py-4 md:py-6 font-mono text-[9px] md:text-xs"><?= date('d M Y', strtotime($row['tanggal_pinjam'])) ?></td>
                                        <td class="px-5 md:px-8 py-4 md:py-6 text-right">
                                            <span class="px-2 py-1 md:px-4 md:py-1.5 rounded-full text-[7px] md:text-[9px] font-black uppercase tracking-widest border <?= $row['status'] == 'dipinjam' ? 'bg-orange-500/10 text-orange-500 border-orange-500/20' : 'bg-emerald-500/10 text-emerald-500 border-emerald-500/20' ?>">
                                                <?= $row['status'] ?>
                                            </span>
                                        </td>
                                    </tr>
                                    <?php endwhile; ?>
                                <?php else: ?>
                                    <tr><td colspan="4" class="px-5 md:px-8 py-10 text-center uppercase text-[10px] italic">Belum ada transaksi</td></tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <?php include "../include/footer_admin.php" ?>
        </main>
    </div>

</body>
</html>