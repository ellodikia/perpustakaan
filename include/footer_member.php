<footer class="bg-slate-900 border-t border-rose-900/20 py-12 mt-20">
    <div class="max-w-7xl mx-auto px-6">
        <div class="flex flex-col md:flex-row justify-between items-center gap-10">
            
            <div class="text-center md:text-left">
                <div class="flex items-center justify-center md:justify-start gap-3 mb-3">
                    <div class="w-8 h-8 bg-accent-rose rounded-lg flex items-center justify-center shadow-lg shadow-rose-500/20">
                        <i class="fa-solid fa-book-open text-white text-[10px]"></i>
                    </div>
                    <span class="font-black text-lg tracking-tighter  text-white">
                        BookLoan</span>
                    </span>
                </div>
                <p class="text-slate-500 text-[10px] uppercase tracking-[0.2em] font-bold">
                    &copy; <?= date('Y'); ?> ELLODIKIA &bull; All Rights Reserved.
                </p>
            </div>

            <div class="text-center md:text-right">
                <p class="text-slate-500 text-[10px] uppercase tracking-[0.2em] mb-1 font-semibold">Developed By</p>
                <a href="http://ellodikia.ct.ws" target="_blank" class="group flex items-center justify-center md:justify-end gap-2 text-rose-50 hover:text-accent-rose transition-all duration-300">
                    <span class="font-black text-sm tracking-tighter uppercase">ELLODIKIA</span>
                    <i class="fa-solid fa-arrow-up-right-from-square text-[10px] transition-transform group-hover:-translate-y-1 group-hover:translate-x-1"></i>
                </a>
            </div>

        </div>

        <div class="mt-10 h-1 w-full bg-gradient-to-r from-transparent via-rose-900/30 to-transparent rounded-full"></div>
    </div>
</footer>