<?php
$koneksi = mysqli_connect("localhost", "root", "", "db_gabriel_perpustakaan");

if (isset($_POST['simpan'])) {
    $id = $_POST['id_anggota'];
    $nama = mysqli_real_escape_string($koneksi, $_POST['nama_siswa']);
    $telp = mysqli_real_escape_string($koneksi, $_POST['no_telp']);
    $alamat = mysqli_real_escape_string($koneksi, $_POST['alamat']);

    if ($id == "") {
        mysqli_query($koneksi, "INSERT INTO anggota (nama_siswa, no_telp, alamat) VALUES ('$nama', '$telp', '$alamat')");
    } else {
        mysqli_query($koneksi, "UPDATE anggota SET nama_siswa='$nama', no_telp='$telp', alamat='$alamat' WHERE id_anggota='$id'");
    }
    header("Location: anggota.php");
}

if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    mysqli_query($koneksi, "DELETE FROM anggota WHERE id_anggota='$id'");
    header("Location: anggota.php");
}

$edit = ['id_anggota' => '', 'nama_siswa' => '', 'no_telp' => '', 'alamat' => ''];
if (isset($_GET['edit'])) {
    $id = $_GET['edit'];
    $res = mysqli_query($koneksi, "SELECT * FROM anggota WHERE id_anggota='$id'");
    $edit = mysqli_fetch_assoc($res);
}

$tampil = mysqli_query($koneksi, "SELECT * FROM anggota ORDER BY id_anggota DESC");
?>

