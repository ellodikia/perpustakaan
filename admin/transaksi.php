<?php
session_start();
$koneksi = mysqli_connect("localhost", "root", "", "db_gabriel_perpustakaan");

if (!isset($_SESSION['level']) || $_SESSION['level'] != 'admin') {
    header("Location: login.php");
    exit;
}

if (isset($_GET['aksi']) && $_GET['aksi'] == 'kembali') {
    $id_tr = $_GET['id'];
    $id_bk = $_GET['buku'];
    $tgl_kembali = date('Y-m-d');

    mysqli_query($koneksi, "UPDATE transaksi SET tanggal_kembali = '$tgl_kembali', status = 'dikembalikan' 
                            WHERE id_transaksi = '$id_tr'");

    mysqli_query($koneksi, "UPDATE buku SET stok_tersedia = stok_tersedia + 1 WHERE id_buku = '$id_bk'");

    header("Location: transaksi.php?status=sukses");
    exit;
}

$query = "SELECT t.*, a.nama_siswa, b.judul_buku 
          FROM transaksi t 
          JOIN anggota a ON t.id_anggota = a.id_anggota 
          JOIN buku b ON t.id_buku = b.id_buku 
          ORDER BY t.status DESC, t.tanggal_pinjam DESC";
$tampil = mysqli_query($koneksi, $query);
?>

<!doctype html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Log Transaksi | BookLoan</title>
    <link rel="icon" href="../img/logo.jpeg" type="../image/png/jpeg" />
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        body { background-color: #0f172a; }
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="font-sans text-slate-300 overflow-x-hidden" x-data="{ selectedTr: null }">

    <div class="flex min-h-screen relative">
        <?php include "../include/sidebar_admin.php" ?>

        <main class="flex-1 w-full min-h-screen flex flex-col min-w-0">
            
            <div class="lg:hidden bg-slate-900 border-b border-slate-800 px-4 py-3 flex justify-between items-center sticky top-0 z-40">
                <span class="text-white font-black uppercase tracking-tighter text-sm">G-LIB <span class="text-rose-500">Log</span></span>
                <button @click="$dispatch('toggle-sidebar')" class="p-2 bg-slate-800 rounded-lg text-slate-400">
                    <i class="fa-solid fa-bars-staggered text-lg"></i>
                </button>
            </div>

            <div class="p-4 md:p-10 lg:p-12">
                <div class="mb-8 flex flex-col md:flex-row md:items-end justify-between gap-4">
                    <div>
                        <h1 class="text-2xl md:text-4xl font-black text-white uppercase tracking-tighter leading-none">
                            Log <span class="text-rose-500">Transaksi</span>
                        </h1>
                        <p class="text-slate-500 text-[9px] md:text-xs font-bold uppercase tracking-[0.2em] mt-2 italic">Monitoring Aktivitas Sirkulasi Buku</p>
                    </div>
                    <div class="bg-slate-800/40 border border-slate-700/50 px-5 py-3 rounded-2xl backdrop-blur-sm self-start">
                        <span class="text-[10px] font-black uppercase tracking-widest text-slate-400">Total Record: </span>
                        <span class="text-white font-mono font-bold"><?= mysqli_num_rows($tampil) ?></span>
                    </div>
                </div>

                <div class="hidden lg:block bg-slate-800/40 rounded-[2.5rem] border border-slate-700/50 overflow-hidden shadow-2xl">
                    <table class="w-full text-left text-sm">
                        <thead class="bg-slate-900/50 text-slate-400 uppercase text-[10px] font-black tracking-[0.2em]">
                            <tr>
                                <th class="px-8 py-5">Peminjam</th>
                                <th class="px-8 py-5">Buku</th>
                                <th class="px-8 py-5">Waktu</th>
                                <th class="px-8 py-5">Status</th>
                                <th class="px-8 py-5 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-700/30 text-slate-400">
                            <?php while($data = mysqli_fetch_assoc($tampil)): ?>
                            <tr class="hover:bg-slate-700/20 transition-colors">
                                <td class="px-8 py-5">
                                    <div class="text-white font-bold uppercase text-xs"><?= $data['nama_siswa'] ?></div>
                                    <div class="text-[9px] text-slate-500 font-mono tracking-tighter uppercase">ID: TR-<?= str_pad($data['id_transaksi'], 4, '0', STR_PAD_LEFT) ?></div>
                                </td>
                                <td class="px-8 py-5 text-xs font-medium"><?= $data['judul_buku'] ?></td>
                                <td class="px-8 py-5">
                                    <div class="text-[10px] font-bold text-slate-300"><?= date('d/m/Y', strtotime($data['tanggal_pinjam'])) ?></div>
                                    <div class="text-[9px] mt-1 <?= (strtotime(date('Y-m-d')) > strtotime($data['jatuh_tempo']) && $data['status'] == 'dipinjam') ? 'text-rose-500 font-black italic' : 'text-slate-500 font-bold' ?>">
                                        S/D <?= date('d/m/Y', strtotime($data['jatuh_tempo'])) ?>
                                    </div>
                                </td>
                                <td class="px-8 py-5">
                                    <?php if($data['status'] == 'dipinjam'): ?>
                                        <span class="bg-orange-500/10 text-orange-500 px-3 py-1 rounded-lg text-[9px] font-black border border-orange-500/20 uppercase tracking-widest italic">Dipinjam</span>
                                    <?php else: ?>
                                        <span class="bg-emerald-500/10 text-emerald-500 px-3 py-1 rounded-lg text-[9px] font-black border border-emerald-500/20 uppercase tracking-widest">Kembali</span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-8 py-5 text-right">
                                    <?php if($data['status'] == 'dipinjam'): ?>
                                        <a href="?aksi=kembali&id=<?= $data['id_transaksi'] ?>&buku=<?= $data['id_buku'] ?>" 
                                           onclick="return confirm('Konfirmasi pengembalian buku?')"
                                           class="bg-rose-500 hover:bg-rose-600 text-white text-[9px] font-black py-2 px-4 rounded-xl transition-all shadow-lg shadow-rose-900/20 uppercase tracking-widest">
                                           Selesaikan
                                        </a>
                                    <?php else: ?>
                                        <span class="text-slate-600 text-[10px] font-black uppercase tracking-widest"><i class="fa-solid fa-circle-check text-emerald-500"></i> Verified</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>

                <div class="lg:hidden space-y-4">
                    <?php mysqli_data_seek($tampil, 0); while($data = mysqli_fetch_assoc($tampil)): ?>
                    <div class="bg-slate-800/60 rounded-[1.5rem] border border-slate-700/50 p-5 relative overflow-hidden group">
                        <div class="flex justify-between items-start mb-4">
                            <div>
                                <div class="text-[8px] font-black text-slate-500 uppercase tracking-widest mb-1">Peminjam</div>
                                <div class="text-sm font-black text-white uppercase leading-tight"><?= $data['nama_siswa'] ?></div>
                            </div>
                            <?php if($data['status'] == 'dipinjam'): ?>
                                <span class="bg-orange-500/10 text-orange-400 px-2 py-1 rounded text-[8px] font-black border border-orange-500/20 uppercase">Pinjam</span>
                            <?php else: ?>
                                <span class="bg-emerald-500/10 text-emerald-400 px-2 py-1 rounded text-[8px] font-black border border-emerald-500/20 uppercase">Kembali</span>
                            <?php endif; ?>
                        </div>

                        <div class="text-[8px] font-black text-slate-500 uppercase tracking-widest mb-1">Judul Buku</div>
                        <div class="text-xs text-slate-300 mb-4 italic"><?= $data['judul_buku'] ?></div>

                        <div class="flex justify-between items-end border-t border-slate-700/50 pt-4">
                            <div>
                                <div class="text-[8px] font-black text-slate-500 uppercase tracking-widest">Deadline</div>
                                <div class="text-[10px] font-bold <?= (strtotime(date('Y-m-d')) > strtotime($data['jatuh_tempo']) && $data['status'] == 'dipinjam') ? 'text-rose-500 font-black' : 'text-slate-300' ?>">
                                    <?= date('d/m/Y', strtotime($data['jatuh_tempo'])) ?>
                                </div>
                            </div>
                            <?php if($data['status'] == 'dipinjam'): ?>
                                <a href="?aksi=kembali&id=<?= $data['id_transaksi'] ?>&buku=<?= $data['id_buku'] ?>" 
                                   class="bg-rose-500 text-white text-[9px] font-black py-2 px-4 rounded-xl uppercase tracking-widest shadow-lg shadow-rose-900/20">
                                   Kembalikan
                                </a>
                            <?php else: ?>
                                <i class="fa-solid fa-check-double text-emerald-500 text-lg opacity-30"></i>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php endwhile; ?>
                </div>

            </div>
            <?php include "../include/footer_admin.php" ?>
        </main>
    </div>

</body>
</html>