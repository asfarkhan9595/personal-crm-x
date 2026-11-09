<section id="experience" class="py-20 relative bg-white dark:bg-slate-950">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Section Header -->
        <div class="text-center max-w-3xl mx-auto mb-14">
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-500/10 border border-emerald-500/20 text-emerald-500 text-xs font-bold uppercase tracking-widest">
                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                Career Journey
            </div>
            <h2 class="text-3xl font-extrabold text-slate-900 dark:text-slate-100 mt-3 sm:text-4xl tracking-tight">
                Work Experience
            </h2>
            <p class="mt-2 text-sm text-slate-600 dark:text-slate-400 max-w-xl mx-auto">
                Professional software engineering roles, client projects, and full-stack development experience.
            </p>
        </div>

        <!-- Modern Vertical Timeline -->
        <div class="max-w-4xl mx-auto space-y-8 relative before:absolute before:inset-0 before:left-8 md:before:left-1/2 before:-translate-x-px before:h-full before:w-0.5 before:bg-slate-200 dark:before:bg-slate-800">
            @foreach($experience as $index => $exp)
            <div class="relative flex items-center justify-between md:justify-normal md:odd:flex-row-reverse group">
                <!-- Timeline Marker Icon -->
                <div class="flex items-center justify-center w-10 h-10 rounded-2xl border-2 border-emerald-500 bg-white dark:bg-slate-900 text-emerald-500 shadow-md md:order-1 md:group-odd:-translate-x-1/2 md:group-even:translate-x-1/2 shrink-0 group-hover:bg-emerald-500 group-hover:text-white transition-all">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                </div>

                <!-- Timeline Content Card -->
                <div class="w-[calc(100%-3.5rem)] md:w-[calc(50%-2.5rem)] p-6 rounded-2xl bg-slate-50/70 dark:bg-slate-900/60 border border-slate-200/80 dark:border-slate-800/80 shadow-sm hover:shadow-xl hover:border-emerald-500/40 transition-all duration-300">
                    <div class="flex items-center justify-between gap-2 mb-2">
                        <span class="px-2.5 py-0.5 rounded-full bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 font-bold text-xs">
                            {{ $exp->company }}
                        </span>
                        <span class="text-[11px] font-mono font-medium text-slate-500 dark:text-slate-400">
                            {{ $exp->start_date ? \Carbon\Carbon::parse($exp->start_date)->format('M Y') : '' }} - {{ $exp->is_current ? 'Present' : ($exp->end_date ? \Carbon\Carbon::parse($exp->end_date)->format('M Y') : '') }}
                        </span>
                    </div>

                    <h3 class="text-lg font-bold text-slate-900 dark:text-slate-100">
                        {{ $exp->title }}
                    </h3>

                    @if($exp->location)
                    <p class="text-xs font-mono text-slate-500 dark:text-slate-400 mb-3 flex items-center gap-1">
                        <svg class="w-3.5 h-3.5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path></svg>
                        <span>{{ $exp->location }}</span>
                    </p>
                    @endif

                    <p class="text-sm text-slate-600 dark:text-slate-300 leading-relaxed">
                        {{ $exp->description }}
                    </p>

                    @if($exp->responsibilities && is_array($exp->responsibilities))
                    <ul class="mt-3 space-y-1 text-xs text-slate-600 dark:text-slate-400">
                        @foreach($exp->responsibilities as $resp)
                        <li class="flex items-start gap-2">
                            <span class="text-emerald-500 font-bold">✓</span>
                            <span>{{ $resp }}</span>
                        </li>
                        @endforeach
                    </ul>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>


