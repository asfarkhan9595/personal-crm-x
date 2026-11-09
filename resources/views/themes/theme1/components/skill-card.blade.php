<div class="skill-tag inline-flex items-center gap-1.5 px-3.5 py-2 rounded-2xl text-xs font-semibold bg-white/70 dark:bg-slate-800/70 text-slate-800 dark:text-slate-200 border border-white/80 dark:border-white/12 shadow-sm backdrop-blur-xl hover:border-blue-500/50 hover:bg-blue-500/10 hover:text-blue-600 dark:hover:text-cyan-400 transition-all cursor-default">
    <span class="w-2 h-2 rounded-full bg-blue-500 dark:bg-cyan-400"></span>
    <span>{{ $skill->name }}</span>
    @if($skill->proficiency)
    <span class="text-[10px] font-mono text-blue-600 dark:text-cyan-400 font-bold opacity-90">{{ $skill->proficiency }}%</span>
    @endif
</div>

