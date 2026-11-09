<div class="p-6 rounded-3xl nexus-glass-card transition-all duration-300">
    <div class="flex flex-wrap items-center justify-between gap-2 mb-3">
        <span class="text-xs font-mono font-bold text-blue-600 dark:text-cyan-400 bg-blue-500/10 dark:bg-cyan-500/10 border border-blue-500/20 dark:border-cyan-500/20 px-3 py-1 rounded-full backdrop-blur-md">
            {{ $experience->period ?? ($experience->start_date ? \Carbon\Carbon::parse($experience->start_date)->format('M Y') . ' - ' . ($experience->end_date ? \Carbon\Carbon::parse($experience->end_date)->format('M Y') : 'Present') : 'Present') }}
        </span>
        @if($experience->company)
        <span class="text-xs font-semibold text-slate-500 dark:text-slate-400 font-mono">{{ $experience->company }}</span>
        @endif
    </div>

    <h3 class="text-lg font-extrabold text-slate-900 dark:text-white mb-2">
        {{ $experience->title }}
    </h3>

    <p class="text-xs leading-relaxed text-slate-600 dark:text-slate-300">
        {{ $experience->description }}
    </p>

    @if(!empty($experience->technologies))
    <div class="mt-4 flex flex-wrap gap-1.5 pt-3 border-t border-white/60 dark:border-white/10">
        @php
            $techs = is_array($experience->technologies) ? $experience->technologies : array_map('trim', explode(',', $experience->technologies));
        @endphp
        @foreach($techs as $t)
        <span class="rounded-lg bg-white/50 dark:bg-slate-800/50 px-2 py-0.5 text-[11px] font-mono text-slate-600 dark:text-slate-400 border border-white/40 dark:border-white/10">
            {{ $t }}
        </span>
        @endforeach
    </div>
    @endif
</div>

