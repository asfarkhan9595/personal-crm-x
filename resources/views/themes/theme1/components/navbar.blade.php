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

    $activeClass = 'px-3.5 py-1 rounded-full text-xs font-extrabold text-indigo-600 dark:text-cyan-400 bg-indigo-500/10 dark:bg-cyan-500/10 border border-indigo-500/20 dark:border-cyan-500/20 nav-link-item active-nav shadow-xs';
    $inactiveClass = 'px-3.5 py-1 rounded-full text-xs font-semibold text-slate-700 dark:text-slate-200 hover:text-indigo-600 dark:hover:text-cyan-400 hover:bg-indigo-500/5 dark:hover:bg-cyan-500/10 transition-all nav-link-item';

    $mobileActiveClass = 'block px-3.5 py-2 rounded-xl text-indigo-600 dark:text-cyan-400 bg-indigo-500/10 font-extrabold mobile-nav-link-item active-nav';
    $mobileInactiveClass = 'block px-3.5 py-2 rounded-xl text-slate-700 dark:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800 mobile-nav-link-item';
@endphp

{{-- Theme 1 Nexus Cybernetic Glass Floating Capsule Navbar --}}
<nav class="fixed top-3 sm:top-4 left-1/2 -translate-x-1/2 z-50 w-[calc(100%-1.25rem)] sm:w-[calc(100%-2rem)] max-w-5xl rounded-full bg-white/85 dark:bg-slate-950/90 backdrop-blur-2xl border border-slate-200/80 dark:border-white/10 shadow-lg shadow-slate-950/5 transition-all duration-300">
    <div class="px-2.5 sm:px-6 h-12 sm:h-13 flex items-center justify-between gap-1 sm:gap-4">

        {{-- Brand Logo --}}
        <a href="{{ route('home') }}" class="flex items-center gap-1.5 sm:gap-2.5 group shrink-0 max-w-[130px] sm:max-w-none">
            <div class="flex h-7 w-7 sm:h-8 sm:w-8 items-center justify-center rounded-full bg-gradient-to-tr from-indigo-600 via-blue-600 to-cyan-500 text-white font-extrabold text-xs shadow-md shadow-indigo-500/20 group-hover:scale-105 transition-transform shrink-0">
                {{ strtoupper(substr($profile->name ?? 'A', 0, 1)) }}
            </div>
            <span class="font-extrabold text-xs sm:text-sm tracking-tight text-slate-900 dark:text-white group-hover:text-indigo-600 dark:group-hover:text-cyan-400 transition-colors truncate">
                {{ $profile->name ?? 'Asfar Khan' }}
            </span>
        </a>

        {{-- Desktop Nav Links --}}
        <div class="hidden lg:flex items-center gap-1 flex-1 justify-center">
            <a href="{{ route('home') }}#home" data-section="home" class="{{ $isHome ? $activeClass : $inactiveClass }}">Home</a>
            <a href="{{ route('home') }}#about" data-section="about" class="{{ $inactiveClass }}">About</a>
            <a href="{{ route('home') }}#skills" data-section="skills" class="{{ $inactiveClass }}">Skills</a>
            <a href="{{ route('home') }}#experience" data-section="experience" class="{{ $inactiveClass }}">Experience</a>
            <a href="{{ route('projects.index') }}" class="{{ $isProjects ? $activeClass : $inactiveClass }}">Projects</a>
            <a href="{{ route('home') }}#services" data-section="services" class="{{ $inactiveClass }}">Services</a>
            <a href="{{ route('blog.index') }}" class="{{ $isBlog ? $activeClass : $inactiveClass }}">Blog</a>
            <a href="{{ route('home') }}#contact" data-section="contact" class="{{ $inactiveClass }}">Contact</a>
        </div>

        {{-- Right Controls & Resume --}}
        <div class="flex items-center gap-1 sm:gap-2 shrink-0">

            {{-- Theme Switcher Pill --}}
            <div class="relative inline-block">
                <button type="button" id="theme-dropdown-btn" class="inline-flex items-center gap-1 sm:gap-1.5 text-[9px] sm:text-[10px] font-bold uppercase tracking-wider px-2.5 sm:px-3.5 py-1 sm:py-1.5 rounded-full border border-slate-200 dark:border-slate-800 text-slate-700 dark:text-cyan-400 bg-slate-100/80 dark:bg-slate-900/80 backdrop-blur-xl hover:border-indigo-500/40 transition-all cursor-pointer">
                    <span class="w-1.5 h-1.5 rounded-full bg-indigo-600 dark:bg-cyan-400 animate-ping shrink-0"></span>
                    <span id="current-theme-label" class="hidden sm:inline">{{ $activeThemeLabel }}</span>
                    <span class="sm:hidden font-mono font-bold">{{ strtoupper($activeThemeLabel) }}</span>
                    <svg class="w-3 h-3 opacity-70 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"></path></svg>
                </button>

                <div id="theme-dropdown-menu" class="hidden absolute right-0 mt-2 w-44 rounded-2xl bg-white/95 dark:bg-slate-950/95 backdrop-blur-2xl border border-slate-200 dark:border-white/10 shadow-2xl py-1.5 z-50 text-xs font-semibold">
                    <div class="px-3 py-1 text-[9px] font-bold uppercase tracking-wider text-indigo-600 dark:text-cyan-400 border-b border-slate-100 dark:border-slate-800 mb-1">
                        Select Theme
                    </div>
                    <button type="button" data-theme="theme1" class="theme-option-btn w-full px-3.5 py-1.5 flex items-center justify-between {{ $activeThemeKey === 'theme1' ? 'text-indigo-600 dark:text-cyan-400 bg-indigo-500/10 font-bold' : 'hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-700 dark:text-slate-200' }} transition-colors cursor-pointer">
                        <span>💎 Nexus Glass</span>
                        @if($activeThemeKey === 'theme1') <span class="text-indigo-600 dark:text-cyan-400">✓</span> @endif
                    </button>
                    <button type="button" data-theme="theme3" class="theme-option-btn w-full px-3.5 py-1.5 flex items-center justify-between {{ $activeThemeKey === 'theme3' ? 'text-indigo-600 dark:text-cyan-400 bg-indigo-500/10 font-bold' : 'hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-700 dark:text-slate-200' }} transition-colors cursor-pointer">
                        <span>💧 Glass UI</span>
                        @if($activeThemeKey === 'theme3') <span class="text-indigo-600 dark:text-cyan-400">✓</span> @endif
                    </button>
                </div>
            </div>

            {{-- Dark / Light Mode Toggle --}}
            <button type="button" class="theme-toggle-btn p-1.5 rounded-full text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 transition-all cursor-pointer" title="Toggle theme mode">
                <svg class="w-4 h-4 hidden dark:block text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                <svg class="w-4 h-4 block dark:hidden text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path></svg>
            </button>


            {{-- Mobile Drawer Trigger --}}
            <button type="button" id="mobile-menu-btn" class="lg:hidden p-1.5 rounded-full text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800" title="Toggle navigation menu">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
            </button>
        </div>

    </div>

    {{-- Mobile Menu Drawer --}}
    <div id="mobile-menu-drawer" class="hidden lg:hidden border-t border-slate-200 dark:border-white/10 bg-white/95 dark:bg-slate-950/95 backdrop-blur-2xl px-4 pt-2 pb-4 space-y-1 text-xs font-semibold rounded-b-3xl">
        <a href="{{ route('home') }}#home" data-section="home" class="{{ $isHome ? $mobileActiveClass : $mobileInactiveClass }}">Home</a>
        <a href="{{ route('home') }}#about" data-section="about" class="{{ $mobileInactiveClass }}">About</a>
        <a href="{{ route('home') }}#skills" data-section="skills" class="{{ $mobileInactiveClass }}">Skills</a>
        <a href="{{ route('home') }}#experience" data-section="experience" class="{{ $mobileInactiveClass }}">Experience</a>
        <a href="{{ route('projects.index') }}" class="{{ $isProjects ? $mobileActiveClass : $mobileInactiveClass }}">Projects</a>
        <a href="{{ route('home') }}#services" data-section="services" class="{{ $mobileInactiveClass }}">Services</a>
        <a href="{{ route('blog.index') }}" class="{{ $isBlog ? $mobileActiveClass : $mobileInactiveClass }}">Blog</a>
        <a href="{{ route('home') }}#contact" data-section="contact" class="{{ $mobileInactiveClass }}">Contact</a>
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
    const sections = ['home', 'about', 'skills', 'experience', 'services', 'contact'];
    const navLinks = document.querySelectorAll('.nav-link-item[data-section], .mobile-nav-link-item[data-section]');

    const activeDesktopClass = 'px-3.5 py-1 rounded-full text-xs font-extrabold text-indigo-600 dark:text-cyan-400 bg-indigo-500/10 dark:bg-cyan-500/10 border border-indigo-500/20 dark:border-cyan-500/20 nav-link-item active-nav shadow-xs';
    const inactiveDesktopClass = 'px-3.5 py-1 rounded-full text-xs font-semibold text-slate-700 dark:text-slate-200 hover:text-indigo-600 dark:hover:text-cyan-400 hover:bg-indigo-500/5 dark:hover:bg-cyan-500/10 transition-all nav-link-item';

    const activeMobileClass = 'block px-3.5 py-2 rounded-xl text-indigo-600 dark:text-cyan-400 bg-indigo-500/10 font-extrabold mobile-nav-link-item active-nav';
    const inactiveMobileClass = 'block px-3.5 py-2 rounded-xl text-slate-700 dark:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800 mobile-nav-link-item';

    const setActive = (sectionId) => {
        navLinks.forEach(link => {
            const isMobile = link.classList.contains('mobile-nav-link-item');
            if (link.getAttribute('data-section') === sectionId) {
                link.className = isMobile ? activeMobileClass : activeDesktopClass;
            } else {
                link.className = isMobile ? inactiveMobileClass : inactiveDesktopClass;
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

