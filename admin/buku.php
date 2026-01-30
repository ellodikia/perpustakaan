<?php
$koneksi = mysqli_connect("localhost", "root", "", "db_gabriel_perpustakaan");

if (isset($_POST['simpan'])) {
    $id = $_POST['id_buku'];
    $judul = mysqli_real_escape_string($koneksi, $_POST['judul_buku']);
    $pengarang = mysqli_real_escape_string($koneksi, $_POST['pengarang']);
    $stok = $_POST['stok'];
    
    $gambar = $_POST['gambar_lama']; 
    if ($_FILES['gambar']['name'] != "") {
        $nama_file = time() . "_" . $_FILES['gambar']['name'];
        $tmp_file = $_FILES['gambar']['tmp_name'];
        move_uploaded_file($tmp_file, "../assets/img/" . $nama_file);
        $gambar = $nama_file;
    }

    if ($id == "") {
        mysqli_query($koneksi, "INSERT INTO buku (judul_buku, pengarang, gambar, stok, stok_tersedia) VALUES ('$judul', '$pengarang', '$gambar', '$stok', '$stok')");
    } else {
        mysqli_query($koneksi, "UPDATE buku SET judul_buku='$judul', pengarang='$pengarang', gambar='$gambar', stok='$stok' WHERE id_buku='$id'");
    }
    header("Location: buku.php");
}

if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    mysqli_query($koneksi, "DELETE FROM buku WHERE id_buku='$id'");
    header("Location: buku.php");
}

$edit_data = ['id_buku' => '', 'judul_buku' => '', 'pengarang' => '', 'stok' => '', 'gambar' => ''];
if (isset($_GET['edit'])) {
    $id = $_GET['edit'];
    $res = mysqli_query($koneksi, "SELECT * FROM buku WHERE id_buku='$id'");
    $edit_data = mysqli_fetch_assoc($res);
}

$daftar_buku = mysqli_query($koneksi, "SELECT * FROM buku ORDER BY id_buku DESC");
?>

