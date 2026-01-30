<nav class="bg-slate-900/80 backdrop-blur-md border-b border-slate-700/50 px-6 md:px-12 py-5 flex justify-between items-center sticky top-0 z-50">
    <div class="flex items-center gap-4">
        <div class="w-10 h-10 bg-gradient-to-br from-rose-500 to-rose-700 rounded-xl flex items-center justify-center shadow-lg shadow-rose-900/20">
            <i class="fa-solid fa-book-open text-white text-lg"></i>
        </div>
        <div>
            <h1 class="text-white font-black leading-none tracking-tighter text-lg">
                BookLoan</span>
            </h1>
            <p class="text-[9px] text-slate-500 uppercase font-black tracking-[0.2em] mt-1">Member Area</p>
        </div>
    </div>

    <div class="flex items-center gap-4 md:gap-8">
        <div class="hidden lg:flex items-center gap-8 mr-4">
            <a href="index.php" class="text-[10px] font-black uppercase tracking-widest <?= basename($_SERVER['PHP_SELF']) == 'index.php' ? 'text-rose-500' : 'text-slate-400 hover:text-rose-400' ?> transition-colors">
                Dashboard
            </a>
            <a href="pinjam_buku.php" class="text-[10px] font-black uppercase tracking-widest <?= basename($_SERVER['PHP_SELF']) == 'pinjam_buku.php' ? 'text-rose-500' : 'text-slate-400 hover:text-rose-400' ?> transition-colors">
                Katalog
            </a>
            <a href="riwayat_peminjaman.php" class="text-[10px] font-black uppercase tracking-widest <?= basename($_SERVER['PHP_SELF']) == 'riwayat_peminjaman.php' ? 'text-rose-500' : 'text-slate-400 hover:text-rose-400' ?> transition-colors">
                Riwayat
            </a>
        </div>

        <div class="hidden sm:block h-8 w-[1px] bg-slate-700/50"></div>

        <div class="flex items-center gap-3 md:gap-5">
            <div class="text-right hidden sm:block">
                <p class="text-[11px] font-black text-white uppercase tracking-tight"><?= ucfirst($_SESSION['username']) ?></p>
                <div class="flex items-center justify-end gap-1.5">
                    <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full animate-pulse"></span>
                    <p class="text-[9px] font-bold text-emerald-500 uppercase tracking-widest">Online</p>
                </div>
            </div>
            
            <a href="../logout.php" onclick="return confirm('Yakin ingin keluar?')" 
               class="bg-slate-800 hover:bg-rose-600 text-slate-400 hover:text-white border border-slate-700 hover:border-rose-500 p-2.5 md:px-4 md:py-2.5 rounded-xl transition-all duration-300 group">
                <i class="fa-solid fa-power-off text-xs md:mr-2"></i>
                <span class="hidden md:inline text-[10px] font-black uppercase tracking-widest">Keluar</span>
            </a>
        </div>
    </div>
</nav>