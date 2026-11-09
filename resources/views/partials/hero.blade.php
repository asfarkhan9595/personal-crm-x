{{-- Hero Section — supports Modern / Glass / Mono themes via data-theme attribute --}}

{{-- ===== MODERN + GLASS HERO (2-col layout) ===== --}}
<section id="home" class="hero-default relative flex min-h-screen items-center pt-24 pb-16 overflow-hidden">
    {{-- Ambient orbs --}}
    <div class="gradient-orb animate-float absolute left-1/4 top-1/4 -z-10 h-64 w-64 bg-emerald-500/10 dark:bg-emerald-500/5"></div>
    <div class="gradient-orb animate-float-delayed absolute bottom-1/4 right-1/3 -z-10 h-48 w-48 bg-teal-500/8 dark:bg-teal-500/5"></div>

    {{-- Glass ambient orbs (only visible on glass theme via CSS) --}}
    <div class="glass-orb pointer-events-none absolute left-10 top-20 -z-10 h-80 w-80 rounded-full bg-blue-500/20 blur-3xl animate-pulse-glow"></div>
    <div class="glass-orb pointer-events-none absolute right-10 bottom-20 -z-10 h-96 w-96 rounded-full bg-purple-500/20 blur-3xl animate-float-delayed"></div>
    <div class="glass-orb pointer-events-none absolute left-1/3 bottom-10 -z-10 h-72 w-72 rounded-full bg-pink-500/15 blur-3xl animate-float"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full">
        <div class="grid grid-cols-1 gap-12 lg:grid-cols-2 lg:items-center">

            {{-- ===== Left Side Text ===== --}}
            <div class="flex flex-col items-start gap-6">

                {{-- Status badge — Modern --}}
                <div class="modern-badge">
                    <span class="inline-flex items-center gap-2 rounded-full border border-emerald-500/30 bg-emerald-500/10 px-4 py-1.5 text-sm font-medium text-emerald-500">
                        <span class="relative flex h-2 w-2">
                            <span class="absolute inline-flex h-full w-full animate-ping rounded-full bg-emerald-400 opacity-75"></span>
                            <span class="relative inline-flex h-2 w-2 rounded-full bg-emerald-500"></span>
                        </span>
                        {{ $profile->primary_role ?? 'Python & AI Automation Developer' }}
                    </span>
                </div>

                {{-- Status badge — Glass (same slot, different look via CSS) --}}
                <div class="glass-badge hidden">
                    <span class="inline-flex items-center gap-2 rounded-full border border-blue-500/30 bg-blue-500/10 dark:border-cyan-500/30 dark:bg-cyan-500/10 px-4 py-1.5 text-sm font-medium text-blue-600 dark:text-cyan-400 backdrop-blur-md shadow-sm">
                        <svg class="h-4 w-4 animate-spin text-blue-500 dark:text-cyan-400" style="animation-duration:8s" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3l14 9-14 9V3z"/></svg>
                        {{ $profile->primary_role ?? 'Python & AI Automation Developer' }}
                    </span>
                </div>

                {{-- Heading --}}
                <h1 class="hero-heading gradient-text text-4xl font-extrabold tracking-tight sm:text-5xl lg:text-6xl">
                    Hi, I'm {{ $profile->name ?? 'Developer' }}
                </h1>

                {{-- Sub-paragraph --}}
                <p class="max-w-xl text-lg leading-relaxed text-slate-600 dark:text-slate-400 hero-para">
                    {{ $profile->hero_supporting_text ?? $profile->bio ?? 'Passionate software developer specializing in Python, AI automation, Laravel backend development, and building modern web applications.' }}
                </p>

                {{-- CTA Buttons --}}
                <div class="flex flex-wrap gap-4">
                    {{-- Modern primary CTA --}}
                    <a href="#projects" class="modern-cta inline-flex items-center gap-2 rounded-xl bg-emerald-500 hover:bg-emerald-600 text-white font-medium px-6 py-3 text-base shadow-lg shadow-emerald-500/25 transition-all hover:scale-[1.02] active:scale-[0.98] btn-shimmer">
                        View My Projects
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                    </a>

                    {{-- Glass primary CTA --}}
                    <a href="#projects" class="glass-cta hidden inline-flex items-center gap-2 rounded-xl bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-medium px-6 py-3 text-base shadow-lg shadow-blue-500/25 border border-white/20 transition-all hover:scale-[1.02] active:scale-[0.98]">
                        View My Projects
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                    </a>

                    {{-- Resume button --}}
                    @if(!empty($resume) && !empty($resume->file_path))
                    <a href="{{ route('resume.download') }}" target="_blank"
                       class="modern-secondary inline-flex items-center gap-2 rounded-xl bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-800 dark:text-slate-200 font-medium px-5 py-3 text-base border border-slate-200 dark:border-slate-700 transition-all hover:scale-[1.02] active:scale-[0.98]">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                        Download Resume
                    </a>
                    <a href="{{ route('resume.download') }}" target="_blank"
                       class="glass-secondary hidden inline-flex items-center gap-2 rounded-xl bg-white/60 dark:bg-slate-800/60 hover:bg-white/90 dark:hover:bg-slate-800/90 text-slate-800 dark:text-slate-200 font-medium px-5 py-3 text-base border border-white/80 dark:border-white/10 backdrop-blur-xl shadow-sm transition-all hover:scale-[1.02] active:scale-[0.98]">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                        Download Resume
                    </a>
                    @endif

                    {{-- Contact --}}
                    <a href="#contact"
                       class="modern-secondary inline-flex items-center gap-2 rounded-xl bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-800 dark:text-slate-200 font-medium px-5 py-3 text-base border border-slate-200 dark:border-slate-700 transition-all hover:scale-[1.02] active:scale-[0.98]">
                        Contact Me
                    </a>
                    <a href="#contact"
                       class="glass-secondary hidden inline-flex items-center gap-2 rounded-xl bg-white/60 dark:bg-slate-800/60 hover:bg-white/90 dark:hover:bg-slate-800/90 text-slate-800 dark:text-slate-200 font-medium px-5 py-3 text-base border border-white/80 dark:border-white/10 backdrop-blur-xl shadow-sm transition-all hover:scale-[1.02] active:scale-[0.98]">
                        Contact Me
                    </a>
                </div>

                {{-- Social links --}}
                @if(!empty($socialLinks) && count($socialLinks))
                <div class="flex items-center gap-3 flex-wrap">
                    @foreach($socialLinks as $link)
                    <a href="{{ $link->url }}" target="_blank" rel="noopener noreferrer"
                       class="flex h-9 w-9 items-center justify-center rounded-xl bg-slate-100 dark:bg-slate-800 text-slate-500 dark:text-slate-400 hover:text-emerald-500 dark:hover:text-emerald-400 hover:bg-slate-200 dark:hover:bg-slate-700 border border-slate-200 dark:border-slate-700 transition-all"
                       title="{{ $link->platform }}">
                        @include('partials.social-icon', ['platform' => $link->platform])
                    </a>
                    @endforeach
                </div>
                @endif
            </div>

            {{-- ===== Right Side — Code Card ===== --}}
            <div class="hidden lg:block">
                <div class="group relative">
                    {{-- Modern glow --}}
                    <div class="modern-glow absolute -inset-1 rounded-2xl bg-gradient-to-r from-emerald-500/20 via-transparent to-teal-500/20 opacity-0 blur-xl transition-opacity duration-700 group-hover:opacity-100"></div>
                    {{-- Glass glow --}}
                    <div class="glass-glow hidden absolute -inset-2 rounded-3xl bg-gradient-to-r from-cyan-500/30 via-blue-500/20 to-purple-500/30 blur-2xl transition-all duration-500 group-hover:blur-3xl opacity-80"></div>

                    {{-- Modern code card --}}
                    <div class="modern-card relative overflow-hidden rounded-xl border border-slate-200 bg-white shadow-2xl dark:border-slate-700/50 dark:bg-slate-900/80">
                        <div class="flex items-center gap-2 border-b border-slate-200 bg-slate-50 px-4 py-3 dark:border-slate-700/50 dark:bg-slate-800/50">
                            <div class="flex gap-1.5">
                                <div class="h-3 w-3 rounded-full bg-red-500/80"></div>
                                <div class="h-3 w-3 rounded-full bg-yellow-500/80"></div>
                                <div class="h-3 w-3 rounded-full bg-green-500/80"></div>
                            </div>
                            <span class="ml-2 text-xs text-slate-500 dark:text-slate-500">~/asfar-khan/config.py</span>
                        </div>
                        <div class="p-6 font-mono text-sm leading-loose" id="modern-code-lines">
                            @php
                                $primaryRole = $profile->primary_role ?? 'python_developer';
                                $primaryRoleSnake = strtolower(preg_replace('/[^a-z0-9]+/i', '_', trim($primaryRole)));
                                $codeLines = [
                                    ['prefix'=>'>','key'=>'role','value'=>'"'.$primaryRoleSnake.'"','color'=>'text-amber-400 dark:text-amber-300'],
                                    ['prefix'=>'>','key'=>'ai_automation','value'=>'true','color'=>'text-blue-500 dark:text-blue-400'],
                                    ['prefix'=>'>','key'=>'backend','value'=>'["laravel", "rest_api", "mysql"]','color'=>'text-amber-400 dark:text-amber-300'],
                                    ['prefix'=>'>','key'=>'browser','value'=>'["chrome_extensions"]','color'=>'text-amber-400 dark:text-amber-300'],
                                    ['prefix'=>'>','key'=>'status','value'=>'"open_to_opportunities"','color'=>'text-emerald-500 dark:text-emerald-400'],
                                ];
                            @endphp
                            @foreach($codeLines as $i => $line)
                            <div class="typed-code-line flex items-center gap-1" data-text="{{ $line['value'] }}" data-delay="{{ 800 + $i * 500 }}">
                                <span class="text-emerald-500">{{ $line['prefix'] }}</span>
                                <span class="text-slate-500 dark:text-slate-400">{{ $line['key'] }}</span>
                                <span class="text-slate-400 dark:text-slate-600">:</span>
                                <span class="{{ $line['color'] }} code-val"></span>
                                <span class="typing-cursor"></span>
                            </div>
                            @endforeach
                        </div>
                        <div class="flex items-center justify-between border-t border-slate-200 bg-slate-50 px-4 py-1.5 text-xs dark:border-slate-700/50 dark:bg-slate-800/50">
                            <div class="flex items-center gap-2">
                                <span class="flex h-2 w-2 rounded-full bg-emerald-500"></span>
                                <span class="text-slate-500">Python 3.12</span>
                            </div>
                            <span class="text-slate-500">UTF-8</span>
                        </div>
                    </div>

                    {{-- Glass code card --}}
                    <div class="glass-card hidden relative overflow-hidden rounded-2xl border border-white/70 dark:border-white/15 bg-white/60 dark:bg-slate-900/65 backdrop-blur-2xl shadow-2xl shadow-blue-500/10">
                        <div class="flex items-center gap-2 border-b border-white/50 dark:border-white/10 bg-white/40 dark:bg-slate-800/40 px-4 py-3 backdrop-blur-md">
                            <div class="flex gap-1.5">
                                <div class="h-3 w-3 rounded-full bg-red-400/80"></div>
                                <div class="h-3 w-3 rounded-full bg-amber-400/80"></div>
                                <div class="h-3 w-3 rounded-full bg-emerald-400/80"></div>
                            </div>
                            <span class="ml-2 text-xs font-medium text-slate-600 dark:text-slate-400 flex items-center gap-1.5">
                                <svg class="h-3.5 w-3.5 text-blue-500 dark:text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l3 3-3 3m5 0h3M5 20h14a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                ~/asfar-khan/config.py
                            </span>
                        </div>
                        <div class="p-6 font-mono text-sm leading-loose">
                            @foreach($codeLines as $i => $line)
                            <div class="flex items-center gap-1">
                                <span class="text-cyan-400">{{ $line['prefix'] }}</span>
                                <span class="text-slate-400">{{ $line['key'] }}</span>
                                <span class="text-slate-500">:</span>
                                <span class="{{ $line['color'] }}">{{ $line['value'] }}</span>
                            </div>
                            @endforeach
                        </div>
                        <div class="flex items-center justify-between border-t border-white/50 dark:border-white/10 bg-white/30 dark:bg-slate-800/30 px-4 py-2 text-xs backdrop-blur-md">
                            <div class="flex items-center gap-2">
                                <span class="flex h-2 w-2 rounded-full bg-cyan-400 animate-ping"></span>
                                <span class="text-slate-600 dark:text-slate-400 font-medium">Python 3.12 (Glass Engine)</span>
                            </div>
                            <span class="text-slate-500">UTF-8</span>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>

