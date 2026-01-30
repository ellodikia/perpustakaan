<?php
session_start();
include 'assets/koneksi.php';

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    $stmt = $koneksi->prepare("SELECT * FROM login WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $data = $result->fetch_assoc();

        if (password_verify($password, $data['password'])) {
            $_SESSION['id_login']   = $data['id'];
            $_SESSION['username']   = $data['username'];
            $_SESSION['level']      = $data['level'];
            $_SESSION['id_anggota'] = $data['id_anggota']; 

            if ($data['level'] == 'admin') {
                header("Location: admin/index.php");
            } else {
                header("Location: anggota/index.php");
            }
            exit;
        } else {
            $error = "Password salah!";
        }
    } else {
        $error = "Username tidak ditemukan!";
    }
}
?>

<!doctype html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="icon" href="img/logo.jpeg" type="image/png/jpeg" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <title>Login | BookLoan</title>
    <style>
        body { background-color: #fff1f2; }
        .text-main { color: #881337; }
        .bg-main { background-color: #881337; }
        .accent-rose { color: #e11d48; }
        .bg-accent-rose { background-color: #e11d48; }
        .bg-dark-slate { background-color: #0f172a; }
    </style>
</head>
<body class="font-sans text-slate-800 min-h-screen flex flex-col">

    <div class="fixed top-6 left-6 z-50">
        <a href="index.php" class="flex items-center gap-2 bg-white/80 backdrop-blur-md px-4 py-2 rounded-xl border border-rose-100 text-main font-black text-xs uppercase tracking-widest shadow-sm hover:bg-accent-rose hover:text-gray transition-all group">
            <i class="fa-solid fa-arrow-left group-hover:-translate-x-1 transition-transform"></i>
            Kembali
        </a>
    </div>

    <div class="flex-grow flex items-center justify-center p-4 md:p-6 relative">
        <div class="absolute inset-0 overflow-hidden z-0">
            <div class="absolute top-0 left-0 w-64 h-64 bg-rose-200/50 rounded-full blur-3xl -translate-x-1/2 -translate-y-1/2"></div>
            <div class="absolute bottom-0 right-0 w-80 h-80 bg-rose-300/30 rounded-full blur-3xl translate-x-1/3 translate-y-1/3"></div>
        </div>

        <div class="bg-white p-6 md:p-10 rounded-[2rem] shadow-2xl shadow-rose-900/10 w-full max-w-sm relative z-10 border border-rose-100">
            <div class="text-center mb-10">
                <div class="w-14 h-14 bg-accent-rose rounded-2xl flex items-center justify-center shadow-lg shadow-rose-500/30 mx-auto mb-4">
                    <i class="fa-solid fa-lock text-white text-xl"></i>
                </div>
                <h2 class="text-2xl md:text-3xl font-black tracking-tighter text-slate-900">
                    BookLoan</span>
                </h2>
                <p class="text-slate-400 text-[10px] md:text-xs font-bold uppercase tracking-[0.2em] mt-2">Portal Perpustakaan</p>
            </div>

            <?php if ($error): ?>
                <div class="mb-6 p-4 bg-rose-50 border-l-4 border-accent-rose text-rose-800 text-xs font-bold rounded-r-lg flex items-center gap-3">
                    <i class="fa-solid fa-circle-exclamation"></i>
                    <?= $error ?>
                </div>
            <?php endif; ?>

            <form action="" method="post" class="space-y-6">
                <div>
                    <label class="block text-[10px] font-black uppercase tracking-widest text-slate-500 mb-2 ml-1">Username</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400">
                            <i class="fa-solid fa-user text-xs"></i>
                        </span>
                        <input type="text" name="username" required
                            class="w-full pl-11 pr-4 py-3.5 rounded-2xl border border-slate-100 bg-slate-50 focus:bg-white focus:ring-2 focus:ring-rose-500/20 focus:border-accent-rose outline-none transition-all text-sm font-bold"
                            placeholder="Masukkan username">
                    </div>
                </div>

                <div>
                    <label class="block text-[10px] font-black uppercase tracking-widest text-slate-500 mb-2 ml-1">Password</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400">
                            <i class="fa-solid fa-key text-xs"></i>
                        </span>
                        <input type="password" name="password" required
                            class="w-full pl-11 pr-4 py-3.5 rounded-2xl border border-slate-100 bg-slate-50 focus:bg-white focus:ring-2 focus:ring-rose-500/20 focus:border-accent-rose outline-none transition-all text-sm font-bold"
                            placeholder="••••••••">
                    </div>
                </div>

                <button type="submit" 
                    class="w-full bg-dark-slate hover:bg-rose-900 text-white font-black py-4 rounded-2xl shadow-xl shadow-slate-900/20 transition-all active:scale-95 text-xs uppercase tracking-widest">
                    Masuk Sekarang
                </button>
            </form>
            
            <div class="mt-10 text-center">
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-3">Belum punya akun?</p>
                <a href="register.php" class="inline-block text-accent-rose font-black text-xs uppercase border-b-2 border-rose-100 hover:border-accent-rose transition-all pb-1">
                    Aktivasi Akun Disini
                </a>
            </div>
        </div>
    </div>

    <?php include 'include/footer_member.php'; ?>

</body>
</html>