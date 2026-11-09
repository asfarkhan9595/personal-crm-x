@extends('layouts.theme1')

@section('title', 'All Projects — Theme 1 (Nexus Glass)')

@section('content')
<section class="py-20 sm:py-24 relative min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Navigation Back Link -->
        <div class="mb-8">
            <a href="{{ route('home') }}#projects" class="inline-flex items-center gap-2 text-xs font-bold uppercase tracking-wider text-blue-600 dark:text-cyan-400 hover:underline transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                <span>Back to Home</span>
            </a>
        </div>

        <!-- Page Header -->
        <div class="mb-12">
            <span class="inline-flex items-center gap-2 px-3.5 py-1 rounded-full bg-blue-500/10 dark:bg-cyan-500/10 border border-blue-500/20 dark:border-cyan-500/20 text-blue-600 dark:text-cyan-400 text-xs font-semibold uppercase tracking-wider backdrop-blur-md">
                <span class="w-2 h-2 rounded-full bg-blue-500 dark:bg-cyan-400 animate-pulse"></span>
                <span>Engineering Archives</span>
            </span>
            <h1 class="text-3xl sm:text-5xl font-extrabold text-slate-900 dark:text-white tracking-tight mt-3">
                All Projects &amp; Case Studies
            </h1>
            <p class="mt-2 text-sm text-slate-600 dark:text-slate-400 max-w-2xl">
                Complete catalog of production web systems, Python REST microservices, AI integrations, and open-source software.
            </p>

            <!-- Category Filter Pills -->
            <div class="flex flex-wrap gap-2 mt-6">
                <a href="{{ route('projects.index') }}" class="px-4 py-2 rounded-2xl text-xs font-bold uppercase tracking-wider {{ !request('category') ? 'bg-gradient-to-r from-blue-600 to-indigo-600 text-white shadow-lg shadow-blue-500/25' : 'bg-white/60 dark:bg-slate-800/60 text-slate-700 dark:text-slate-300 border border-white/80 dark:border-white/12 backdrop-blur-md' }}">
                    All
                </a>
                @foreach($categories as $cat)
                <a href="{{ route('projects.index', ['category' => $cat->slug]) }}" class="px-4 py-2 rounded-2xl text-xs font-bold uppercase tracking-wider {{ request('category') === $cat->slug ? 'bg-gradient-to-r from-blue-600 to-indigo-600 text-white shadow-lg shadow-blue-500/25' : 'bg-white/60 dark:bg-slate-800/60 text-slate-700 dark:text-slate-300 border border-white/80 dark:border-white/12 backdrop-blur-md' }}">
                    {{ $cat->name }}
                </a>
                @endforeach
            </div>
        </div>

        <!-- Projects Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($projects as $proj)
                @include('themes.theme1.components.project-card', ['project' => $proj])
            @endforeach
        </div>

        <!-- Pagination Controls -->
        @if(method_exists($projects, 'links'))
            {{ $projects->links('partials.nexus-glass-pagination') }}
        @endif

    </div>
</section>
@endsection

