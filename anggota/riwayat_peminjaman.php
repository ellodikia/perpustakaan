<?php
session_start();
$koneksi = mysqli_connect("localhost", "root", "", "db_gabriel_perpustakaan");

if (!isset($_SESSION['level']) || $_SESSION['level'] != 'siswa') {
    header("Location: ../login.php");
    exit;
}

$id_member = $_SESSION['id_anggota']; 

$query = "SELECT t.*, b.judul_buku, b.pengarang, b.gambar 
          FROM transaksi t 
          JOIN buku b ON t.id_buku = b.id_buku 
          WHERE t.id_anggota = '$id_member' 
          ORDER BY t.id_transaksi DESC";
$riwayat = mysqli_query($koneksi, $query);
?>

<!doctype html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Peminjaman |BookLoan</title>
    <link rel="icon" href="../img/logo.jpeg" type="../image/png/jpeg" />
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <style>
        body { background-color: #0f172a; }
        .accent-rose { color: #e11d48; }
        .bg-accent-rose { background-color: #e11d48; }
        
        @media (max-width: 768px) {
            .mobile-table thead { display: none; }
            .mobile-table tr { 
                display: block; 
                margin-bottom: 1rem; 
                background: #1e293b; 
                border-radius: 1.5rem;
                padding: 1rem;
                border: 1px solid #334155;
            }
            .mobile-table td { 
                display: flex; 
                justify-content: space-between; 
                align-items: center;
                padding: 0.5rem 0;
                border: none;
            }
            .mobile-table td::before {
                content: attr(data-label);
                font-weight: 800;
                text-transform: uppercase;
                font-size: 10px;
                color: #94a3b8;
                letter-spacing: 0.05em;
            }
            .mobile-table td.book-cell { flex-direction: column; align-items: flex-start; }
            .mobile-table td.book-cell::before { margin-bottom: 0.5rem; }
        }
    </style>
</head>
<body class="font-sans text-slate-300 min-h-screen flex flex-col">
    
    <?php include "../include/header_member.php" ?>

    <div class="max-w-7xl mx-auto px-4 py-10 md:py-16 flex-grow w-full">
        <div class="mb-10 flex flex-col md:flex-row justify-between items-center gap-6">
            <div class="text-center md:text-left">
                <h1 class="text-2xl md:text-4xl font-black text-white uppercase tracking-tighter">
                    Riwayat <span class="text-accent-rose">Peminjaman</span>
                </h1>
                <p class="text-slate-500 text-xs md:text-sm font-bold uppercase tracking-widest mt-2">Log aktivitas literasi Anda</p>
            </div>
            <a href="pinjam_buku.php" class="w-full md:w-auto bg-accent-rose hover:bg-rose-600 text-white px-8 py-4 rounded-2xl text-[10px] md:text-xs font-black uppercase tracking-widest transition-all shadow-lg shadow-rose-900/20 text-center">
                <i class="fa-solid fa-plus mr-2"></i> Pinjam Buku Baru
            </a>
        </div>

        <div class="bg-slate-800/40 rounded-[2rem] border border-slate-700/50 overflow-hidden shadow-2xl backdrop-blur-sm">
            <table class="w-full text-left text-sm mobile-table">
                <thead class="bg-slate-900/50 text-slate-400 uppercase text-[10px] font-black tracking-[0.2em] border-b border-slate-700">
                    <tr>
                        <th class="px-8 py-6">Informasi Buku</th>
                        <th class="px-8 py-6 text-center">Pinjam</th>
                        <th class="px-8 py-6 text-center">Batas Tempo</th>
                        <th class="px-8 py-6 text-center">Kembali</th>
                        <th class="px-8 py-6 text-right">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-700/50">
                    <?php if(mysqli_num_rows($riwayat) > 0): ?>
                        <?php while($row = mysqli_fetch_assoc($riwayat)): 
                            $is_late = (strtotime(date('Y-m-d')) > strtotime($row['jatuh_tempo']) && $row['status'] == 'dipinjam');
                        ?>
                        <tr class="hover:bg-slate-700/20 transition-colors">
                            <td class="px-8 py-6 book-cell" data-label="Buku">
                                <div class="flex items-center gap-5">
                                    <div class="relative group">
                                        <img src="../assets/img/<?= $row['gambar'] ?: 'default_book.png' ?>" 
                                             class="w-12 h-16 md:w-14 md:h-20 object-cover rounded-xl shadow-lg border border-slate-600 group-hover:border-rose-500 transition-all" 
                                             alt="cover" onerror="this.src='https://via.placeholder.com/150x200?text=Buku'">
                                    </div>
                                    <div>
                                        <div class="text-white font-black text-sm md:text-base leading-tight mb-1 uppercase tracking-tighter"><?= $row['judul_buku'] ?></div>
                                        <div class="text-[10px] md:text-xs text-slate-500 font-bold italic opacity-80"><?= $row['pengarang'] ?></div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-8 py-6 text-center font-bold text-slate-400 text-xs" data-label="Pinjam">
                                <?= date('d M Y', strtotime($row['tanggal_pinjam'])) ?>
                            </td>
                            <td class="px-8 py-6 text-center font-bold text-xs <?= $is_late ? 'text-rose-500' : 'text-slate-400' ?>" data-label="Tempo">
                                <?= date('d M Y', strtotime($row['jatuh_tempo'])) ?>
                                <?php if($is_late): ?>
                                    <span class="block text-[8px] font-black uppercase tracking-tighter">Terlambat!</span>
                                <?php endif; ?>
                            </td>
                            <td class="px-8 py-6 text-center font-bold text-xs text-slate-400" data-label="Kembali">
                                <?= $row['tanggal_kembali'] ? date('d M Y', strtotime($row['tanggal_kembali'])) : '<span class="text-slate-600">-</span>' ?>
                            </td>
                            <td class="px-8 py-6 text-right" data-label="Status">
                                <?php if($row['status'] == 'dipinjam'): ?>
                                    <span class="inline-block bg-orange-500/10 text-orange-500 px-4 py-1.5 rounded-full text-[9px] md:text-[10px] font-black border border-orange-500/20 uppercase tracking-widest">
                                        <i class="fa-solid fa-clock mr-1"></i> Dipinjam
                                    </span>
                                <?php else: ?>
                                    <span class="inline-block bg-emerald-500/10 text-emerald-500 px-4 py-1.5 rounded-full text-[9px] md:text-[10px] font-black border border-emerald-500/20 uppercase tracking-widest">
                                        <i class="fa-solid fa-circle-check mr-1"></i> Kembali
                                    </span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" class="px-8 py-24 text-center">
                                <div class="w-20 h-20 bg-slate-800 rounded-full flex items-center justify-center mx-auto mb-6 border border-slate-700">
                                    <i class="fa-solid fa-folder-open text-slate-600 text-2xl"></i>
                                </div>
                                <p class="text-slate-500 font-black uppercase tracking-[0.3em] text-xs italic">
                                    Belum ada catatan peminjaman
                                </p>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <?php include "../include/footer_member.php" ?>

</body>
</html>