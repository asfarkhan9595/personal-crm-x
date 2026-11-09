<!DOCTYPE html>
<html lang="en" class="h-full bg-gray-50 dark:bg-gray-900">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Portfolio</title>
    <script>
        if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
        }
    </script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/collapse@3.x.x/dist/cdn.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet" />
    <style>
        body, input, textarea, select, button {
            font-family: 'Inter', system-ui, -apple-system, sans-serif !important;
        }
    </style>
</head>
<body class="font-sans antialiased bg-gray-50 text-gray-900 dark:bg-gray-900 dark:text-gray-100 flex h-screen overflow-hidden">

    <!-- Mobile sidebar backdrop -->
    <div x-data="{ sidebarOpen: false }" class="relative flex h-full w-full">
        <div 
            id="sidebar-backdrop"
            class="fixed inset-0 z-20 bg-gray-900/50 hidden lg:hidden"
            onclick="toggleSidebar()"
        ></div>

        <!-- Sidebar -->
        <aside 
            id="sidebar"
            class="fixed inset-y-0 left-0 z-30 w-64 transform bg-white dark:bg-gray-800 border-r border-gray-200 dark:border-gray-700 transition-transform duration-300 ease-in-out lg:translate-x-0 lg:static lg:inset-0 -translate-x-full flex flex-col"
        >
            <div class="flex items-center justify-center h-16 border-b border-gray-200 dark:border-gray-700">
                <span class="text-xl font-bold uppercase tracking-wider text-gray-800 dark:text-gray-100">Portfolio Admin</span>
            </div>

            <nav class="flex-1 px-4 py-6 space-y-2 overflow-y-auto">
                @php
                    $active = 'bg-indigo-50 text-indigo-600 dark:bg-indigo-500/10 dark:text-indigo-400 font-semibold';
                    $inactive = 'text-gray-600 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700';
                    $unreadMessagesCount = \App\Models\ContactMessage::where('status', 'new')->orWhere('is_read', false)->count();

                    // Active group determination: only the current active page's group stays open by default
                    $isContactActive = request()->is('admin/contact*') || request()->routeIs(['admin.contact-messages.*', 'admin.contact-settings.*']);
                    $isCrmActive = request()->is('admin/crm*');
                    $isSystemActive = request()->is('admin/settings*') || request()->routeIs('admin.settings.*');
                    $isPortfolioActive = !$isContactActive && !$isCrmActive && !$isSystemActive;
                @endphp

                <!-- 1. PORTFOLIO GROUP -->
                <div x-data="{ open: {{ $isPortfolioActive ? 'true' : 'false' }} }" class="space-y-1">
                    <button 
                        type="button" 
                        @click="open = !open" 
                        class="w-full flex items-center justify-between px-3 py-2 text-[10px] font-bold uppercase tracking-wider text-gray-400 hover:text-gray-700 dark:text-gray-500 dark:hover:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700/50 rounded-lg transition-colors group select-none"
                    >
                        <span class="flex items-center gap-2">
                            @if($isPortfolioActive)
                                <span class="w-1.5 h-1.5 rounded-full bg-indigo-600 dark:bg-indigo-400"></span>
                            @endif
                            <span>Portfolio</span>
                        </span>
                        <svg 
                            class="w-3.5 h-3.5 text-gray-400 group-hover:text-gray-600 dark:text-gray-500 dark:group-hover:text-gray-300 transition-transform duration-200" 
                            :class="open ? 'rotate-180' : ''" 
                            fill="none" 
                            viewBox="0 0 24 24" 
                            stroke="currentColor"
                        >
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>

                    <div x-show="open" x-collapse.duration.200ms class="space-y-1 overflow-hidden">
                        <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-4 py-3 text-sm rounded-lg transition-colors {{ request()->routeIs('admin.dashboard') ? $active : $inactive }}">
                            <i data-lucide="layout-dashboard" class="w-5 h-5"></i>
                            Dashboard
                        </a>

                        <a href="{{ route('admin.profile.show') }}" class="flex items-center gap-3 px-4 py-3 text-sm rounded-lg transition-colors {{ request()->routeIs('admin.profile.*') ? $active : $inactive }}">
                            <i data-lucide="user" class="w-5 h-5"></i>
                            Profile
                        </a>

                        <a href="{{ route('admin.projects.index') }}" class="flex items-center gap-3 px-4 py-3 text-sm rounded-lg transition-colors {{ request()->routeIs('admin.projects.*') ? $active : $inactive }}">
                            <i data-lucide="briefcase" class="w-5 h-5"></i>
                            Projects
                        </a>

                        @if(Route::has('admin.project-categories.index'))
                        <a href="{{ route('admin.project-categories.index') }}" class="flex items-center gap-3 px-4 py-3 text-sm rounded-lg transition-colors {{ request()->routeIs('admin.project-categories.*') ? $active : $inactive }}">
                            <i data-lucide="tags" class="w-5 h-5"></i>
                            Project Categories
                        </a>
                        @endif

                        <a href="{{ route('admin.technologies.index') }}" class="flex items-center gap-3 px-4 py-3 text-sm rounded-lg transition-colors {{ request()->routeIs('admin.technologies.*') ? $active : $inactive }}">
                            <i data-lucide="cpu" class="w-5 h-5"></i>
                            Technologies
                        </a>

                        <a href="{{ route('admin.skills.index') }}" class="flex items-center gap-3 px-4 py-3 text-sm rounded-lg transition-colors {{ request()->routeIs('admin.skills.*') ? $active : $inactive }}">
                            <i data-lucide="code" class="w-5 h-5"></i>
                            Skills
                        </a>

                        <a href="{{ route('admin.skill-categories.index') }}" class="flex items-center gap-3 px-4 py-3 text-sm rounded-lg transition-colors {{ request()->routeIs('admin.skill-categories.*') ? $active : $inactive }}">
                            <i data-lucide="folder" class="w-5 h-5"></i>
                            Skill Categories
                        </a>

                        <a href="{{ route('admin.experience.index') }}" class="flex items-center gap-3 px-4 py-3 text-sm rounded-lg transition-colors {{ request()->routeIs('admin.experience.*') ? $active : $inactive }}">
                            <i data-lucide="award" class="w-5 h-5"></i>
                            Experience
                        </a>

                        <a href="{{ route('admin.resume.index') }}" class="flex items-center gap-3 px-4 py-3 text-sm rounded-lg transition-colors {{ request()->routeIs('admin.resume.*') ? $active : $inactive }}">
                            <i data-lucide="file-text" class="w-5 h-5"></i>
                            Resume
                        </a>

                        <a href="{{ route('admin.social-links.index') }}" class="flex items-center gap-3 px-4 py-3 text-sm rounded-lg transition-colors {{ request()->routeIs('admin.social-links.*') ? $active : $inactive }}">
                            <i data-lucide="share-2" class="w-5 h-5"></i>
                            Social Links
                        </a>

                        <a href="{{ route('admin.services.index') }}" class="flex items-center gap-3 px-4 py-3 text-sm rounded-lg transition-colors {{ request()->routeIs('admin.services.*') ? $active : $inactive }}">
                            <i data-lucide="layers" class="w-5 h-5"></i>
                            Services
                        </a>

                        <a href="{{ route('admin.posts.index') }}" class="flex items-center gap-3 px-4 py-3 text-sm rounded-lg transition-colors {{ request()->routeIs('admin.posts.*') ? $active : $inactive }}">
                            <i data-lucide="newspaper" class="w-5 h-5"></i>
                            Blog Posts
                        </a>
                    </div>
                </div>

                <!-- 2. CONTACT GROUP -->
                <div x-data="{ open: {{ $isContactActive ? 'true' : 'false' }} }" class="pt-2 space-y-1">
                    <button 
                        type="button" 
                        @click="open = !open" 
                        class="w-full flex items-center justify-between px-3 py-2 text-[10px] font-bold uppercase tracking-wider text-gray-400 hover:text-gray-700 dark:text-gray-500 dark:hover:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700/50 rounded-lg transition-colors group select-none"
                    >
                        <span class="flex items-center gap-2">
                            @if($isContactActive)
                                <span class="w-1.5 h-1.5 rounded-full bg-indigo-600 dark:bg-indigo-400"></span>
                            @endif
                            <span>Contact</span>
                            @if($unreadMessagesCount > 0)
                                <span x-show="!open" class="px-1.5 py-0.5 text-[9px] font-bold rounded-full bg-emerald-500 text-white">
                                    {{ $unreadMessagesCount }}
                                </span>
                            @endif
                        </span>
                        <svg 
                            class="w-3.5 h-3.5 text-gray-400 group-hover:text-gray-600 dark:text-gray-500 dark:group-hover:text-gray-300 transition-transform duration-200" 
                            :class="open ? 'rotate-180' : ''" 
                            fill="none" 
                            viewBox="0 0 24 24" 
                            stroke="currentColor"
                        >
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>

                    <div x-show="open" x-collapse.duration.200ms class="space-y-1 overflow-hidden">
                        <a href="{{ route('admin.contact-messages.index') }}" class="flex items-center justify-between px-4 py-3 text-sm rounded-lg transition-colors {{ request()->routeIs('admin.contact-messages.*') ? $active : $inactive }}">
                            <div class="flex items-center gap-3">
                                <i data-lucide="mail" class="w-5 h-5"></i>
                                <span>Messages</span>
                            </div>
                            @if($unreadMessagesCount > 0)
                                <span class="px-2 py-0.5 text-xs font-bold rounded-full bg-emerald-500 text-white animate-pulse">
                                    {{ $unreadMessagesCount }}
                                </span>
                            @endif
                        </a>

                        <a href="{{ route('admin.contact-settings.index') }}" class="flex items-center gap-3 px-4 py-3 text-sm rounded-lg transition-colors {{ request()->routeIs('admin.contact-settings.*') ? $active : $inactive }}">
                            <i data-lucide="sliders" class="w-5 h-5"></i>
                            Contact Settings
                        </a>
                    </div>
                </div>

                <!-- 3. CRM GROUP -->
                @if(config('crm.enabled', false))
                    @if(request()->is('admin/crm*'))
                        <!-- Expanded Collapsible CRM Group on CRM pages -->
                        <div x-data="{ open: true }" class="pt-2 space-y-1">
                            <button 
                                type="button" 
                                @click="open = !open" 
                                class="w-full flex items-center justify-between px-3 py-2 text-[10px] font-bold uppercase tracking-wider text-emerald-600 hover:text-emerald-700 dark:text-emerald-400 dark:hover:text-emerald-300 hover:bg-emerald-50/50 dark:hover:bg-emerald-500/10 rounded-lg transition-colors group select-none"
                            >
                                <span class="flex items-center gap-2">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                                    <span>CRM</span>
                                </span>
                                <svg 
                                    class="w-3.5 h-3.5 text-emerald-500 transition-transform duration-200" 
                                    :class="open ? 'rotate-180' : ''" 
                                    fill="none" 
                                    viewBox="0 0 24 24" 
                                    stroke="currentColor"
                                >
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>

                            <div x-show="open" x-collapse.duration.200ms class="space-y-1 overflow-hidden">
                                <a href="{{ route('admin.crm.dashboard') }}" class="flex items-center gap-3 px-4 py-3 text-sm rounded-lg transition-colors {{ request()->routeIs('admin.crm.dashboard*') ? $active : $inactive }}">
                                    <i data-lucide="pie-chart" class="w-5 h-5"></i>
                                    Earnings Dashboard
                                </a>

                                <a href="{{ route('admin.crm.leads.index') }}" class="flex items-center gap-3 px-4 py-3 text-sm rounded-lg transition-colors {{ request()->routeIs('admin.crm.leads.*') ? $active : $inactive }}">
                                    <i data-lucide="kanban" class="w-5 h-5"></i>
                                    Leads (Kanban / Table)
                                </a>

                                <a href="{{ route('admin.crm.payments.index') }}" class="flex items-center gap-3 px-4 py-3 text-sm rounded-lg transition-colors {{ request()->routeIs('admin.crm.payments.*') ? $active : $inactive }}">
                                    <i data-lucide="receipt" class="w-5 h-5"></i>
                                    Payments
                                </a>

                                <a href="{{ route('admin.crm.settings.index') }}" class="flex items-center gap-3 px-4 py-3 text-sm rounded-lg transition-colors {{ request()->routeIs('admin.crm.settings.*') ? $active : $inactive }}">
                                    <i data-lucide="sliders-horizontal" class="w-5 h-5"></i>
                                    CRM Settings
                                </a>
                            </div>
                        </div>
                    @else
                        <!-- Collapsed single CRM link on non-CRM pages (preserves existing behavior) -->
                        <div class="pt-2">
                            <div class="px-3 py-1.5 text-[10px] font-bold uppercase tracking-wider text-gray-400 dark:text-gray-500">
                                CRM
                            </div>
                            <a href="{{ route('admin.crm.dashboard') }}" class="flex items-center justify-between px-4 py-3 text-sm rounded-lg transition-colors {{ $inactive }}" title="Open CRM Module">
                                <div class="flex items-center gap-3">
                                    <i data-lucide="bar-chart-3" class="w-5 h-5 text-emerald-600 dark:text-emerald-400"></i>
                                    <span>CRM</span>
                                </div>
                                <span class="px-1.5 py-0.5 text-[10px] font-bold rounded uppercase tracking-wider bg-emerald-50 text-emerald-600 dark:bg-emerald-500/10 dark:text-emerald-400">
                                    Open
                                </span>
                            </a>
                        </div>
                    @endif
                @endif

                <!-- 4. SYSTEM GROUP -->
                <div x-data="{ open: {{ $isSystemActive ? 'true' : 'false' }} }" class="pt-2 space-y-1">
                    <button 
                        type="button" 
                        @click="open = !open" 
                        class="w-full flex items-center justify-between px-3 py-2 text-[10px] font-bold uppercase tracking-wider text-gray-400 hover:text-gray-700 dark:text-gray-500 dark:hover:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700/50 rounded-lg transition-colors group select-none"
                    >
                        <span class="flex items-center gap-2">
                            @if($isSystemActive)
                                <span class="w-1.5 h-1.5 rounded-full bg-indigo-600 dark:bg-indigo-400"></span>
                            @endif
                            <span>System</span>
                        </span>
                        <svg 
                            class="w-3.5 h-3.5 text-gray-400 group-hover:text-gray-600 dark:text-gray-500 dark:group-hover:text-gray-300 transition-transform duration-200" 
                            :class="open ? 'rotate-180' : ''" 
                            fill="none" 
                            viewBox="0 0 24 24" 
                            stroke="currentColor"
                        >
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>

                    <div x-show="open" x-collapse.duration.200ms class="space-y-1 overflow-hidden">
                        <a href="{{ route('admin.settings.index') }}" class="flex items-center gap-3 px-4 py-3 text-sm rounded-lg transition-colors {{ request()->routeIs('admin.settings.*') ? $active : $inactive }}">
                            <i data-lucide="settings" class="w-5 h-5"></i>
                            Main Settings
                        </a>

                        <form action="{{ route('admin.logout') }}" method="POST" class="pt-2">
                            @csrf
                            <button type="submit" class="w-full flex items-center gap-3 px-4 py-3 text-sm rounded-lg text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-500/10 font-semibold transition-colors">
                                <i data-lucide="log-out" class="w-5 h-5"></i>
                                Logout
                            </button>
                        </form>
                    </div>
                </div>
            </nav>
        </aside>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col min-w-0 overflow-hidden">
            <!-- Topbar -->
            <header class="flex items-center justify-between h-16 px-6 border-b border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800">
                <div class="flex items-center">
                    <button 
                        class="p-2 mr-4 text-gray-600 rounded-lg lg:hidden hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700 focus:outline-none"
                        onclick="toggleSidebar()"
                    >
                        <i data-lucide="menu" class="w-6 h-6"></i>
                    </button>
                    <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-100">
                        @yield('title', 'Admin Dashboard')
                    </h2>
                </div>
                
                <div class="flex items-center gap-4">
                    <!-- Notification Bell Button -->
                    <a href="{{ route('admin.contact-messages.index', ['status' => 'unread']) }}" class="relative p-2 text-gray-600 rounded-full hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700 transition-colors" title="Contact Notifications">
                        <i data-lucide="bell" class="w-5 h-5"></i>
                        @if($unreadMessagesCount > 0)
                            <span class="absolute top-1 right-1 flex h-4 w-4 items-center justify-center rounded-full bg-emerald-500 text-[10px] font-bold text-white animate-pulse">
                                {{ $unreadMessagesCount > 99 ? '99+' : $unreadMessagesCount }}
                            </span>
                        @endif
                    </a>

                    <button class="p-2 text-gray-600 rounded-full hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700" onclick="toggleTheme()" title="Toggle Light/Dark Theme">
                        <i data-lucide="sun" class="w-5 h-5 hidden dark:block"></i>
                        <i data-lucide="moon" class="w-5 h-5 block dark:hidden"></i>
                    </button>
                    
                    <div class="flex items-center gap-3 pl-2 border-l border-gray-200 dark:border-gray-700">
                        <img class="w-8 h-8 rounded-full bg-gray-300 dark:bg-gray-600" src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name ?? 'Admin') }}&background=10b981&color=fff" alt="Admin avatar">
                        <span class="hidden sm:inline text-xs font-semibold text-gray-700 dark:text-gray-300">{{ Auth::user()->name ?? 'Admin' }}</span>
                        
                        <form action="{{ route('admin.logout') }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="p-2 text-gray-500 hover:text-red-600 dark:text-gray-400 dark:hover:text-red-400 transition-colors" title="Logout">
                                <i data-lucide="log-out" class="w-4 h-4"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </header>

            <!-- Main Page Content -->
            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-50 dark:bg-gray-900 p-6">
                @yield('content')
                
                <!-- Mobile Footer / Spacer -->
                <footer class="block md:hidden mt-12 pb-16 text-center text-xs text-gray-400 dark:text-gray-500">
                    <p>&copy; {{ date('Y') }} Portfolio Admin. All rights reserved.</p>
                </footer>
            </main>
        </div>
    </div>

    <!-- Global Delete Confirmation Modal -->
    <div id="globalDeleteModal" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4 text-left font-normal">
        <div onclick="closeGlobalDeleteModal()" class="fixed inset-0 bg-black/60 backdrop-blur-sm"></div>
        <div class="relative z-10 w-full max-w-md bg-white dark:bg-gray-800 rounded-2xl shadow-2xl border border-gray-200 dark:border-gray-700 p-6">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-10 h-10 rounded-full bg-red-100 dark:bg-red-500/20 flex items-center justify-center text-red-600 dark:text-red-400 flex-shrink-0">
                    <i data-lucide="trash-2" class="w-5 h-5"></i>
                </div>
                <div>
                    <h3 id="globalDeleteModalTitle" class="text-lg font-bold text-gray-900 dark:text-white">Delete Confirmation</h3>
                    <p id="globalDeleteModalMessage" class="text-xs text-gray-500 dark:text-gray-400">Are you sure you want to delete this record?</p>
                </div>
            </div>

            <div id="globalDeleteModalItemDetail" class="hidden bg-gray-50 dark:bg-gray-700/50 p-3 rounded-lg border border-gray-200 dark:border-gray-700 mb-6">
                <p id="globalDeleteModalItemText" class="font-bold text-sm text-gray-900 dark:text-white truncate"></p>
            </div>

            <div class="flex justify-end gap-3">
                <button onclick="closeGlobalDeleteModal()" type="button" class="px-4 py-2 text-sm font-medium text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors">
                    Cancel
                </button>
                <button id="globalDeleteConfirmBtn" type="button" class="px-5 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg shadow-md shadow-red-500/20 transition-colors">
                    Delete Permanently
                </button>
            </div>
        </div>
    </div>

    <script>
        // Initialize Lucide Icons
        lucide.createIcons();

        // Sidebar Toggle Logic
        const sidebar = document.getElementById('sidebar');
        const backdrop = document.getElementById('sidebar-backdrop');
        let isSidebarOpen = false;

        function toggleSidebar() {
            isSidebarOpen = !isSidebarOpen;
            if (isSidebarOpen) {
                sidebar.classList.remove('-translate-x-full');
                backdrop.classList.remove('hidden');
            } else {
                sidebar.classList.add('-translate-x-full');
                backdrop.classList.add('hidden');
            }
        }

        // Theme Toggle Logic
        function toggleTheme() {
            const html = document.documentElement;
            if (html.classList.contains('dark')) {
                html.classList.remove('dark');
                localStorage.setItem('theme', 'light');
            } else {
                html.classList.add('dark');
                localStorage.setItem('theme', 'dark');
            }
        }
        
        // Check local storage for theme
        if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }

        // Global Delete Modal Interceptor
        let pendingDeleteForm = null;
        let pendingDeleteCallback = null;

        function closeGlobalDeleteModal() {
            const modal = document.getElementById('globalDeleteModal');
            if (modal) modal.classList.add('hidden');
            pendingDeleteForm = null;
            pendingDeleteCallback = null;
        }

        function showGlobalDeleteModal({ message, itemText, onConfirm }) {
            const modal = document.getElementById('globalDeleteModal');
            const msgEl = document.getElementById('globalDeleteModalMessage');
            const detailBox = document.getElementById('globalDeleteModalItemDetail');
            const detailText = document.getElementById('globalDeleteModalItemText');

            if (msgEl) msgEl.textContent = message || 'Are you sure you want to delete this record?';
            
            if (itemText && itemText.trim()) {
                if (detailText) detailText.textContent = itemText.trim();
                if (detailBox) detailBox.classList.remove('hidden');
            } else {
                if (detailBox) detailBox.classList.add('hidden');
            }

            pendingDeleteCallback = onConfirm;
            if (modal) modal.classList.remove('hidden');
        }

        // Intercept form submissions with confirm(...)
        document.addEventListener('submit', function(e) {
            const form = e.target;
            if (form.dataset.bypassConfirm === 'true') return;

            const onsubmitAttr = form.getAttribute('onsubmit') || '';
            if (onsubmitAttr.includes('confirm(')) {
                e.preventDefault();
                e.stopPropagation();

                let msg = 'Are you sure you want to delete this record?';
                const match = onsubmitAttr.match(/confirm\(['"](.*?)['"]\)/);
                if (match && match[1]) {
                    msg = match[1];
                }

                let itemText = '';
                const row = form.closest('tr');
                if (row) {
                    const titleEl = row.querySelector('.font-semibold, h4, td');
                    if (titleEl) itemText = titleEl.textContent;
                }

                pendingDeleteForm = form;
                showGlobalDeleteModal({
                    message: msg,
                    itemText: itemText,
                    onConfirm: function() {
                        form.dataset.bypassConfirm = 'true';
                        HTMLFormElement.prototype.submit.call(form);
                    }
                });
            }
        }, true);

        // Intercept click events with confirm(...)
        document.addEventListener('click', function(e) {
            const target = e.target.closest('[onclick*="confirm("]');
            if (target && !target.dataset.bypassConfirm) {
                const onclickAttr = target.getAttribute('onclick') || '';
                if (onclickAttr.includes('confirm(')) {
                    e.preventDefault();
                    e.stopPropagation();

                    let msg = 'Are you sure you want to delete this?';
                    const match = onclickAttr.match(/confirm\(['"](.*?)['"]\)/);
                    if (match && match[1]) {
                        msg = match[1];
                    }

                    showGlobalDeleteModal({
                        message: msg,
                        itemText: '',
                        onConfirm: function() {
                            target.dataset.bypassConfirm = 'true';
                            const submitMatch = onclickAttr.match(/document\.getElementById\(['"](.*?)['"]\)\.submit\(\)/);
                            if (submitMatch && submitMatch[1]) {
                                const targetForm = document.getElementById(submitMatch[1]);
                                if (targetForm) {
                                    targetForm.dataset.bypassConfirm = 'true';
                                    HTMLFormElement.prototype.submit.call(targetForm);
                                    return;
                                }
                            }
                            target.click();
                        }
                    });
                }
            }
        }, true);

        // Confirm button click binding
        document.addEventListener('DOMContentLoaded', function() {
            const confirmBtn = document.getElementById('globalDeleteConfirmBtn');
            if (confirmBtn) {
                confirmBtn.addEventListener('click', function() {
                    const cb = pendingDeleteCallback;
                    const f = pendingDeleteForm;
                    closeGlobalDeleteModal();
                    if (cb) {
                        cb();
                    } else if (f) {
                        f.dataset.bypassConfirm = 'true';
                        HTMLFormElement.prototype.submit.call(f);
                    }
                });
            }
        });
    </script>
</body>
</html>