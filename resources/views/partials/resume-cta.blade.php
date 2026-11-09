@if($activeResume)
<section class="py-16 relative bg-gradient-to-r from-emerald-600 to-teal-700 text-white overflow-hidden">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="flex flex-col md:flex-row items-center justify-between gap-8 text-center md:text-left">
            <div>
                <h2 class="text-3xl font-extrabold tracking-tight sm:text-4xl">
                    Interested in working together?
                </h2>
                <p class="mt-2 text-emerald-100 text-lg max-w-2xl">
                    Download my comprehensive CV to learn more about my background, technical skill sets, and work experience.
                </p>
            </div>

            <a href="{{ route('resume.download') }}" target="_blank" class="inline-flex items-center gap-2 rounded-xl bg-white text-slate-900 hover:bg-slate-100 font-bold px-6 py-3.5 text-base shadow-xl transition-all hover:scale-105 active:scale-95 shrink-0">
                <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                Download Official Resume (PDF)
            </a>
        </div>
    </div>
</section>
@endif

