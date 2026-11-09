@php
    $footerSocials = (isset($socialLinks) && count($socialLinks)) 
        ? $socialLinks 
        : \App\Models\SocialLink::where('is_active', true)->orderBy('sort_order', 'asc')->get();
@endphp

<footer class="mt-20 sm:mt-24 border-t border-white/80 dark:border-white/10 bg-white/60 dark:bg-slate-900/60 backdrop-blur-2xl py-10 sm:py-14 transition-colors">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-12 gap-8 items-center">
            
            {{-- Left Info (Centered on Mobile, Left-aligned on Desktop) --}}
            <div class="md:col-span-6 flex flex-col items-center text-center md:items-start md:text-left space-y-3">
                <div class="flex items-center justify-center md:justify-start gap-3">
                    <span class="flex h-9 w-9 items-center justify-center rounded-2xl bg-blue-600 text-white font-extrabold text-base shadow-lg shadow-blue-500/25">
                        {{ strtoupper(substr($profile->name ?? 'A', 0, 1)) }}
                    </span>
                    <span class="font-extrabold text-lg text-slate-900 dark:text-white tracking-tight">
                        {{ $profile->name ?? 'Asfar Khan' }}
                    </span>
                </div>
                <p class="text-xs text-slate-600 dark:text-slate-300 max-w-md mx-auto md:mx-0 leading-relaxed">
                    {{ $profile->bio ?? 'Full-Stack Software Engineer & Python Specialist building modern web applications, REST APIs, microservices, and AI integrations.' }}
                </p>
            </div>

            {{-- Right Navigation & Socials (Centered on Mobile, Right-aligned on Desktop) --}}
            <div class="md:col-span-6 flex flex-col items-center text-center md:items-end md:text-right gap-4">
                
                {{-- Quick Nav Links --}}
                <div class="flex flex-wrap justify-center md:justify-end gap-x-4 gap-y-2 text-xs font-semibold text-slate-700 dark:text-slate-300">
                    <a href="{{ route('home') }}#home" class="hover:text-blue-600 dark:hover:text-cyan-400 transition-colors">Home</a>
                    <a href="{{ route('home') }}#about" class="hover:text-blue-600 dark:hover:text-cyan-400 transition-colors">About</a>
                    <a href="{{ route('home') }}#skills" class="hover:text-blue-600 dark:hover:text-cyan-400 transition-colors">Skills</a>
                    <a href="{{ route('projects.index') }}" class="hover:text-blue-600 dark:hover:text-cyan-400 transition-colors">Projects</a>
                    <a href="{{ route('home') }}#experience" class="hover:text-blue-600 dark:hover:text-cyan-400 transition-colors">Experience</a>
                    <a href="{{ route('home') }}#services" class="hover:text-blue-600 dark:hover:text-cyan-400 transition-colors">Services</a>
                    <a href="{{ route('blog.index') }}" class="hover:text-blue-600 dark:hover:text-cyan-400 transition-colors">Blog</a>
                    <a href="{{ route('home') }}#contact" class="hover:text-blue-600 dark:hover:text-cyan-400 transition-colors">Contact</a>
                </div>

                {{-- Social Icons Bar (Centered on Mobile) --}}
                @if(!empty($footerSocials) && count($footerSocials))
                <div class="flex items-center justify-center md:justify-end gap-2.5">
                    @foreach($footerSocials as $link)
                    <a href="{{ $link->url }}" target="_blank" rel="noopener noreferrer"
                       class="flex h-9 w-9 items-center justify-center rounded-2xl bg-white/80 dark:bg-slate-800/80 border border-white/80 dark:border-white/10 text-slate-700 dark:text-slate-300 hover:text-blue-600 dark:hover:text-cyan-400 hover:scale-110 transition-all shadow-sm"
                       title="{{ $link->platform }}">
                        @include('partials.social-icon', ['platform' => $link->platform])
                    </a>
                    @endforeach
                </div>
                @endif

                <p class="text-[11px] font-mono text-slate-500 dark:text-slate-400 pt-1">
                    © {{ date('Y') }} {{ $profile->name ?? 'Asfar Khan' }}. All rights reserved.
                </p>
            </div>

        </div>
    </div>
</footer>
