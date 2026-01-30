<?php
session_start();
$koneksi = mysqli_connect("localhost", "root", "", "db_gabriel_perpustakaan");

if (!isset($_SESSION['level']) || $_SESSION['level'] != 'admin') {
    header("Location: login.php");
    exit;
}

$query = "SELECT *, (stok - stok_tersedia) AS buku_dipinjam FROM buku ORDER BY judul_buku ASC";
$laporan = mysqli_query($koneksi, $query);

$total_stok = mysqli_query($koneksi, "SELECT SUM(stok) as total FROM buku");
$total_sedia = mysqli_query($koneksi, "SELECT SUM(stok_tersedia) as sedia FROM buku");
$data_total = mysqli_fetch_assoc($total_stok);
$data_sedia = mysqli_fetch_assoc($total_sedia);
?>

<!doctype html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Laporan Stok | BookLoan</title>
    <link rel="icon" href="../img/logo.jpeg" type="../image/png/jpeg" />
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        body { background-color: #0f172a; -webkit-tap-highlight-color: transparent; }
        [x-cloak] { display: none !important; }
        
        .custom-scroll::-webkit-scrollbar { width: 5px; }
        .custom-scroll::-webkit-scrollbar-track { background: transparent; }
        .custom-scroll::-webkit-scrollbar-thumb { background: #1e293b; border-radius: 10px; }
        .custom-scroll::-webkit-scrollbar-thumb:hover { background: #334155; }

        @media print {
            .no-print { display: none !important; }
            body { background-color: white !important; color: black !important; overflow: visible !important; }
            .print-card { border: 1px solid #ccc !important; background: transparent !important; color: black !important; }
            .main-content { padding: 0 !important; margin: 0 !important; height: auto !important; overflow: visible !important; }
            .fixed-container { display: block !important; height: auto !important; }
        }
    </style>
</head>
<body class="font-sans text-slate-300 overflow-hidden"> <div class="flex h-screen overflow-hidden relative fixed-container">
        
        <div class="no-print h-full shrink-0 border-r border-slate-800/50 shadow-2xl z-50">
            <?php include "../include/sidebar_admin.php" ?>
        </div>

        <main x-data="{ selectedBuku: null }" class="flex-1 min-w-0 h-full overflow-y-auto custom-scroll main-content bg-slate-950/20">
            
            <div class="lg:hidden no-print bg-slate-900/80 backdrop-blur-md border-b border-slate-800 px-4 py-3 flex justify-between items-center sticky top-0 z-40">
                <span class="text-white font-black uppercase tracking-tighter text-sm">BookLoan <span class="text-rose-500">Stok</span></span>
                <button @click="$dispatch('toggle-sidebar')" class="p-2 bg-slate-800 rounded-lg text-slate-400">
                    <i class="fa-solid fa-bars-staggered text-lg"></i>
                </button>
            </div>

            <div class="p-4 md:p-10 lg:p-12">
                <div class="mb-8 flex flex-row justify-between items-end gap-4">
                    <div>
                        <h1 class="text-xl md:text-4xl font-black text-white uppercase tracking-tighter leading-none">
                            Laporan <span class="text-rose-500">Stok</span>
                        </h1>
                        <p class="text-slate-500 text-[8px] md:text-xs font-bold uppercase tracking-[0.2em] mt-2 italic">Data Inventaris Real-Time</p>
                    </div>
                    <button onclick="window.print()" class="no-print bg-slate-800 hover:bg-slate-700 text-white text-[10px] font-black px-5 py-3 rounded-xl border border-slate-700 transition-all uppercase tracking-widest flex items-center gap-2 shadow-lg shadow-black/20">
                        <i class="fa-solid fa-print text-rose-500"></i> Cetak Laporan
                    </button>
                </div>

                <div class="grid grid-cols-3 gap-3 md:gap-6 mb-10">
                    <div class="bg-slate-800/40 border border-slate-700/50 p-4 md:p-8 rounded-[1.5rem] md:rounded-[2.5rem] print-card backdrop-blur-sm">
                        <p class="text-[7px] md:text-[10px] font-black text-slate-500 uppercase tracking-widest leading-none mb-2">Total Koleksi</p>
                        <h2 class="text-lg md:text-4xl font-black text-white"><?= $data_total['total'] ?? 0 ?></h2>
                    </div>
                    <div class="bg-slate-800/40 border border-slate-700/50 p-4 md:p-8 rounded-[1.5rem] md:rounded-[2.5rem] print-card backdrop-blur-sm">
                        <p class="text-[7px] md:text-[10px] font-black text-slate-500 uppercase tracking-widest leading-none mb-2">Tersedia</p>
                        <h2 class="text-lg md:text-4xl font-black text-emerald-500"><?= $data_sedia['sedia'] ?? 0 ?></h2>
                    </div>
                    <div class="bg-slate-800/40 border border-slate-700/50 p-4 md:p-8 rounded-[1.5rem] md:rounded-[2.5rem] print-card backdrop-blur-sm">
                        <p class="text-[7px] md:text-[10px] font-black text-slate-500 uppercase tracking-widest leading-none mb-2">Dipinjam</p>
                        <h2 class="text-lg md:text-4xl font-black text-rose-500"><?= ($data_total['total'] - $data_sedia['sedia']) ?></h2>
                    </div>
                </div>

                <div class="hidden lg:block bg-slate-800/40 rounded-[2.5rem] border border-slate-700/50 overflow-hidden backdrop-blur-sm shadow-2xl">
                    <table class="w-full text-left text-sm">
                        <thead class="bg-slate-900/50 text-slate-400 uppercase text-[10px] font-black tracking-[0.2em]">
                            <tr>
                                <th class="px-8 py-5">Judul Buku</th>
                                <th class="px-8 py-5 text-center">Stok Awal</th>
                                <th class="px-8 py-5 text-center">Keluar</th>
                                <th class="px-8 py-5 text-center">Sisa</th>
                                <th class="px-8 py-5 text-right">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-700/30 text-slate-400">
                            <?php mysqli_data_seek($laporan, 0); while($row = mysqli_fetch_assoc($laporan)): ?>
                            <tr class="hover:bg-slate-700/20 transition-colors">
                                <td class="px-8 py-5">
                                    <div class="text-white font-bold uppercase text-xs"><?= $row['judul_buku'] ?></div>
                                    <div class="text-[9px] text-slate-500 font-medium italic"><?= $row['pengarang'] ?></div>
                                </td>
                                <td class="px-8 py-5 text-center font-mono"><?= $row['stok'] ?></td>
                                <td class="px-8 py-5 text-center font-mono text-rose-400"><?= $row['buku_dipinjam'] ?></td>
                                <td class="px-8 py-5 text-center font-mono text-emerald-400 font-bold"><?= $row['stok_tersedia'] ?></td>
                                <td class="px-8 py-5 text-right uppercase text-[9px] font-black tracking-widest">
                                    <?php if($row['stok_tersedia'] <= 0): ?>
                                        <span class="text-rose-500 bg-rose-500/10 px-3 py-1 rounded-lg border border-rose-500/20 italic">Kosong</span>
                                    <?php elseif($row['stok_tersedia'] <= 3): ?>
                                        <span class="text-orange-500 bg-orange-500/10 px-3 py-1 rounded-lg border border-orange-500/20 italic">Kritis</span>
                                    <?php else: ?>
                                        <span class="text-emerald-500 bg-emerald-500/10 px-3 py-1 rounded-lg border border-emerald-500/20">Aman</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>

                <div class="lg:hidden grid grid-cols-3 gap-3 mb-10">
                    <?php mysqli_data_seek($laporan, 0); while($row = mysqli_fetch_assoc($laporan)): ?>
                    <div @click="selectedBuku = <?= htmlspecialchars(json_encode($row)) ?>" 
                         class="bg-slate-800/60 rounded-2xl border border-slate-700/50 p-3 flex flex-col items-center justify-center text-center active:scale-95 transition-all shadow-lg">
                        <div class="w-8 h-12 bg-slate-900 rounded-md overflow-hidden mb-2 shadow-lg ring-1 ring-white/10">
                             <img src="../assets/img/<?= $row['gambar'] ?: 'default_book.png' ?>" class="w-full h-full object-cover opacity-60" 
                                  onerror="this.src='https://via.placeholder.com/150x225?text=No+Cover'">
                        </div>
                        <div class="text-[8px] font-black text-white uppercase truncate w-full"><?= $row['judul_buku'] ?></div>
                        <div class="text-[10px] font-mono <?= $row['stok_tersedia'] <= 3 ? 'text-rose-500' : 'text-emerald-500' ?> mt-1">
                            <?= $row['stok_tersedia'] ?>
                        </div>
                    </div>
                    <?php endwhile; ?>
                </div>
            </div>

            <div x-show="selectedBuku" 
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 scale-90"
                 x-transition:enter-end="opacity-100 scale-100"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100 scale-100"
                 x-transition:leave-end="opacity-0 scale-90"
                 class="fixed inset-0 z-[100] flex items-center justify-center p-6 bg-slate-950/80 backdrop-blur-md no-print"
                 x-cloak>
                
                <div class="bg-slate-900 border border-slate-700 w-full max-w-xs rounded-[2.5rem] overflow-hidden shadow-2xl relative" @click.away="selectedBuku = null">
                    <button @click="selectedBuku = null" class="absolute top-6 right-6 text-slate-500 hover:text-white transition-colors">
                        <i class="fa-solid fa-circle-xmark text-2xl"></i>
                    </button>
                    
                    <div class="p-8 text-center">
                        <template x-if="selectedBuku">
                            <div>
                                <h3 class="text-lg font-black text-white uppercase tracking-tighter mb-1 leading-tight" x-text="selectedBuku.judul_buku"></h3>
                                <p class="text-[9px] text-slate-500 font-bold uppercase tracking-widest mb-6" x-text="selectedBuku.pengarang"></p>
                                
                                <div class="space-y-3">
                                    <div class="flex justify-between items-center bg-slate-800/50 p-4 rounded-2xl border border-slate-700/50">
                                        <span class="text-[9px] font-black text-slate-500 uppercase tracking-widest">Stok Awal</span>
                                        <span class="text-white font-mono font-bold" x-text="selectedBuku.stok"></span>
                                    </div>
                                    <div class="flex justify-between items-center bg-slate-800/50 p-4 rounded-2xl border border-slate-700/50">
                                        <span class="text-[9px] font-black text-slate-500 uppercase tracking-widest">Keluar</span>
                                        <span class="text-rose-500 font-mono font-bold" x-text="selectedBuku.buku_dipinjam"></span>
                                    </div>
                                    <div class="flex justify-between items-center bg-emerald-500/10 p-4 rounded-2xl border border-emerald-500/20">
                                        <span class="text-[9px] font-black text-emerald-500 uppercase tracking-widest">Tersedia</span>
                                        <span class="text-emerald-500 font-black text-xl font-mono" x-text="selectedBuku.stok_tersedia"></span>
                                    </div>
                                </div>

                                <button @click="selectedBuku = null" class="w-full mt-8 bg-slate-800 hover:bg-slate-700 text-white py-4 rounded-2xl font-black uppercase text-[10px] tracking-[0.2em] transition-colors border border-slate-700">Tutup</button>
                            </div>
                        </template>
                    </div>
                </div>
            </div>

        </main>
    </div>

</body>
</html>