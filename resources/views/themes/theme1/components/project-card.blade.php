@php
    $img = $project->cover_image ?? ($project->featured_image ?? ($project->images->first()?->image_path));
    $imgUrl = format_image_url($img);
    $videoUrl = format_image_url($project->video_url);
@endphp

<div class="project-card-item group relative flex flex-col overflow-hidden rounded-2xl nexus-glass-card transition-all duration-300 hover:-translate-y-1 cursor-pointer"
     data-category="{{ \Illuminate\Support\Str::slug($project->category->name ?? 'other') }}"
     data-category-name="{{ $project->category->name ?? 'Project' }}"
     data-title="{{ $project->title }}"
     data-short-description="{{ $project->short_description ?? ($project->summary ?? $project->description) }}"
     data-description="{{ $project->description }}"
     data-problem="{{ $project->problem }}"
     data-solution="{{ $project->solution }}"
     data-architecture="{{ $project->architecture }}"
     data-challenges="{{ $project->challenges }}"
     data-learned="{{ $project->what_i_learned }}"
     data-image="{{ $imgUrl }}"
     data-video-url="{{ $videoUrl }}"
     data-demo-url="{{ $project->live_demo_url ?? $project->demo_url }}"
     data-github-url="{{ $project->github_url }}"
     data-project-slug="{{ $project->slug }}"
     data-features='@json($project->features->pluck("feature"))'
     data-technologies='@json($project->technologies->pluck("name"))'
     data-gallery='@json($project->images->map(fn($i) => format_image_url($i->image_path)))'>

    <!-- Glass Image Frame -->
    <div class="relative aspect-video w-full overflow-hidden bg-slate-950/80">
        @if($imgUrl)
        <img src="{{ $imgUrl }}" alt="{{ $project->title }}" class="h-full w-full object-cover transition-all duration-700 ease-out group-hover:scale-105 group-hover:blur-[3px]">
        <div class="absolute inset-0 bg-gradient-to-t from-slate-950/80 via-transparent to-transparent opacity-60 group-hover:opacity-30 transition-opacity"></div>
        @else
        <div class="relative inset-0 w-full h-full p-4 flex flex-col justify-between bg-gradient-to-br from-blue-900/40 via-slate-900 to-slate-950 transition-all duration-500 group-hover:blur-[3px]">
            <div class="flex items-center justify-between">
                <span class="font-mono text-[10px] text-cyan-400 font-semibold">// {{ strtoupper($project->category->name ?? 'NEXUS') }}</span>
                <span class="w-1.5 h-1.5 rounded-full bg-cyan-400 animate-ping"></span>
            </div>
            <div class="text-center py-2">
                <h4 class="text-sm font-bold text-slate-100 line-clamp-1 px-2">{{ $project->title }}</h4>
            </div>
            <div class="flex items-center justify-between text-[10px] font-mono text-slate-400 border-t border-white/10 pt-1.5">
                <span>Active</span>
                <span class="text-cyan-400">View →</span>
            </div>
        </div>
        @endif

        <!-- Floating Glassy Badges (Featured & Category) -->
        <div class="absolute top-2.5 left-2.5 flex items-center gap-1.5 z-20 pointer-events-none">
            @if(!empty($project->featured) || !empty($project->is_featured))
            <span class="inline-flex items-center gap-1 rounded-full bg-amber-500/15 dark:bg-amber-400/15 border border-amber-500/30 text-amber-600 dark:text-amber-300 px-2.5 py-0.5 text-[10px] font-extrabold shadow-sm backdrop-blur-xl">
                <svg class="w-3 h-3 text-amber-500 dark:text-amber-400 fill-amber-500 dark:fill-amber-400" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                Featured
            </span>
            @endif
            @if($project->category)
            <span class="rounded-full bg-slate-900/40 dark:bg-slate-900/60 border border-white/20 text-slate-100 px-2.5 py-0.5 text-[10px] font-bold shadow-sm backdrop-blur-xl">
                {{ $project->category->name }}
            </span>
            @endif
        </div>

        <!-- Translucent Glass Hover Overlay with Interactive Showcase Button -->
        <div class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-all duration-300 bg-slate-950/20 dark:bg-slate-950/30 backdrop-blur-md z-10">
            <button type="button" class="open-project-modal inline-flex items-center gap-2 rounded-xl bg-blue-600/80 hover:bg-blue-600 border border-blue-400/30 text-white font-bold px-4 py-2 text-xs shadow-lg backdrop-blur-xl transition-all hover:scale-105 cursor-pointer" data-project-slug="{{ $project->slug }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                <span>Interactive Showcase</span>
            </button>
        </div>
    </div>

    <!-- Glass Card Body -->
    <div class="flex flex-1 flex-col p-4">
        <h3 class="text-base font-bold text-slate-900 dark:text-white group-hover:text-blue-600 dark:group-hover:text-cyan-400 transition-colors line-clamp-1 mb-1.5">
            {{ $project->title }}
        </h3>

        <p class="mb-4 line-clamp-2 text-xs leading-relaxed text-slate-600 dark:text-slate-300">
            {{ $project->short_description ?? ($project->summary ?? $project->description) }}
        </p>

        <!-- Tech Chips -->
        @if($project->technologies && $project->technologies->count() > 0)
        <div class="mb-4 flex flex-wrap gap-1">
            @foreach($project->technologies->take(4) as $tech)
            <span class="rounded-lg border border-white/50 dark:border-white/10 bg-white/40 dark:bg-slate-800/40 px-2 py-0.5 text-[11px] font-mono text-slate-700 dark:text-slate-300">
                {{ $tech->name }}
            </span>
            @endforeach
        </div>
        @endif

        <!-- Card Footer -->
        <div class="mt-auto flex items-center justify-between border-t border-white/40 dark:border-white/10 pt-3 text-xs font-semibold">
            <button type="button" data-project-slug="{{ $project->slug }}" class="open-project-modal text-blue-600 dark:text-cyan-400 hover:underline inline-flex items-center gap-1 cursor-pointer">
                <span>Details</span>
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
            </button>

            <a href="{{ route('projects.show', $project->slug) }}" onclick="event.stopPropagation()" class="text-slate-500 hover:text-slate-900 dark:text-slate-400 dark:hover:text-white transition-colors">
                Page →
            </a>
        </div>
    </div>

</div>
