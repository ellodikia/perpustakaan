<div x-data="{ isOpen: false }" 
     @toggle-sidebar.window="isOpen = !isOpen" 
     class="relative">
    
    <div x-show="isOpen" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         @click="isOpen = false" 
         class="fixed inset-0 bg-slate-950/60 backdrop-blur-sm z-[60] lg:hidden">
    </div>

    <aside :class="isOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'"
           class="fixed lg:sticky top-0 left-0 flex w-72 flex-col border-r border-slate-800 bg-slate-900 h-screen z-[70] transition-transform duration-300 ease-in-out">
        
        <div class="flex h-24 items-center justify-between px-8 shrink-0">
            <div class="flex items-center">
                <div class="h-10 w-10 rounded-xl bg-gradient-to-br from-rose-500 to-rose-700 flex items-center justify-center text-white shadow-lg shadow-rose-900/30 rotate-3">
                    <i class="fa-solid fa-book-bookmark text-xl"></i>
                </div>
                <div class="ml-4">
                    <span class="block text-white font-black tracking-tighter text-xl leading-none">BookLoan</span>
                    <span class="text-[9px] font-bold text-slate-500 uppercase tracking-[0.3em]"><a href="register.php">Admin</a> </span>
                </div>
            </div>
            <button @click="isOpen = false" class="lg:hidden text-slate-500 hover:text-rose-500 transition-colors">
                <i class="fa-solid fa-xmark text-2xl"></i>
            </button>
        </div>

        <nav class="flex-1 space-y-2 px-4 py-6 overflow-y-auto custom-scrollbar">
            <a href="index.php" 
               class="flex items-center rounded-2xl px-4 py-3.5 transition-all duration-300 group 
               <?= (basename($_SERVER['PHP_SELF']) == 'index.php') ? 'bg-rose-500 text-white shadow-lg shadow-rose-900/20' : 'text-slate-400 hover:bg-slate-800/50 hover:text-white' ?>">
                <i class="fa-solid fa-house-chimney mr-4 text-sm <?= (basename($_SERVER['PHP_SELF']) == 'index.php') ? '' : 'group-hover:scale-110' ?> transition-transform"></i>
                <span class="text-xs font-black uppercase tracking-widest">Dashboard</span>
            </a>

            <div class="mt-8 mb-2 px-5">
                <h3 class="text-[10px] font-black uppercase tracking-[0.2em] text-slate-600">Master Data</h3>
            </div>
            
            <a href="buku.php" 
               class="flex items-center rounded-2xl px-4 py-3.5 transition-all duration-300 group 
               <?= (basename($_SERVER['PHP_SELF']) == 'buku.php') ? 'bg-rose-500 text-white shadow-lg shadow-rose-900/20' : 'text-slate-400 hover:bg-slate-800/50 hover:text-white' ?>">
                <i class="fa-solid fa-book mr-4 text-sm group-hover:scale-110 transition-transform"></i>
                <span class="text-xs font-black uppercase tracking-widest">Kelola Buku</span>
            </a>
            
            <a href="anggota.php" 
               class="flex items-center rounded-2xl px-4 py-3.5 transition-all duration-300 group 
               <?= (basename($_SERVER['PHP_SELF']) == 'anggota.php') ? 'bg-rose-500 text-white shadow-lg shadow-rose-900/20' : 'text-slate-400 hover:bg-slate-800/50 hover:text-white' ?>">
                <i class="fa-solid fa-users mr-4 text-sm group-hover:scale-110 transition-transform"></i>
                <span class="text-xs font-black uppercase tracking-widest">Data Anggota</span>
            </a>

            <div class="mt-8 mb-2 px-5">
                <h3 class="text-[10px] font-black uppercase tracking-[0.2em] text-slate-600">Layanan</h3>
            </div>

            <a href="transaksi.php" 
               class="flex items-center rounded-2xl px-4 py-3.5 transition-all duration-300 group 
               <?= (basename($_SERVER['PHP_SELF']) == 'transaksi.php') ? 'bg-rose-500 text-white shadow-lg shadow-rose-900/20' : 'text-slate-400 hover:bg-slate-800/50 hover:text-white' ?>">
                <i class="fa-solid fa-right-left mr-4 text-sm group-hover:scale-110 transition-transform"></i>
                <span class="text-xs font-black uppercase tracking-widest">Transaksi</span>
            </a>

            <a href="stok.php" 
               class="flex items-center rounded-2xl px-4 py-3.5 transition-all duration-300 group 
               <?= (basename($_SERVER['PHP_SELF']) == 'stok.php') ? 'bg-rose-500 text-white shadow-lg shadow-rose-900/20' : 'text-slate-400 hover:bg-slate-800/50 hover:text-white' ?>">
                <i class="fa-solid fa-chart-pie mr-4 text-sm group-hover:scale-110 transition-transform"></i>
                <span class="text-xs font-black uppercase tracking-widest">Laporan Stok</span>
            </a>
        </nav>

        <div class="p-6 border-t border-slate-800/50 bg-slate-900/50 backdrop-blur-sm shrink-0">
            <div class="flex items-center gap-4 p-3 rounded-2xl bg-slate-800/40 border border-slate-700/50">
                <div class="relative shrink-0">
                    <img class="h-10 w-10 rounded-xl object-cover ring-2 ring-rose-500/20" 
                         src="https://ui-avatars.com/api/?name=Admin+Perpus&background=e11d48&color=fff&bold=true" 
                         alt="User">
                    <span class="absolute -bottom-1 -right-1 w-3 h-3 bg-emerald-500 border-2 border-slate-900 rounded-full"></span>
                </div>
                <div class="overflow-hidden">
                    <p class="text-[11px] font-black text-white uppercase tracking-tight truncate">Administrator</p>
                    <a href="../logout.php" onclick="return confirm('Yakin ingin keluar?')" 
                       class="text-[9px] font-bold text-rose-500 hover:text-rose-400 uppercase tracking-widest flex items-center gap-1 transition-colors">
                        <i class="fa-solid fa-power-off text-[8px]"></i> Logout
                    </a>
                </div>
            </div>
        </div>
    </aside>
</div>