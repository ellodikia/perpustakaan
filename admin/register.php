<?php
session_start();
$koneksi = mysqli_connect("localhost", "root", "", "db_gabriel_perpustakaan");

$pesan = "";
$tipe_pesan = "";

if (isset($_POST['register'])) {
    $username = mysqli_real_escape_string($koneksi, $_POST['username']);
    $password = $_POST['password'];
    $konfirmasi_password = $_POST['konfirmasi_password'];

    $cek_user = mysqli_query($koneksi, "SELECT * FROM login WHERE username = '$username'");
    
    if (mysqli_num_rows($cek_user) > 0) {
        $pesan = "Username sudah terdaftar!";
        $tipe_pesan = "error";
    } elseif ($password !== $konfirmasi_password) {
        $pesan = "Konfirmasi password tidak cocok!";
        $tipe_pesan = "error";
    } else {
        $password_aman = password_hash($password, PASSWORD_BCRYPT);
        
        $query = "INSERT INTO login (username, password, level) 
                  VALUES ('$username', '$password_aman', 'admin')";
        
        if (mysqli_query($koneksi, $query)) {
            $pesan = "Registrasi berhasil! Silakan login.";
            $tipe_pesan = "sukses";
        } else {
            $pesan = "Terjadi kesalahan sistem.";
            $tipe_pesan = "error";
        }
    }
}
?>

<!doctype html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Register Admin | BookLoan</title>
    <link rel="icon" href="../img/logo.jpeg" type="../image/png/jpeg" />
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
</head>
<body class="bg-[#0f172a] font-sans text-slate-300 min-h-screen flex items-center justify-center p-4">

    <div class="w-full max-w-md">
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-16 h-16 bg-rose-500 rounded-2xl shadow-lg shadow-rose-900/40 mb-4 rotate-3">
                <i class="fa-solid fa-user-plus text-white text-3xl"></i>
            </div>
            <h1 class="text-3xl font-black text-white uppercase tracking-tighter">Join <span class="text-rose-500">BookLoan</span></h1>
            <p class="text-slate-500 text-xs font-bold uppercase tracking-widest mt-2">Daftar Akun Admin Baru</p>
        </div>

        <div class="bg-slate-800/40 border border-slate-700/50 p-8 rounded-[2.5rem] backdrop-blur-xl shadow-2xl">
            
            <?php if ($pesan): ?>
                <div class="<?= $tipe_pesan == 'sukses' ? 'bg-emerald-500/10 text-emerald-500 border-emerald-500/20' : 'bg-rose-500/10 text-rose-500 border-rose-500/20' ?> border px-4 py-3 rounded-xl text-xs font-bold uppercase tracking-widest mb-6 text-center">
                    <?= $pesan ?>
                </div>
            <?php endif; ?>

            <form action="" method="POST" class="space-y-5">

                <div>
                    <label class="block text-[10px] font-black text-slate-500 uppercase tracking-[0.2em] ml-2 mb-2 italic">Username</label>
                    <div class="relative">
                        <i class="fa-solid fa-user absolute left-4 top-1/2 -translate-y-1/2 text-slate-500 text-xs"></i>
                        <input type="text" name="username" required
                               class="w-full bg-slate-900/50 border border-slate-700 rounded-xl py-3.5 pl-11 pr-4 text-sm text-white focus:outline-none focus:border-rose-500 transition-all placeholder:text-slate-700"
                               placeholder="Pilih username unik">
                    </div>
                </div>

                <div>
                    <label class="block text-[10px] font-black text-slate-500 uppercase tracking-[0.2em] ml-2 mb-2 italic">Password</label>
                    <div class="relative">
                        <i class="fa-solid fa-lock absolute left-4 top-1/2 -translate-y-1/2 text-slate-500 text-xs"></i>
                        <input type="password" name="password" required
                               class="w-full bg-slate-900/50 border border-slate-700 rounded-xl py-3.5 pl-11 pr-4 text-sm text-white focus:outline-none focus:border-rose-500 transition-all placeholder:text-slate-700"
                               placeholder="••••••••">
                    </div>
                </div>

                <div>
                    <label class="block text-[10px] font-black text-slate-500 uppercase tracking-[0.2em] ml-2 mb-2 italic">Ulangi Password</label>
                    <div class="relative">
                        <i class="fa-solid fa-shield-check absolute left-4 top-1/2 -translate-y-1/2 text-slate-500 text-xs"></i>
                        <input type="password" name="konfirmasi_password" required
                               class="w-full bg-slate-900/50 border border-slate-700 rounded-xl py-3.5 pl-11 pr-4 text-sm text-white focus:outline-none focus:border-rose-500 transition-all placeholder:text-slate-700"
                               placeholder="Ketik ulang password">
                    </div>
                </div>

                <button type="submit" name="register"
                        class="w-full bg-rose-500 hover:bg-rose-600 text-white font-black py-4 rounded-xl shadow-lg shadow-rose-900/30 transition-all uppercase text-[10px] tracking-[0.3em] active:scale-[0.98]">
                    Buat Akun Sekarang
                </button>
            </form>

            <div class="mt-8 pt-6 border-t border-slate-700/50 text-center">
                <p class="text-[10px] font-bold text-slate-500 uppercase tracking-widest leading-loose">
                    Sudah punya akses admin?<br>
                    <a href="../login.php" class="text-rose-500 hover:text-rose-400 transition-colors">Masuk ke Dashboard</a>
                </p>
            </div>
        </div>

    </div>

</body>
</html>