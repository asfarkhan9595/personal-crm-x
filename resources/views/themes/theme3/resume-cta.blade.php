<section id="resume" class="py-16 sm:py-20 relative">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="relative rounded-3xl bg-gradient-to-r from-blue-600/10 via-cyan-500/10 to-indigo-600/10 border border-white/80 dark:border-white/10 backdrop-blur-2xl p-8 sm:p-12 shadow-2xl overflow-hidden flex flex-col md:flex-row md:items-center justify-between gap-8">
            
            {{-- Background ambient glow --}}
            <div class="absolute -right-10 -bottom-10 w-64 h-64 bg-blue-500/20 rounded-full blur-3xl pointer-events-none"></div>

            <div class="space-y-3 max-w-2xl relative z-10">
                <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-blue-500/10 dark:bg-cyan-500/10 border border-blue-500/20 text-blue-600 dark:text-cyan-400 text-xs font-bold uppercase tracking-wider">
                    📄 Official Resume
                </span>
                <h2 class="text-2xl sm:text-4xl font-extrabold text-slate-900 dark:text-white tracking-tight">
                    Looking for detailed experience &amp; credentials?
                </h2>
                <p class="text-xs sm:text-sm text-slate-600 dark:text-slate-300 leading-relaxed">
                    Download my comprehensive PDF resume detailing technical proficiencies, enterprise experience, system architecture projects, and education.
                </p>
            </div>

            <div class="relative z-10 flex-shrink-0">
                @if(!empty($activeResume) || !empty($resume))
                <a href="{{ route('resume.download') }}" target="_blank" class="inline-flex items-center gap-2.5 px-8 py-4 rounded-full bg-blue-600 hover:bg-blue-700 text-white font-extrabold text-xs shadow-xl shadow-blue-500/25 transition-all hover:scale-105 active:scale-95">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                    <span>Download Resume PDF</span>
                </a>
                @else
                <a href="#contact" class="inline-flex items-center gap-2.5 px-8 py-4 rounded-full bg-blue-600 hover:bg-blue-700 text-white font-extrabold text-xs shadow-xl shadow-blue-500/25 transition-all hover:scale-105 active:scale-95">
                    <span>Contact For CV</span>
                </a>
                @endif
            </div>

        </div>
    </div>
</section>
