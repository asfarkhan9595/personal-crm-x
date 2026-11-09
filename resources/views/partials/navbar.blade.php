@php
    $reqTheme = strtolower((string)request()->query('theme', ''));
    $dbTheme = strtolower((string)($settings->active_theme ?? 'theme1'));
    $currentTheme = $reqTheme ?: $dbTheme;

    if (in_array($currentTheme, ['theme1', 'nexus-glass', 'nexus_glass', 'nexus'])) {
        $activeThemeLabel = 'Nexus Glass';
        $activeThemeKey = 'theme1';
    } elseif (in_array($currentTheme, ['theme2', 'vercel', 'mono'])) {
        $activeThemeLabel = 'Vercel Mono';
        $activeThemeKey = 'theme2';
    } elseif (in_array($currentTheme, ['theme3', 'glass'])) {
        $activeThemeLabel = 'Glass UI';
        $activeThemeKey = 'theme3';
    } else {
        $activeThemeLabel = 'Nexus Glass';
        $activeThemeKey = 'theme1';
    }

    $isHome = request()->routeIs('home');
    $isProjects = request()->routeIs('projects.*');
    $isBlog = request()->routeIs('blog.*');

    $activeClass = 'nav-link relative px-3 py-2 text-sm font-semibold text-emerald-500 dark:text-emerald-400 active-nav';
    $inactiveClass = 'nav-link relative px-3 py-2 text-sm font-medium text-slate-600 dark:text-slate-300 hover:text-emerald-500 dark:hover:text-emerald-400 transition-colors';

    $mobileActiveClass = 'block px-3 py-2 rounded-lg text-base font-bold text-emerald-500 dark:text-emerald-400 bg-emerald-500/10 active-nav';
    $mobileInactiveClass = 'block px-3 py-2 rounded-lg text-base font-medium text-slate-700 dark:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800';
@endphp

