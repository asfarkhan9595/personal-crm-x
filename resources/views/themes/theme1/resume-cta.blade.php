<section id="resume-cta" class="py-12 sm:py-16 relative">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="p-8 sm:p-12 rounded-3xl nexus-glass-card border border-white/60 dark:border-white/10 shadow-2xl relative overflow-hidden flex flex-col md:flex-row items-center justify-between gap-8 backdrop-blur-2xl">
            
            <!-- Background Decorative Glow -->
            <div class="absolute -right-12 -bottom-12 w-64 h-64 bg-blue-500/10 dark:bg-cyan-500/10 rounded-full blur-3xl pointer-events-none"></div>
            <div class="absolute -left-12 -top-12 w-64 h-64 bg-purple-500/10 dark:bg-indigo-500/10 rounded-full blur-3xl pointer-events-none"></div>

            <div class="relative z-10 text-center md:text-left">
                <div class="inline-flex items-center gap-1.5 px-3 py-0.5 rounded-full bg-blue-500/10 dark:bg-cyan-500/10 border border-blue-500/20 dark:border-cyan-500/20 text-blue-600 dark:text-cyan-400 text-[10px] font-semibold uppercase tracking-wider backdrop-blur-md mb-2">
                    <span class="w-1.5 h-1.5 rounded-full bg-blue-500 dark:bg-cyan-400 animate-ping"></span>
                    <span>Curriculum Vitae</span>
                </div>
                <h2 class="text-2xl sm:text-4xl font-extrabold text-slate-900 dark:text-white tracking-tight">
                    Want to know more about my work?
                </h2>
                <p class="mt-2 text-sm text-slate-600 dark:text-slate-300 max-w-xl leading-relaxed">
                    @if(!empty($activeResume) || !empty($resume))
                        Download my resume for a detailed look at my skills, projects, and experience.
                    @else
                        Get in touch to request a copy of my detailed resume and experience.
                    @endif
                </p>
            </div>

            <div class="relative z-10 shrink-0">
                @if(!empty($activeResume) || !empty($resume))
                <a href="{{ route('resume.download') }}" target="_blank" class="inline-flex items-center gap-2.5 rounded-2xl bg-gradient-to-r from-blue-600 via-indigo-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 text-white font-extrabold px-6 py-4 text-sm shadow-xl shadow-blue-500/20 border border-white/20 transition-all hover:scale-105 active:scale-95 cursor-pointer">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                    <span>Download Resume</span>
                </a>
                @else
                <a href="#contact" class="inline-flex items-center gap-2.5 rounded-2xl bg-gradient-to-r from-blue-600 via-indigo-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 text-white font-extrabold px-6 py-4 text-sm shadow-xl shadow-blue-500/20 border border-white/20 transition-all hover:scale-105 active:scale-95 cursor-pointer">
                    <span>Contact For CV</span>
                </a>
                @endif
            </div>

        </div>
    </div>
</section>

