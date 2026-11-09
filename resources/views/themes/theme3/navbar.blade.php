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

    $activeClass = 'px-4 py-1.5 rounded-full text-xs font-bold text-blue-600 dark:text-cyan-400 bg-blue-500/10 dark:bg-cyan-500/10 border border-blue-500/20 dark:border-cyan-500/20 backdrop-blur-md shadow-sm nav-link-item active-nav';
    $inactiveClass = 'px-4 py-1.5 rounded-full text-xs font-semibold text-slate-700 dark:text-slate-300 hover:text-blue-600 dark:hover:text-cyan-400 hover:bg-slate-100 dark:hover:bg-slate-800 transition-all nav-link-item';

    $mobileActiveClass = 'block px-3 py-2 rounded-xl text-blue-600 dark:text-cyan-400 bg-blue-500/10 font-bold mobile-nav-link-item active-nav';
    $mobileInactiveClass = 'block px-3 py-2 rounded-xl text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 mobile-nav-link-item';
@endphp

{{-- Theme 3 Frosted Glass Full-Width Navigation Bar --}}
<nav class="sticky top-0 left-0 right-0 z-50 bg-white/75 dark:bg-slate-900/75 backdrop-blur-2xl border-b border-white/80 dark:border-white/10 shadow-sm transition-all duration-300">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16 gap-4">

            {{-- Frosted Glass Brand Badge --}}
            <a href="{{ route('home') }}" class="flex items-center gap-2.5 shrink-0 group">
                <span class="flex h-9 w-9 items-center justify-center rounded-2xl bg-blue-600 text-white font-extrabold text-base shadow-lg shadow-blue-500/25 group-hover:scale-105 transition-transform">
                    {{ strtoupper(substr($profile->name ?? 'A', 0, 1)) }}
                </span>
                <span class="font-extrabold text-base tracking-tight text-slate-900 dark:text-white group-hover:text-blue-600 dark:group-hover:text-cyan-400 transition-colors">
                    {{ $profile->name ?? 'Portfolio' }}
                </span>
            </a>

            {{-- Glass Nav Link Pills --}}
            <div class="hidden md:flex items-center gap-1.5 flex-1 justify-center">
                <a href="{{ route('home') }}#home" data-section="home" class="{{ $isHome ? $activeClass : $inactiveClass }}">Home</a>
                <a href="{{ route('home') }}#about" data-section="about" class="{{ $inactiveClass }}">About</a>
                <a href="{{ route('home') }}#skills" data-section="skills" class="{{ $inactiveClass }}">Skills</a>
                <a href="{{ route('projects.index') }}" class="{{ $isProjects ? $activeClass : $inactiveClass }}">Projects</a>
                <a href="{{ route('home') }}#experience" data-section="experience" class="{{ $inactiveClass }}">Experience</a>
                <a href="{{ route('home') }}#services" data-section="services" class="{{ $inactiveClass }}">Services</a>
                <a href="{{ route('blog.index') }}" class="{{ $isBlog ? $activeClass : $inactiveClass }}">Blog</a>
                <a href="{{ route('home') }}#contact" data-section="contact" class="{{ $inactiveClass }}">Contact</a>
            </div>

            {{-- Right Controls & Theme Dropdown --}}
            <div class="flex items-center gap-1.5 sm:gap-2.5 shrink-0">
                <div class="relative inline-block">
                    <button type="button" id="theme-dropdown-btn" class="inline-flex items-center gap-1 sm:gap-1.5 text-[9px] sm:text-[10px] font-bold uppercase tracking-wider px-2.5 sm:px-3.5 py-1 sm:py-1.5 rounded-full border border-blue-500/30 dark:border-cyan-500/30 text-blue-600 dark:text-cyan-400 bg-blue-500/10 dark:bg-cyan-500/10 backdrop-blur-xl hover:bg-blue-500/20 transition-all shadow-sm">
                        <span class="w-2 h-2 rounded-full bg-blue-500 dark:bg-cyan-400 animate-ping shrink-0"></span>
                        <span id="current-theme-label" class="hidden sm:inline">{{ $activeThemeLabel }}</span>
                        <span class="sm:hidden font-mono font-bold">{{ strtoupper($activeThemeLabel) }}</span>
                        <svg class="w-3 h-3 ml-0.5 opacity-80 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"></path></svg>
                    </button>

                    <div id="theme-dropdown-menu" class="hidden absolute right-0 mt-2 w-44 rounded-2xl bg-white/90 dark:bg-slate-900/90 backdrop-blur-2xl border border-white/80 dark:border-white/10 shadow-2xl py-2 z-50 text-xs font-semibold">
                        <div class="px-3 py-1 text-[9px] font-bold uppercase tracking-wider text-blue-600 dark:text-cyan-400 border-b border-slate-100 dark:border-slate-800 mb-1">
                            Switch UI Theme
                        </div>
                        <button type="button" data-theme="theme1" class="theme-option-btn w-full px-3.5 py-2 flex items-center justify-between {{ $activeThemeKey === 'theme1' ? 'text-blue-600 dark:text-cyan-400 bg-blue-500/10 font-bold' : 'hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-700 dark:text-slate-200' }} transition-colors cursor-pointer">
                            <span>💎 Nexus Glass</span>
                            @if($activeThemeKey === 'theme1') <span class="text-blue-600 dark:text-cyan-400">✓</span> @endif
                        </button>
                        <button type="button" data-theme="theme3" class="theme-option-btn w-full px-3.5 py-2 flex items-center justify-between {{ $activeThemeKey === 'theme3' ? 'text-blue-600 dark:text-cyan-400 bg-blue-500/10 font-bold' : 'hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-700 dark:text-slate-200' }} transition-colors cursor-pointer">
                            <span>💧 Glass UI</span>
                            @if($activeThemeKey === 'theme3') <span class="text-blue-600 dark:text-cyan-400">✓</span> @endif
                        </button>
                    </div>
                </div>

                <button type="button" class="theme-toggle-btn p-2 rounded-full text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 transition-all cursor-pointer" title="Toggle dark mode">
                    <svg class="w-4 h-4 hidden dark:block text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                    <svg class="w-4 h-4 block dark:hidden text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path></svg>
                </button>

                {{-- Mobile Drawer Trigger --}}
                <button type="button" id="mobile-menu-btn" class="md:hidden p-2 rounded-full text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                </button>
            </div>
        </div>
    </div>

    {{-- Mobile Menu Drawer --}}
    <div id="mobile-menu-drawer" class="hidden md:hidden border-t border-slate-200 dark:border-white/10 bg-white/95 dark:bg-slate-900/95 backdrop-blur-2xl px-4 pt-2 pb-4 space-y-1 text-xs font-semibold">
        <a href="{{ route('home') }}#home" data-section="home" class="{{ $isHome ? $mobileActiveClass : $mobileInactiveClass }}">Home</a>
        <a href="{{ route('home') }}#about" data-section="about" class="{{ $mobileInactiveClass }}">About</a>
        <a href="{{ route('home') }}#skills" data-section="skills" class="{{ $mobileInactiveClass }}">Skills</a>
        <a href="{{ route('projects.index') }}" class="{{ $isProjects ? $mobileActiveClass : $mobileInactiveClass }}">Projects</a>
        <a href="{{ route('home') }}#experience" data-section="experience" class="{{ $mobileInactiveClass }}">Experience</a>
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

    const activeDesktopClass = 'px-4 py-1.5 rounded-full text-xs font-bold text-blue-600 dark:text-cyan-400 bg-blue-500/10 dark:bg-cyan-500/10 border border-blue-500/20 dark:border-cyan-500/20 backdrop-blur-md shadow-sm nav-link-item active-nav';
    const inactiveDesktopClass = 'px-4 py-1.5 rounded-full text-xs font-semibold text-slate-700 dark:text-slate-300 hover:text-blue-600 dark:hover:text-cyan-400 hover:bg-slate-100 dark:hover:bg-slate-800 transition-all nav-link-item';

    const activeMobileClass = 'block px-3 py-2 rounded-xl text-blue-600 dark:text-cyan-400 bg-blue-500/10 font-bold mobile-nav-link-item active-nav';
    const inactiveMobileClass = 'block px-3 py-2 rounded-xl text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 mobile-nav-link-item';

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
