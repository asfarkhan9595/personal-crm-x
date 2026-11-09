<section id="home" class="relative py-12 sm:py-16 flex items-center overflow-hidden">
    {{-- Ambient glowing blur orbs --}}
    <div class="pointer-events-none absolute left-10 top-10 -z-10 h-72 w-72 rounded-full bg-blue-500/15 blur-3xl animate-nexus-pulse"></div>
    <div class="pointer-events-none absolute right-10 bottom-10 -z-10 h-80 w-80 rounded-full bg-indigo-500/15 blur-3xl animate-nexus-float"></div>

    <div class="max-w-6xl mx-auto px-4 sm:px-6 w-full">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-center">

            {{-- Left Column Content --}}
            <div class="lg:col-span-7 flex flex-col items-start gap-4">

                {{-- Status Pill --}}
                <div class="inline-flex items-center gap-2 rounded-full border border-indigo-500/20 bg-indigo-500/10 dark:border-cyan-400/30 dark:bg-cyan-500/10 px-3.5 py-1 text-xs font-semibold text-indigo-600 dark:text-cyan-400 backdrop-blur-md">
                    <span class="relative flex h-2 w-2">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-cyan-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 bg-cyan-400"></span>
                    </span>
                    <span class="font-mono text-[11px] uppercase tracking-wider">// Available for Projects</span>
                </div>

                {{-- Headline --}}
                <div class="space-y-1">
                    <h1 class="text-3xl sm:text-5xl font-black tracking-tight text-slate-900 dark:text-white leading-tight">
                        Hi, I'm <span class="bg-gradient-to-r from-indigo-600 via-blue-600 to-cyan-500 dark:from-cyan-400 dark:via-sky-300 dark:to-indigo-400 bg-clip-text text-transparent">{{ $profile->name ?? 'Asfar Khan' }}</span>
                    </h1>
                    <p class="text-base sm:text-lg font-bold text-slate-700 dark:text-slate-300 font-mono tracking-tight">
                        {{ $profile->primary_role ?? 'Full Stack Developer & AI Specialist' }}
                    </p>
                </div>

                {{-- Supporting Tech Tagline --}}
                <p class="text-xs sm:text-sm leading-relaxed text-slate-600 dark:text-slate-300 max-w-xl font-normal">
                    {{ $profile->hero_supporting_text ?? $profile->bio ?? 'PHP • Laravel • Python • JavaScript • REST APIs • AI Integration • Browser Automation' }}
                </p>

                {{-- CTA Buttons --}}
                <div class="flex flex-wrap items-center gap-3 pt-1">
                    <a href="#projects" class="inline-flex items-center gap-1.5 rounded-full bg-gradient-to-r from-indigo-600 via-blue-600 to-cyan-600 hover:from-indigo-700 hover:to-cyan-700 text-white font-extrabold px-6 py-2.5 text-xs shadow-md shadow-indigo-500/20 transition-all hover:scale-105 active:scale-95">
                        <span>View Work</span>
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                    </a>

                    @if(!empty($activeResume) || !empty($resume))
                    <a href="{{ route('resume.download') }}" target="_blank" class="inline-flex items-center gap-1.5 rounded-full bg-white/70 dark:bg-slate-800/70 hover:bg-white dark:hover:bg-slate-800 text-slate-800 dark:text-white font-semibold px-5 py-2.5 text-xs border border-slate-200 dark:border-white/10 backdrop-blur-md shadow-2xs transition-all hover:scale-105">
                        <svg class="w-3.5 h-3.5 text-indigo-600 dark:text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                        <span>Resume PDF</span>
                    </a>
                    @endif
                </div>

                {{-- Social Icons --}}
                @if(isset($socialLinks) && count($socialLinks) > 0)
                <div class="flex items-center gap-2 pt-2">
                    @foreach($socialLinks as $link)
                    <a href="{{ $link->url }}" target="_blank" rel="noopener noreferrer"
                       class="flex h-8 w-8 items-center justify-center rounded-full bg-white/60 dark:bg-slate-800/60 border border-slate-200 dark:border-white/10 text-slate-600 dark:text-slate-300 hover:text-indigo-600 dark:hover:text-cyan-400 transition-all text-xs"
                       title="{{ $link->platform }}">
                        @include('partials.social-icon', ['platform' => $link->platform])
                    </a>
                    @endforeach
                </div>
                @endif
            </div>

            {{-- Right Column Compact Showcase Card --}}
            <div class="lg:col-span-5">
                <div class="relative rounded-3xl nexus-glass-card p-5 shadow-xl">
                    <div class="flex items-center justify-between border-b border-slate-200/80 dark:border-white/10 pb-2 mb-3">
                        <div class="flex items-center gap-1.5">
                            <span class="w-2.5 h-2.5 rounded-full bg-red-400"></span>
                            <span class="w-2.5 h-2.5 rounded-full bg-amber-400"></span>
                            <span class="w-2.5 h-2.5 rounded-full bg-emerald-400"></span>
                            <span class="ml-1.5 font-mono text-[11px] text-slate-600 dark:text-slate-400 font-semibold">nexus_spec.py</span>
                        </div>
                        <span class="font-mono text-[9px] text-indigo-600 dark:text-cyan-400 bg-indigo-500/10 dark:bg-cyan-500/10 px-2.5 py-0.5 rounded-full font-bold border border-indigo-500/20">NEXUS GLASS ENGINE</span>
                    </div>

                    <div class="font-mono text-[11px] leading-relaxed space-y-2">
                        <div class="p-3 rounded-2xl bg-white/40 dark:bg-slate-900/40 border border-slate-200/50 dark:border-white/10 space-y-0.5 text-slate-700 dark:text-slate-300">
                            <p><span class="text-indigo-600 dark:text-cyan-400 font-bold">developer</span> = <span class="text-blue-600 dark:text-sky-300">"{{ $profile->name ?? 'Asfar Khan' }}"</span></p>
                            <p><span class="text-indigo-600 dark:text-cyan-400 font-bold">stack</span> = [<span class="text-blue-600 dark:text-sky-300">"Laravel"</span>, <span class="text-blue-600 dark:text-sky-300">"Python"</span>, <span class="text-blue-600 dark:text-sky-300">"MySQL"</span>]</p>
                            <p><span class="text-indigo-600 dark:text-cyan-400 font-bold">render</span> = <span class="text-emerald-600 dark:text-emerald-400 font-bold">"Server Blade"</span></p>
                        </div>
                        <div class="pt-1 flex items-center justify-between text-[10px] text-slate-500 dark:text-slate-400">
                            <span class="flex items-center gap-1">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                                Production Active
                            </span>
                            <span>UTF-8</span>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>
