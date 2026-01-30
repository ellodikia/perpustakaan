<?php
include 'assets/koneksi.php';
$success = "";
$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama_siswa = trim($_POST['nama_siswa'] ?? '');
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($nama_siswa) || empty($username) || empty($password)) {
        $error = "Semua kolom wajib diisi!";
    } else {
        $cek_anggota = $koneksi->prepare("SELECT id_anggota FROM anggota WHERE nama_siswa = ?");
        $cek_anggota->bind_param("s", $nama_siswa);
        $cek_anggota->execute();
        $res_anggota = $cek_anggota->get_result();

        if ($res_anggota->num_rows === 0) {
            $error = "Nama Anda belum terdaftar di sistem. Silakan hubungi Admin.";
        } else {
            $data_anggota = $res_anggota->fetch_assoc();
            $id_anggota = $data_anggota['id_anggota'];

            $cek_akun = $koneksi->prepare("SELECT id FROM login WHERE id_anggota = ?");
            $cek_akun->bind_param("i", $id_anggota);
            $cek_akun->execute();
            if ($cek_akun->get_result()->num_rows > 0) {
                $error = "Nama ini sudah memiliki akun. Silakan langsung login.";
            } else {
                $cek_user = $koneksi->prepare("SELECT id FROM login WHERE username = ?");
                $cek_user->bind_param("s", $username);
                $cek_user->execute();
                if ($cek_user->get_result()->num_rows > 0) {
                    $error = "Username sudah digunakan, cari yang lain!";
                } else {
                    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                    $level = 'siswa'; 
                    
                    $stmt = $koneksi->prepare("INSERT INTO login (username, password, level, id_anggota) VALUES (?, ?, ?, ?)");
                    $stmt->bind_param("sssi", $username, $hashed_password, $level, $id_anggota);
                    
                    if ($stmt->execute()) {
                        $success = "Registrasi berhasil untuk <strong>$nama_siswa</strong>! Silakan login.";
                    } else {
                        $error = "Gagal menyimpan data.";
                    }
                }
            }
        }
    }
}
?>

<!doctype html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="icon" href="img/logo.jpeg" type="image/png/jpeg" />
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <title>Aktivasi Akun |BookLoan</title>
    <style>
        body { background-color: #fff1f2; }
        .text-main { color: #881337; }
        .accent-rose { color: #e11d48; }
        .bg-accent-rose { background-color: #e11d48; }
        .bg-dark-slate { background-color: #0f172a; }
    </style>
</head>
<body class="font-sans text-slate-800 min-h-screen flex flex-col">

    <div class="fixed top-6 left-6 z-50">
        <a href="index.php" class="flex items-center gap-2 bg-white/80 backdrop-blur-md px-4 py-2 rounded-xl border border-rose-100 text-main font-black text-xs uppercase tracking-widest shadow-sm hover:bg-accent-rose hover:text-white transition-all group">
            <i class="fa-solid fa-arrow-left group-hover:-translate-x-1 transition-transform"></i>
            Kembali
        </a>
    </div>

    <div class="flex-grow flex items-center justify-center p-4 md:p-6 relative">
        <div class="absolute inset-0 overflow-hidden z-0">
            <div class="absolute top-0 right-0 w-64 h-64 bg-rose-200/50 rounded-full blur-3xl translate-x-1/2 -translate-y-1/2"></div>
            <div class="absolute bottom-0 left-0 w-80 h-80 bg-rose-300/30 rounded-full blur-3xl -translate-x-1/3 translate-y-1/3"></div>
        </div>

        <div class="bg-white p-6 md:p-10 rounded-[2rem] shadow-2xl shadow-rose-900/10 w-full max-w-md relative z-10 border border-rose-100">
            <div class="text-center mb-8">
                <div class="w-14 h-14 bg-rose-50 text-accent-rose rounded-2xl flex items-center justify-center mx-auto mb-4 border border-rose-100">
                    <i class="fa-solid fa-user-plus text-xl"></i>
                </div>
                <h2 class="text-2xl font-black tracking-tighter text-slate-900 uppercase">Aktivasi <span class="text-accent-rose">Akun</span></h2>
                <p class="text-slate-400 text-[10px] font-bold uppercase tracking-widest mt-2">Gunakan nama terdaftar di sistem</p>
            </div>

            <?php if ($success): ?>
                <div class="mb-6 p-4 bg-green-50 border-l-4 border-green-500 text-green-800 text-xs font-bold rounded-r-lg flex items-center gap-3">
                    <i class="fa-solid fa-circle-check text-lg"></i>
                    <span><?= $success ?></span>
                </div>
            <?php endif; ?>

            <?php if ($error): ?>
                <div class="mb-6 p-4 bg-rose-50 border-l-4 border-accent-rose text-rose-800 text-xs font-bold rounded-r-lg flex items-center gap-3">
                    <i class="fa-solid fa-circle-exclamation text-lg"></i>
                    <span><?= $error ?></span>
                </div>
            <?php endif; ?>

            <form action="" method="post" class="space-y-5">
                <div>
                    <label class="block text-[10px] font-black uppercase tracking-widest text-slate-500 mb-2 ml-1">Nama Lengkap Siswa</label>
                    <input type="text" name="nama_siswa" placeholder="Contoh: Budi Pratama" required
                        class="w-full px-5 py-3.5 rounded-2xl border border-slate-100 bg-slate-50 focus:bg-white focus:ring-2 focus:ring-rose-500/20 focus:border-accent-rose outline-none transition-all text-sm font-bold">
                </div>

                <div class="h-px bg-gradient-to-r from-transparent via-slate-200 to-transparent my-2"></div>

                <div>
                    <label class="block text-[10px] font-black uppercase tracking-widest text-slate-500 mb-2 ml-1">Username Baru</label>
                    <input type="text" name="username" required
                        class="w-full px-5 py-3.5 rounded-2xl border border-slate-100 bg-slate-50 focus:bg-white focus:ring-2 focus:ring-rose-500/20 focus:border-accent-rose outline-none transition-all text-sm font-bold">
                </div>

                <div>
                    <label class="block text-[10px] font-black uppercase tracking-widest text-slate-500 mb-2 ml-1">Password</label>
                    <input type="password" name="password" required
                        class="w-full px-5 py-3.5 rounded-2xl border border-slate-100 bg-slate-50 focus:bg-white focus:ring-2 focus:ring-rose-500/20 focus:border-accent-rose outline-none transition-all text-sm font-bold"
                        placeholder="••••••••">
                </div>

                <button type="submit" 
                    class="w-full bg-dark-slate hover:bg-rose-900 text-white font-black py-4 rounded-2xl shadow-xl shadow-slate-900/20 transition-all active:scale-95 text-xs uppercase tracking-widest">
                    Aktivasi Akun Saya
                </button>
            </form>

            <div class="mt-8 text-center">
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-3">Sudah punya akun?</p>
                <a href="login.php" class="text-accent-rose font-black text-xs uppercase border-b-2 border-rose-100 hover:border-accent-rose transition-all pb-1">
                    Masuk di sini
                </a>
            </div>
        </div>
    </div>

    <?php include 'include/footer_member.php'; ?>

</body>
</html>