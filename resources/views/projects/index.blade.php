@extends('layouts.app')

@section('title', 'Projects Showcase - ' . ($profile->name ?? 'Portfolio'))

@section('content')
<section class="py-24 relative min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-3xl mx-auto mb-16">
            <span class="text-xs font-bold uppercase tracking-widest text-emerald-500 bg-emerald-500/10 px-3 py-1 rounded-full">
                All Works
            </span>
            <h1 class="text-4xl font-extrabold text-slate-900 dark:text-slate-100 mt-3 sm:text-5xl">
                Projects Portfolio
            </h1>
            <p class="mt-4 text-lg text-slate-600 dark:text-slate-400">
                Browse my full list of projects, applications, scripts, and open-source contributions.
            </p>
        </div>

        @include('partials.projects')
    </div>
</section>
@endsection

