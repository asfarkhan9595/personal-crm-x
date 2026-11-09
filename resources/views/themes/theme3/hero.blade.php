<section id="home" class="relative py-16 sm:py-24 flex items-center overflow-hidden min-h-[90vh]">
    {{-- Ambient radial frosted background glows --}}
    <div class="pointer-events-none absolute left-1/4 top-10 -z-10 h-96 w-96 rounded-full bg-blue-500/20 blur-3xl animate-pulse"></div>
    <div class="pointer-events-none absolute right-1/4 bottom-10 -z-10 h-[28rem] w-[28rem] rounded-full bg-cyan-500/20 blur-3xl animate-pulse"></div>
    <div class="pointer-events-none absolute left-10 bottom-20 -z-10 h-72 w-72 rounded-full bg-indigo-500/15 blur-3xl"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">

            {{-- Left Content Column --}}
            <div class="lg:col-span-7 flex flex-col items-start gap-6 max-w-full overflow-hidden">

                {{-- Status Pill --}}
                <div class="inline-flex items-center gap-2 px-3.5 sm:px-4 py-1.5 rounded-full bg-blue-500/10 dark:bg-cyan-500/10 border border-blue-500/20 dark:border-cyan-500/20 text-blue-600 dark:text-cyan-400 text-xs font-bold uppercase tracking-wider backdrop-blur-xl shadow-sm max-w-full">
                    <span class="relative flex h-2.5 w-2.5 shrink-0">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-blue-500 dark:bg-cyan-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-blue-600 dark:bg-cyan-400"></span>
                    </span>
                    <span class="truncate">{{ $profile->primary_role ?? 'Full-Stack Developer & Python Specialist' }}</span>
                </div>

                {{-- Main Headline --}}
                <div class="space-y-3 w-full">
                    <h1 class="text-3xl sm:text-5xl lg:text-6xl font-extrabold tracking-tight text-slate-900 dark:text-white leading-[1.15] break-words">
                        Crafting <span class="bg-gradient-to-r from-blue-600 via-cyan-500 to-indigo-600 dark:from-cyan-400 dark:via-sky-300 dark:to-blue-400 bg-clip-text text-transparent">frosted glass digital experiences</span> &amp; backend systems.
                    </h1>
                </div>

                {{-- Supporting Bio --}}
                <p class="text-sm sm:text-base lg:text-lg leading-relaxed text-slate-600 dark:text-slate-300 max-w-2xl font-normal break-words">
                    {{ $profile->hero_supporting_text ?? $profile->bio ?? 'Full-stack software engineer specializing in modern web applications, Python microservices, AI integrations, and cloud architectures.' }}
                </p>

                {{-- Action Buttons --}}
                <div class="flex flex-wrap items-center gap-4 pt-2">
                    <a href="#projects" class="inline-flex items-center gap-2 px-6 py-3.5 rounded-full bg-blue-600 hover:bg-blue-700 text-white font-extrabold text-xs shadow-lg shadow-blue-500/25 transition-all hover:scale-105 active:scale-95">
                        <span>Explore Portfolio</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                    </a>

                    @if(!empty($activeResume) || !empty($resume))
                    <a href="{{ route('resume.download') }}" target="_blank" class="inline-flex items-center gap-2 px-6 py-3.5 rounded-full bg-white/80 dark:bg-slate-800/80 hover:bg-white dark:hover:bg-slate-800 text-slate-800 dark:text-white font-bold text-xs border border-slate-200 dark:border-slate-700 backdrop-blur-xl shadow-md transition-all hover:scale-105 active:scale-95">
                        <svg class="w-4 h-4 text-blue-600 dark:text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                        <span>Download Resume</span>
                    </a>
                    @else
                    <a href="#contact" class="inline-flex items-center gap-2 px-6 py-3.5 rounded-full bg-white/80 dark:bg-slate-800/80 hover:bg-white dark:hover:bg-slate-800 text-slate-800 dark:text-white font-bold text-xs border border-slate-200 dark:border-slate-700 backdrop-blur-xl shadow-md transition-all hover:scale-105 active:scale-95">
                        <span>Contact Me</span>
                    </a>
                    @endif
                </div>

                {{-- Social Icons --}}
                @if(isset($socialLinks) && count($socialLinks) > 0)
                <div class="flex items-center gap-2.5 pt-4">
                    @foreach($socialLinks as $link)
                    <a href="{{ $link->url }}" target="_blank" rel="noopener noreferrer"
                       class="flex h-10 w-10 items-center justify-center rounded-full bg-white/70 dark:bg-slate-800/70 border border-white/80 dark:border-white/10 text-slate-600 dark:text-slate-300 hover:text-blue-600 dark:hover:text-cyan-400 hover:scale-110 backdrop-blur-xl shadow-sm transition-all"
                       title="{{ $link->platform }}">
                        @include('partials.social-icon', ['platform' => $link->platform])
                    </a>
                    @endforeach
                </div>
                @endif
            </div>

            {{-- Right Column Frosted Glass Profile Card --}}
            <div class="lg:col-span-5">
                <div class="relative p-7 rounded-3xl bg-white/70 dark:bg-slate-900/70 backdrop-blur-2xl border border-white/80 dark:border-white/10 shadow-2xl shadow-blue-500/10 space-y-6">
                    <div class="flex items-center justify-between border-b border-slate-200/60 dark:border-slate-800/60 pb-4">
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 rounded-2xl bg-gradient-to-tr from-blue-600 to-cyan-400 flex items-center justify-center text-white font-extrabold text-lg shadow-md">
                                {{ strtoupper(substr($profile->name ?? 'A', 0, 1)) }}
                            </div>
                            <div>
                                <h3 class="font-extrabold text-base text-slate-900 dark:text-white">{{ $profile->name ?? 'Asfar Khan' }}</h3>
                                <p class="text-xs text-blue-600 dark:text-cyan-400 font-semibold">{{ $profile->title ?? ($profile->primary_role ?? 'Senior Engineer') }}</p>
                            </div>
                        </div>
                        <span class="px-3 py-1 rounded-full bg-blue-500/10 dark:bg-cyan-500/10 border border-blue-500/20 text-blue-600 dark:text-cyan-400 text-[10px] font-bold uppercase tracking-wider">
                            Verified
                        </span>
                    </div>

                    <div class="space-y-3">
                        <div class="p-4 rounded-2xl bg-slate-50/80 dark:bg-slate-800/50 border border-slate-200/50 dark:border-slate-700/50 text-xs leading-relaxed space-y-2">
                            <div class="flex justify-between items-center text-slate-500 dark:text-slate-400 font-medium">
                                <span>Primary Stack</span>
                                <span class="font-bold text-slate-800 dark:text-slate-200">Python • Laravel • JS</span>
                            </div>
                            <div class="flex justify-between items-center text-slate-500 dark:text-slate-400 font-medium">
                                <span>Architecture</span>
                                <span class="font-bold text-blue-600 dark:text-cyan-400">REST API &amp; Microservices</span>
                            </div>
                            <div class="flex justify-between items-center text-slate-500 dark:text-slate-400 font-medium">
                                <span>Status</span>
                                <span class="inline-flex items-center gap-1.5 text-emerald-500 font-bold">
                                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                                    Available
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="pt-2 flex items-center justify-between text-xs font-semibold text-slate-500 dark:text-slate-400">
                        <span>Location: {{ $profile->location ?? 'India' }}</span>
                        <a href="#contact" class="text-blue-600 dark:text-cyan-400 hover:underline flex items-center gap-1">
                            <span>Let's Talk</span>
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                        </a>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>