<nav class="fixed top-0 left-0 right-0 z-40 bg-white/90 dark:bg-slate-900/90 backdrop-blur-md border-b border-slate-200/60 dark:border-slate-800/60 transition-colors duration-300">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16 gap-4">

            {{-- ===== Logo / Brand ===== --}}
            <a href="{{ route('home') }}" class="flex items-center gap-2.5 shrink-0 group">
                <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-gradient-to-tr from-emerald-500 to-teal-400 text-white font-bold text-lg shadow-md group-hover:scale-105 transition-transform">
                    {{ strtoupper(substr($profile->name ?? 'A', 0, 1)) }}
                </span>
                <span class="font-extrabold text-lg tracking-tight text-slate-800 dark:text-slate-100">
                    {{ $profile->name ?? 'Portfolio' }}
                </span>
            </a>

            {{-- ===== Desktop Nav Links ===== --}}
            <div class="hidden md:flex items-center gap-1 flex-1 justify-center">
                <a href="{{ route('home') }}#home" data-section="home" class="{{ $isHome ? $activeClass : $inactiveClass }}">Home</a>
                <a href="{{ route('home') }}#about" data-section="about" class="{{ $inactiveClass }}">About</a>
                <a href="{{ route('home') }}#skills" data-section="skills" class="{{ $inactiveClass }}">Skills</a>
                <a href="{{ route('projects.index') }}" class="{{ $isProjects ? $activeClass : $inactiveClass }}">Projects</a>
                <a href="{{ route('home') }}#experience" data-section="experience" class="{{ $inactiveClass }}">Experience</a>
                <a href="{{ route('blog.index') }}" class="{{ $isBlog ? $activeClass : $inactiveClass }}">Blog</a>
                @if(!empty($resume))
                <a href="{{ route('resume.download') }}" target="_blank" class="{{ $inactiveClass }}">Resume</a>
                @endif
                <a href="{{ route('home') }}#contact" data-section="contact" class="{{ $inactiveClass }}">Contact</a>
            </div>

            {{-- ===== Right Controls ===== --}}
            <div class="flex items-center gap-2 shrink-0">

                {{-- Theme Selector Pill (AJAX Driven) --}}
                <div class="relative inline-block">
                    <button type="button" id="theme-dropdown-btn"
                            class="hidden md:inline-flex items-center gap-1.5 text-[10px] font-bold uppercase tracking-wider px-3 py-1.5 rounded-full border border-emerald-500/40 text-emerald-600 dark:text-emerald-400 hover:bg-emerald-500/10 transition-all cursor-pointer whitespace-nowrap">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 dark:bg-emerald-400 animate-pulse"></span>
                        <span id="current-theme-label">{{ $activeThemeLabel }}</span>
                        <svg class="w-3 h-3 ml-0.5 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"></path></svg>
                    </button>

                    <div id="theme-dropdown-menu" class="hidden absolute right-0 mt-2 w-44 rounded-xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 shadow-xl py-1.5 z-50 text-xs font-semibold">
                        <div class="px-3 py-1 text-[9px] font-bold uppercase text-slate-400 dark:text-slate-500 border-b border-slate-100 dark:border-slate-800 mb-1">
                            Switch Theme
                        </div>
                        <button type="button" data-theme="theme1" class="theme-option-btn w-full px-3 py-1.5 flex items-center justify-between {{ $activeThemeKey === 'theme1' ? 'text-emerald-600 dark:text-emerald-400 bg-emerald-500/10 font-bold' : 'hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-700 dark:text-slate-300' }} transition-colors cursor-pointer">
                            <span>💎 Nexus Glass</span>
                            @if($activeThemeKey === 'theme1') <span class="text-emerald-600 dark:text-emerald-400">✓</span> @endif
                        </button>
                        <button type="button" data-theme="theme3" class="theme-option-btn w-full px-3 py-1.5 flex items-center justify-between {{ $activeThemeKey === 'theme3' ? 'text-emerald-600 dark:text-emerald-400 bg-emerald-500/10 font-bold' : 'hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-700 dark:text-slate-300' }} transition-colors cursor-pointer">
                            <span>💧 Glass UI</span>
                            @if($activeThemeKey === 'theme3') <span class="text-emerald-600 dark:text-emerald-400">✓</span> @endif
                        </button>
                    </div>
                </div>

                {{-- Dark / Light Toggle --}}
                <button type="button" class="theme-toggle-btn p-2 rounded-xl text-slate-500 hover:text-slate-900 dark:text-slate-400 dark:hover:text-slate-100 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors" title="Toggle dark/light mode">
                    <svg class="w-5 h-5 hidden dark:block" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                    <svg class="w-5 h-5 block dark:hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path></svg>
                </button>

                {{-- Mobile Menu Button --}}
                <button type="button" id="mobile-menu-btn" class="md:hidden p-2 rounded-xl text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 focus:outline-none">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                </button>
            </div>

        </div>
    </div>

    {{-- Mobile Drawer --}}
    <div id="mobile-menu-drawer" class="hidden md:hidden border-t border-slate-200 dark:border-slate-800 bg-white/95 dark:bg-slate-900/95 px-4 pt-2 pb-4 space-y-1">
        <a href="{{ route('home') }}#home" data-section="home" class="{{ $isHome ? $mobileActiveClass : $mobileInactiveClass }}">Home</a>
        <a href="{{ route('home') }}#about" data-section="about" class="{{ $mobileInactiveClass }}">About</a>
        <a href="{{ route('home') }}#skills" data-section="skills" class="{{ $mobileInactiveClass }}">Skills</a>
        <a href="{{ route('projects.index') }}" class="{{ $isProjects ? $mobileActiveClass : $mobileInactiveClass }}">Projects</a>
        <a href="{{ route('home') }}#experience" data-section="experience" class="{{ $mobileInactiveClass }}">Experience</a>
        <a href="{{ route('blog.index') }}" class="{{ $isBlog ? $mobileActiveClass : $mobileInactiveClass }}">Blog</a>
        @if(!empty($resume))
        <a href="{{ route('resume.download') }}" target="_blank" class="{{ $mobileInactiveClass }}">Resume</a>
        @endif
        <a href="{{ route('home') }}#contact" data-section="contact" class="{{ $mobileInactiveClass }}">Contact</a>

        {{-- Mobile Theme Picker --}}
        <div class="pt-2 border-t border-slate-100 dark:border-slate-800 flex gap-2 flex-wrap">
            <button type="button" data-theme="theme1" class="theme-option-btn text-xs font-bold px-3 py-1.5 rounded-full border border-slate-200 dark:border-slate-700 text-slate-600 dark:text-slate-300">💎 Theme 1 (Nexus Glass)</button>
            <button type="button" data-theme="theme2" class="theme-option-btn text-xs font-bold px-3 py-1.5 rounded-full border border-slate-200 dark:border-slate-700 text-slate-600 dark:text-slate-300">✨ Theme 2 (Vercel)</button>
            <button type="button" data-theme="theme3" class="theme-option-btn text-xs font-bold px-3 py-1.5 rounded-full border border-slate-200 dark:border-slate-700 text-slate-600 dark:text-slate-300">💧 Theme 3 (Glass)</button>
        </div>
    </div>