<!doctype html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Data Anggota | BookLoan</title>
    <link rel="icon" href="../img/logo.jpeg" type="../image/png/jpeg" />
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        body { background-color: #0f172a; }
        ::-webkit-scrollbar { width: 5px; }
        ::-webkit-scrollbar-thumb { background: #1e293b; border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: #e11d48; }
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="font-sans text-slate-300 overflow-x-hidden" x-data="{ selectedMember: null }">

    <div class="flex min-h-screen relative">
        <?php include "../include/sidebar_admin.php" ?>

        <main class="flex-1 w-full min-h-screen flex flex-col">
            
            <div class="lg:hidden bg-slate-900 border-b border-slate-800 px-4 py-3 flex justify-between items-center sticky top-0 z-40">
                <span class="text-white font-black uppercase tracking-tighter text-sm">BookLoan <span class="text-rose-500">Anggota</span></span>
                <button @click="$dispatch('toggle-sidebar')" class="p-2 bg-slate-800 rounded-lg text-slate-400">
                    <i class="fa-solid fa-bars-staggered text-lg"></i>
                </button>
            </div>

            <div class="p-4 md:p-10 lg:p-12">
                <div class="mb-8 flex flex-row justify-between items-center">
                    <div>
                        <h1 class="text-xl md:text-4xl font-black text-white uppercase tracking-tighter">Data <span class="text-rose-500">Anggota</span></h1>
                        <p class="text-slate-500 text-[8px] md:text-xs font-bold uppercase tracking-[0.2em] mt-1 italic">Manajemen Keanggotaan Siswa</p>
                    </div>
                </div>

                <div class="bg-slate-800/40 rounded-3xl border border-slate-700/50 p-5 md:p-8 mb-10 backdrop-blur-sm">
                    <form action="" method="POST" class="space-y-6">
                        <input type="hidden" name="id_anggota" value="<?= $edit['id_anggota'] ?>">
                        
                        <div class="grid grid-cols-2 md:grid-cols-3 gap-3 md:gap-6">
                            <div class="col-span-2 md:col-span-1">
                                <label class="text-[9px] font-black uppercase tracking-widest text-slate-500 ml-2 mb-2 block">Nama Lengkap</label>
                                <input type="text" name="nama_siswa" required value="<?= $edit['nama_siswa'] ?>" 
                                       class="w-full bg-slate-900/50 border border-slate-700 rounded-xl p-3 text-white text-sm focus:border-rose-500/50 outline-none">
                            </div>
                            <div class="col-span-1">
                                <label class="text-[9px] font-black uppercase tracking-widest text-slate-500 ml-2 mb-2 block">No. Telepon</label>
                                <input type="text" name="no_telp" required value="<?= $edit['no_telp'] ?>" 
                                       class="w-full bg-slate-900/50 border border-slate-700 rounded-xl p-3 text-white text-sm focus:border-rose-500/50 outline-none">
                            </div>
                            <div class="col-span-1 md:col-span-1">
                                <label class="text-[9px] font-black uppercase tracking-widest text-slate-500 ml-2 mb-2 block">Alamat</label>
                                <input type="text" name="alamat" required value="<?= $edit['alamat'] ?>" 
                                       class="w-full bg-slate-900/50 border border-slate-700 rounded-xl p-3 text-white text-sm focus:border-rose-500/50 outline-none">
                            </div>
                        </div>
                        
                        <div class="flex items-center gap-3">
                            <button type="submit" name="simpan" class="flex-1 bg-gradient-to-r from-rose-500 to-rose-600 hover:from-rose-600 hover:to-rose-700 text-white font-black uppercase tracking-[0.2em] text-[10px] py-4 rounded-xl shadow-lg shadow-rose-900/20 transition-all active:scale-[0.98]">
                                <i class="fa-solid fa-user-plus mr-2"></i> <?= $edit['id_anggota'] ? 'Perbarui Anggota' : 'Tambah Anggota Baru' ?>
                            </button>
                            <?php if($edit['id_anggota']): ?>
                                <a href="anggota.php" class="px-6 py-4 bg-slate-700 text-white rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-slate-600 transition-all">Batal</a>
                            <?php endif; ?>
                        </div>
                    </form>
                </div>

                <div class="hidden lg:block bg-slate-800/40 rounded-[2.5rem] border border-slate-700/50 overflow-hidden">
                    <table class="w-full text-left text-sm whitespace-nowrap">
                        <thead class="bg-slate-900/50 text-slate-400 uppercase text-[10px] font-black tracking-[0.2em]">
                            <tr>
                                <th class="px-8 py-5">Siswa</th>
                                <th class="px-8 py-5">No. Telepon</th>
                                <th class="px-8 py-5">Alamat</th>
                                <th class="px-8 py-5 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-700/30 text-slate-400">
                            <?php mysqli_data_seek($tampil, 0); while($row = mysqli_fetch_assoc($tampil)): ?>
                            <tr class="hover:bg-slate-700/20 transition-all group">
                                <td class="px-8 py-5">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 bg-rose-500/10 text-rose-500 rounded-xl flex items-center justify-center font-black uppercase text-xs">
                                            <?= substr($row['nama_siswa'], 0, 1) ?>
                                        </div>
                                        <div>
                                            <div class="text-white font-bold uppercase"><?= $row['nama_siswa'] ?></div>
                                            <div class="text-[10px] text-slate-500 font-medium italic">ID: #<?= $row['id_anggota'] ?></div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-8 py-5 font-mono text-xs"><?= $row['no_telp'] ?></td>
                                <td class="px-8 py-5 text-xs text-slate-500"><?= $row['alamat'] ?></td>
                                <td class="px-8 py-5 text-right space-x-2">
                                    <a href="?edit=<?= $row['id_anggota'] ?>" class="inline-flex items-center justify-center w-9 h-9 rounded-lg bg-blue-500/10 text-blue-500 hover:bg-blue-500 hover:text-white transition-all">
                                        <i class="fa-solid fa-pen text-xs"></i>
                                    </a>
                                    <a href="?hapus=<?= $row['id_anggota'] ?>" onclick="return confirm('Hapus anggota ini?')" class="inline-flex items-center justify-center w-9 h-9 rounded-lg bg-rose-500/10 text-rose-500 hover:bg-rose-500 hover:text-white transition-all">
                                        <i class="fa-solid fa-trash text-xs"></i>
                                    </a>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>

                <div class="lg:hidden grid grid-cols-3 gap-3">
                    <?php mysqli_data_seek($tampil, 0); while($row = mysqli_fetch_assoc($tampil)): ?>
                    <div @click="selectedMember = <?= htmlspecialchars(json_encode($row)) ?>" 
                         class="bg-slate-800/60 rounded-2xl border border-slate-700/50 p-3 relative flex flex-col items-center justify-center text-center active:scale-95 transition-all">
                        <div class="w-10 h-10 bg-gradient-to-br from-rose-500 to-rose-700 text-white rounded-full flex items-center justify-center shadow-lg shadow-rose-900/20 mb-2">
                            <span class="text-sm font-black uppercase"><?= substr($row['nama_siswa'], 0, 1) ?></span>
                        </div>
                        <div class="text-[9px] font-black text-white uppercase truncate w-full"><?= $row['nama_siswa'] ?></div>
                        <div class="text-[7px] text-slate-500 uppercase mt-1">ID: #<?= $row['id_anggota'] ?></div>
                    </div>
                    <?php endwhile; ?>
                </div>
            </div>

            <div x-show="selectedMember" 
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 scale-90"
                 x-transition:enter-end="opacity-100 scale-100"
                 x-transition:leave="transition ease-in duration-200"
                 class="fixed inset-0 z-[100] flex items-center justify-center p-6 bg-slate-950/80 backdrop-blur-md"
                 x-cloak>
                
                <div class="bg-slate-900 border border-slate-700 w-full max-w-xs rounded-[2.5rem] overflow-hidden shadow-2xl relative" @click.away="selectedMember = null">
                    <button @click="selectedMember = null" class="absolute top-4 right-4 text-slate-500 hover:text-white transition-colors">
                        <i class="fa-solid fa-circle-xmark text-2xl"></i>
                    </button>
                    
                    <div class="p-8 text-center">
                        <template x-if="selectedMember">
                            <div>
                                <div class="w-20 h-20 bg-rose-500 mx-auto rounded-3xl flex items-center justify-center text-white text-3xl font-black shadow-2xl shadow-rose-500/20 mb-6 border-4 border-slate-800">
                                    <span x-text="selectedMember.nama_siswa.substring(0,1)"></span>
                                </div>
                                <h3 class="text-xl font-black text-white uppercase tracking-tighter" x-text="selectedMember.nama_siswa"></h3>
                                <p class="text-[10px] text-slate-500 font-bold uppercase tracking-[0.2em] mt-1" x-text="'Member ID: #' + selectedMember.id_anggota"></p>
                                
                                <div class="mt-8 space-y-3">
                                    <div class="bg-slate-800/50 p-4 rounded-2xl border border-slate-700/50 text-left">
                                        <p class="text-[8px] font-black uppercase text-slate-500 mb-1 tracking-widest">Kontak</p>
                                        <p class="text-xs font-mono text-white" x-text="selectedMember.no_telp"></p>
                                    </div>
                                    <div class="bg-slate-800/50 p-4 rounded-2xl border border-slate-700/50 text-left">
                                        <p class="text-[8px] font-black uppercase text-slate-500 mb-1 tracking-widest">Alamat</p>
                                        <p class="text-xs text-white leading-relaxed" x-text="selectedMember.alamat"></p>
                                    </div>
                                </div>

                                <div class="grid grid-cols-2 gap-3 mt-8">
                                    <a :href="'?edit=' + selectedMember.id_anggota" class="bg-blue-600 text-white py-3 rounded-xl font-black uppercase text-[10px] tracking-widest shadow-lg shadow-blue-900/20">Edit</a>
                                    <a :href="'?hapus=' + selectedMember.id_anggota" onclick="return confirm('Hapus data ini?')" class="bg-rose-600 text-white py-3 rounded-xl font-black uppercase text-[10px] tracking-widest shadow-lg shadow-rose-900/20">Hapus</a>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>
            </div>
            <?php include "../include/footer_admin.php" ?>
        </main>
    </div>

</body>
</html>