{{-- ===== MONO HERO (1-col centered, completely different layout) ===== --}}
<section id="home-mono" class="hero-mono hidden relative pt-32 pb-20 md:pt-40 md:pb-28 overflow-hidden">
    {{-- Background glows --}}
    <div class="absolute top-1/4 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[600px] h-[300px] bg-cyan-500/10 blur-[120px] rounded-full pointer-events-none -z-10"></div>
    <div class="absolute top-1/3 right-10 w-[400px] h-[250px] bg-violet-500/10 blur-[100px] rounded-full pointer-events-none -z-10"></div>

    <div class="mx-auto max-w-5xl px-4 sm:px-6">
        <div class="flex flex-col items-center text-center">

            {{-- Tech Status Badge --}}
            <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-cyan-500/10 text-cyan-600 dark:text-cyan-400 border border-cyan-500/20 mb-6 backdrop-blur-md">
                <span class="relative flex h-2 w-2">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-cyan-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-2 w-2 bg-cyan-400"></span>
                </span>
                @php
                    $monoTaglineParts = array_filter([$profile->primary_role ?? null]);
                    if(!empty($profile->secondary_roles)) {
                        $secondary = is_array($profile->secondary_roles)
                            ? $profile->secondary_roles
                            : array_map('trim', explode(',', $profile->secondary_roles));
                        $monoTaglineParts = array_merge($monoTaglineParts, $secondary);
                    }
                    $monoTagline = !empty($monoTaglineParts)
                        ? implode(' • ', $monoTaglineParts)
                        : 'Backend & Full-Stack Developer • Python & AI Automation Specialist';
                @endphp
                <span class="font-mono text-xs font-semibold uppercase tracking-wider">{{ $monoTagline }}</span>
            </div>

            {{-- Main Headline --}}
            <h1 class="text-4xl sm:text-6xl lg:text-7xl font-extrabold tracking-tight text-slate-900 dark:text-white max-w-4xl leading-[1.15]">
                Hi, I'm <span class="bg-gradient-to-r from-cyan-400 via-sky-400 to-violet-400 bg-clip-text text-transparent">{{ $profile->name ?? 'Developer' }}</span>
            </h1>

            <p class="mt-6 text-base sm:text-lg md:text-xl text-slate-600 dark:text-slate-400 max-w-2xl font-normal leading-relaxed">
                {{ $profile->bio ?? $profile->hero_supporting_text ?? 'Building high-performance backend APIs, full-stack web applications, custom Chrome extensions, and intelligent AI automation systems.' }}
            </p>

            {{-- CTA Buttons --}}
            <div class="mt-8 flex flex-wrap items-center justify-center gap-4">
                <a href="#projects"
                   class="inline-flex items-center justify-center gap-2 px-6 py-3 rounded-full text-xs font-mono font-bold text-slate-950 bg-gradient-to-r from-cyan-400 to-violet-400 hover:from-cyan-300 hover:to-violet-300 shadow-lg shadow-cyan-500/25 transition-all hover:scale-[1.02] active:scale-[0.98]">
                    <span>Explore Projects</span>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                </a>
                <a href="#contact"
                   class="inline-flex items-center justify-center gap-2 px-6 py-3 rounded-full text-xs font-mono font-bold text-slate-900 dark:text-white bg-white dark:bg-[#0F1117] hover:bg-slate-100 dark:hover:bg-[#151821] border border-slate-300 dark:border-white/15 transition-all hover:scale-[1.02] active:scale-[0.98]">
                    <svg class="w-4 h-4 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l3 3-3 3m5 0h3M5 20h14a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    <span>Get In Touch</span>
                </a>
            </div>

            {{-- Social Icons --}}
            @if(!empty($socialLinks) && count($socialLinks))
            <div class="mt-10 flex items-center justify-center gap-3">
                @foreach($socialLinks as $link)
                <a href="{{ $link->url }}" target="_blank" rel="noopener noreferrer"
                   class="flex h-10 w-10 items-center justify-center rounded-full bg-white dark:bg-[#0F1117] border border-slate-200 dark:border-white/10 text-slate-400 hover:text-cyan-400 hover:border-cyan-500/40 transition-all shadow-sm"
                   title="{{ $link->platform }}">
                    @include('partials.social-icon', ['platform' => $link->platform])
                </a>
                @endforeach
            </div>
            @endif

            {{-- Terminal / Spec Mockup Box --}}
            <div class="mt-14 w-full max-w-3xl rounded-2xl bg-slate-900 dark:bg-[#0F1117] border border-slate-200/20 dark:border-white/10 shadow-2xl p-4 sm:p-6 text-left font-mono">
                <div class="flex items-center justify-between border-b border-white/10 pb-3 mb-4">
                    <div class="flex items-center gap-2">
                        <span class="w-3 h-3 rounded-full bg-red-500/80 inline-block"></span>
                        <span class="w-3 h-3 rounded-full bg-amber-500/80 inline-block"></span>
                        <span class="w-3 h-3 rounded-full bg-emerald-500/80 inline-block"></span>
                        <span class="ml-2 text-xs text-slate-400">architecture-config.json</span>
                    </div>
                    <span class="text-[10px] text-cyan-400 bg-cyan-500/10 px-2 py-0.5 rounded border border-cyan-500/20 font-bold">● ACTIVE PIPELINE</span>
                </div>
                <div class="space-y-2 text-xs sm:text-sm text-slate-300">
                    <p><span class="text-cyan-400">$</span> <span class="text-violet-400">python</span> --version &amp;&amp; <span class="text-violet-400">fastapi</span> dev</p>
                    <p class="text-slate-400 text-xs">// Scalable REST APIs, Full-Stack applications &amp; AI workflows</p>
                    <div class="pt-2 grid grid-cols-1 sm:grid-cols-3 gap-2">
                        <div class="bg-[#151821] p-3 rounded-xl border border-white/5">
                            <div class="text-[10px] text-cyan-400 uppercase tracking-wide font-bold">Backend Development</div>
                            <div class="text-xs text-white mt-1 font-semibold">Python, FastAPI, Laravel</div>
                        </div>
                        <div class="bg-[#151821] p-3 rounded-xl border border-white/5">
                            <div class="text-[10px] text-violet-400 uppercase tracking-wide font-bold">Full-Stack Frontend</div>
                            <div class="text-xs text-white mt-1 font-semibold">React, Vite, Tailwind CSS</div>
                        </div>
                        <div class="bg-[#151821] p-3 rounded-xl border border-white/5">
                            <div class="text-[10px] text-emerald-400 uppercase tracking-wide font-bold">AI &amp; Automation</div>
                            <div class="text-xs text-white mt-1 font-semibold">LLMs, Web Scraping, Extensions</div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>
