{{-- Unique Transparent Glass Page Loader --}}
<div id="page-loader" class="fixed inset-0 z-[100] flex flex-col items-center justify-center bg-slate-950/70 dark:bg-black/80 backdrop-blur-2xl transition-all duration-500 pointer-events-auto">
    <div class="relative flex items-center justify-center">
        <!-- Outer Glowing Cyber Ring -->
        <div class="w-16 h-16 rounded-full border-2 border-indigo-500/20 dark:border-cyan-500/20 border-t-indigo-500 dark:border-t-cyan-400 animate-spin"></div>
        <!-- Inner Reverse Spinner -->
        <div class="absolute w-10 h-10 rounded-full border-2 border-emerald-500/20 border-b-emerald-400 animate-spin" style="animation-direction: reverse; animation-duration: 0.8s;"></div>
        <!-- Center Pulsing Core Dot -->
        <div class="w-3 h-3 rounded-full bg-gradient-to-tr from-indigo-500 to-cyan-400 animate-pulse shadow-lg shadow-cyan-500/50"></div>
    </div>
    <span class="mt-4 font-mono text-[11px] font-bold uppercase tracking-widest text-slate-200 dark:text-cyan-300 animate-pulse">// INITIALIZING_SYSTEM</span>
</div>