</nav>

<!-- AJAX Theme Switch Script -->
<script>
document.addEventListener('DOMContentLoaded', () => {
    const dropdownBtn = document.getElementById('theme-dropdown-btn');
    const dropdownMenu = document.getElementById('theme-dropdown-menu');

    if (dropdownBtn && dropdownMenu) {
        dropdownBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            dropdownMenu.classList.toggle('hidden');
        });

        document.addEventListener('click', () => {
            dropdownMenu.classList.add('hidden');
        });
    }

    const themeButtons = document.querySelectorAll('.theme-option-btn[data-theme]');
    themeButtons.forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();

            const selectedTheme = this.getAttribute('data-theme');
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

            fetch("{{ route('theme.switch') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken || '',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({ theme: selectedTheme })
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    const currentUrl = new URL(window.location.href);
                    currentUrl.searchParams.delete('theme');
                    const targetUrl = currentUrl.toString();
                    if (window.location.href === targetUrl) {
                        window.location.reload();
                    } else {
                        window.location.href = targetUrl;
                    }
                }
            })
            .catch(() => {
                const currentUrl = new URL(window.location.href);
                currentUrl.searchParams.delete('theme');
                const targetUrl = currentUrl.toString();
                if (window.location.href === targetUrl) {
                    window.location.reload();
                } else {
                    window.location.href = targetUrl;
                }
            });
        });
    });
});
</script>

@if($isHome)
<script>
document.addEventListener('DOMContentLoaded', () => {
    const sections = ['home', 'about', 'skills', 'experience', 'contact'];
    const navLinks = document.querySelectorAll('.nav-link[data-section]');

    const setActive = (sectionId) => {
        navLinks.forEach(link => {
            if (link.getAttribute('data-section') === sectionId) {
                link.classList.add('text-emerald-500', 'dark:text-emerald-400', 'font-semibold', 'active-nav');
                link.classList.remove('text-slate-600', 'dark:text-slate-300', 'font-medium');
            } else {
                link.classList.remove('text-emerald-500', 'dark:text-emerald-400', 'font-semibold', 'active-nav');
                link.classList.add('text-slate-600', 'dark:text-slate-300', 'font-medium');
            }
        });
    };

    const handleScroll = () => {
        let current = 'home';
        const scrollPosition = window.scrollY + 200;

        sections.forEach(id => {
            const el = document.getElementById(id);
            if (el) {
                const top = el.offsetTop;
                const height = el.offsetHeight;
                if (scrollPosition >= top && scrollPosition < top + height) {
                    current = id;
                }
            }
        });

        setActive(current);
    };

    window.addEventListener('scroll', handleScroll, { passive: true });
    handleScroll();
});
</script>
@endif
