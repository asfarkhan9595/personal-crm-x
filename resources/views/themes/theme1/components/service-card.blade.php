<div class="h-full flex flex-col p-6 sm:p-8 rounded-3xl nexus-glass-card group transition-all duration-300">
    <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-gradient-to-tr from-blue-600 to-indigo-600 text-white shadow-lg shadow-blue-500/25 mb-6 group-hover:scale-110 transition-transform">
        @if(!empty($icon) && str_contains($icon, '<svg'))
            {!! $icon !!}
        @else
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path></svg>
        @endif
    </div>

    <h3 class="text-xl font-bold text-slate-900 dark:text-white group-hover:text-blue-600 dark:group-hover:text-cyan-400 transition-colors mb-3">
        {{ $title }}
    </h3>

    <p class="text-xs leading-relaxed text-slate-600 dark:text-slate-300 mb-4">
        {{ $description }}
    </p>

    @if(isset($skills) && count($skills))
    <div class="mt-auto flex flex-wrap gap-1.5 pt-4 border-t border-black/5 dark:border-white/10">
        @foreach($skills->take(4) as $sk)
        <span class="rounded-lg bg-blue-500/10 text-blue-600 dark:text-cyan-400 border border-blue-500/20 px-2 py-0.5 text-[10px] font-mono font-semibold">
            {{ $sk->name }}
        </span>
        @endforeach
    </div>
    @endif
</div>