<!doctype html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Kelola Buku | BookLoan</title>
    <link rel="icon" href="../img/logo.jpeg" type="../image/png/jpeg" />
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        body { background-color: #0f172a; }
        .custom-scrollbar::-webkit-scrollbar { height: 4px; width: 4px; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #1e293b; border-radius: 10px; }
        /* Animasi Scale untuk Card */
        .card-pop { transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1); }
    </style>
</head>
<body class="font-sans text-slate-300 overflow-x-hidden" x-data="{ selectedBook: null }">

    <div class="flex min-h-screen relative">
        <?php include "../include/sidebar_admin.php" ?>

        <main class="flex-1 w-full min-h-screen flex flex-col">
            
            <div class="lg:hidden bg-slate-900 border-b border-slate-800 px-4 py-3 flex justify-between items-center sticky top-0 z-40">
                <span class="text-white font-black uppercase tracking-tighter text-sm">BookLoan <span class="text-rose-500">Buku</span></span>
                <button @click="$dispatch('toggle-sidebar')" class="p-2 bg-slate-800 rounded-lg text-slate-400">
                    <i class="fa-solid fa-bars-staggered text-lg"></i>
                </button>
            </div>

            <div class="p-4 md:p-10 lg:p-12">
                <div class="mb-8 flex flex-row justify-between items-center">
                    <h1 class="text-xl md:text-4xl font-black text-white uppercase tracking-tighter">Kelola <span class="text-rose-500">Buku</span></h1>
                </div>

                <div class="bg-slate-800/40 rounded-3xl border border-slate-700/50 p-5 md:p-8 mb-10">
                    <form action="" method="POST" enctype="multipart/form-data" class="space-y-4">
                        <input type="hidden" name="id_buku" value="<?= $edit_data['id_buku'] ?>">
                        <input type="hidden" name="gambar_lama" value="<?= $edit_data['gambar'] ?>">
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-3 md:gap-6">
                            <input type="text" name="judul_buku" placeholder="Judul" required value="<?= $edit_data['judul_buku'] ?>" class="col-span-2 md:col-span-1 bg-slate-900/50 border border-slate-700 rounded-xl p-3 text-white text-xs">
                            <input type="text" name="pengarang" placeholder="Pengarang" required value="<?= $edit_data['pengarang'] ?>" class="col-span-2 md:col-span-1 bg-slate-900/50 border border-slate-700 rounded-xl p-3 text-white text-xs">
                            <input type="number" name="stok" placeholder="Stok" required value="<?= $edit_data['stok'] ?>" class="bg-slate-900/50 border border-slate-700 rounded-xl p-3 text-white text-xs">
                            <input type="file" name="gambar" class="text-[10px] text-slate-500">
                        </div>
                        <button type="submit" name="simpan" class="w-full bg-rose-500 text-white font-black uppercase tracking-widest text-[10px] py-3 rounded-xl hover:bg-rose-600 transition-all">
                            <?= $edit_data['id_buku'] ? 'Update' : 'Simpan' ?>
                        </button>
                    </form>
                </div>

                <div class="hidden lg:block bg-slate-800/40 rounded-[2.5rem] border border-slate-700/50 overflow-hidden backdrop-blur-sm">
                    <table class="w-full text-left text-sm whitespace-nowrap">
                        <thead class="bg-slate-900/50 text-slate-400 uppercase text-[10px] font-black tracking-[0.2em]">
                            <tr>
                                <th class="px-8 py-5">Cover</th>
                                <th class="px-8 py-5">Informasi Buku</th>
                                <th class="px-8 py-5 text-center">Stok (T/S)</th>
                                <th class="px-8 py-5 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-700/30 text-slate-400">
                            <?php 
                            mysqli_data_seek($daftar_buku, 0); // Reset pointer
                            while($row = mysqli_fetch_assoc($daftar_buku)): 
                            ?>
                            <tr class="hover:bg-slate-700/20 transition-all group">
                                <td class="px-8 py-4"><img src="../assets/img/<?= $row['gambar'] ?: 'default_book.png' ?>" class="w-10 h-14 object-cover rounded shadow-lg"></td>
                                <td class="px-8 py-4">
                                    <div class="text-white font-bold uppercase text-sm"><?= $row['judul_buku'] ?></div>
                                    <div class="text-[10px] text-slate-500 uppercase"><?= $row['pengarang'] ?></div>
                                </td>
                                <td class="px-8 py-4 text-center font-mono text-xs"><?= $row['stok'] ?> / <span class="text-rose-500 font-bold"><?= $row['stok_tersedia'] ?></span></td>
                                <td class="px-8 py-4 text-right space-x-2">
                                    <a href="?edit=<?= $row['id_buku'] ?>" class="p-2 bg-blue-500/10 text-blue-500 rounded-lg hover:bg-blue-500 hover:text-white transition-all"><i class="fa-solid fa-pen"></i></a>
                                    <a href="?hapus=<?= $row['id_buku'] ?>" class="p-2 bg-rose-500/10 text-rose-500 rounded-lg hover:bg-rose-500 hover:text-white transition-all"><i class="fa-solid fa-trash"></i></a>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>

                <div class="lg:hidden grid grid-cols-3 gap-3">
                    <?php 
                    mysqli_data_seek($daftar_buku, 0); 
                    while($row = mysqli_fetch_assoc($daftar_buku)): 
                    ?>
                    <div @click="selectedBook = <?= htmlspecialchars(json_encode($row)) ?>" 
                         class="bg-slate-800/60 rounded-xl border border-slate-700/50 p-2 relative group active:scale-95 transition-all overflow-hidden">
                        <img src="../assets/img/<?= $row['gambar'] ?: 'default_book.png' ?>" 
                             class="w-full aspect-[3/4] object-cover rounded-lg shadow-md mb-2">
                        <div class="text-[9px] font-black text-white uppercase truncate text-center"><?= $row['judul_buku'] ?></div>
                        <div class="absolute top-1 right-1 bg-rose-500 text-[8px] font-black text-white px-1.5 rounded-full">
                            <?= $row['stok_tersedia'] ?>
                        </div>
                    </div>
                    <?php endwhile; ?>
                </div>

            </div>

            <div x-show="selectedBook" 
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 scale-90"
                 x-transition:enter-end="opacity-100 scale-100"
                 x-transition:leave="transition ease-in duration-200"
                 class="fixed inset-0 z-[100] flex items-center justify-center p-6 bg-slate-950/80 backdrop-blur-md"
                 @click.away="selectedBook = null" x-cloak>
                
                <div class="bg-slate-900 border border-slate-700 w-full max-w-sm rounded-[2rem] overflow-hidden shadow-2xl relative">
                    <button @click="selectedBook = null" class="absolute top-4 right-4 text-slate-500 hover:text-white">
                        <i class="fa-solid fa-circle-xmark text-2xl"></i>
                    </button>
                    
                    <div class="p-8 text-center">
                        <template x-if="selectedBook">
                            <div>
                                <img :src="'../assets/img/' + (selectedBook.gambar || 'default_book.png')" 
                                     class="w-40 mx-auto rounded-2xl shadow-2xl shadow-rose-500/20 mb-6 border-4 border-slate-800">
                                <h3 class="text-xl font-black text-white uppercase tracking-tighter leading-tight" x-text="selectedBook.judul_buku"></h3>
                                <p class="text-rose-500 font-bold text-xs uppercase tracking-widest mt-2" x-text="'Karya: ' + selectedBook.pengarang"></p>
                                
                                <div class="grid grid-cols-2 gap-4 mt-8">
                                    <div class="bg-slate-800 p-4 rounded-2xl border border-slate-700">
                                        <p class="text-[8px] font-black uppercase text-slate-500">Total Stok</p>
                                        <p class="text-xl font-black text-white" x-text="selectedBook.stok"></p>
                                    </div>
                                    <div class="bg-slate-800 p-4 rounded-2xl border border-slate-700">
                                        <p class="text-[8px] font-black uppercase text-slate-500">Tersedia</p>
                                        <p class="text-xl font-black text-emerald-500" x-text="selectedBook.stok_tersedia"></p>
                                    </div>
                                </div>

                                <div class="flex gap-3 mt-6">
                                    <a :href="'?edit=' + selectedBook.id_buku" class="flex-1 bg-blue-600 text-white py-3 rounded-xl font-black uppercase text-[10px] tracking-widest">Edit</a>
                                    <a :href="'?hapus=' + selectedBook.id_buku" class="flex-1 bg-rose-600 text-white py-3 rounded-xl font-black uppercase text-[10px] tracking-widest">Hapus</a>
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