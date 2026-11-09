<section id="about" class="py-20 sm:py-24 relative bg-slate-50/50 dark:bg-slate-900/40">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <!-- Section Heading -->
        <div class="text-center max-w-3xl mx-auto mb-14">
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-500/10 border border-emerald-500/20 text-emerald-500 text-xs font-bold uppercase tracking-widest">
                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                Get To Know Me
            </div>
            <h2 class="text-3xl font-extrabold text-slate-900 dark:text-slate-100 mt-3 sm:text-4xl tracking-tight">
                About Me
            </h2>
        </div>

        <div class="grid grid-cols-1 gap-10 lg:grid-cols-12 lg:items-center">

            <!-- Bio Text -->
            @php
                $aboutText = $profile->about ?? $profile->bio ?? '';
                $paragraphs = array_filter(explode("\n\n", $aboutText));
                $hasImage = !empty($profile->avatar) || !empty($profile->profile_image);
            @endphp

            <div class="relative pl-6 sm:pl-8 {{ $hasImage ? 'lg:col-span-7' : 'lg:col-span-12 max-w-3xl mx-auto' }}">
                <!-- Left Gradient Border Accent Line -->
                <div class="absolute left-0 top-0 h-full w-1 rounded-full bg-gradient-to-b from-emerald-500 via-teal-400 to-transparent"></div>

                <div class="space-y-4">
                    @if(count($paragraphs) > 0)
                        @foreach($paragraphs as $para)
                        <p class="text-base sm:text-lg leading-relaxed text-slate-600 dark:text-slate-300">
                            {{ $para }}
                        </p>
                        @endforeach
                    @else
                    <p class="text-base sm:text-lg leading-relaxed text-slate-600 dark:text-slate-300">
                        Passionate software developer specializing in Python, AI automation, Laravel backend development, and building modern web applications.
                    </p>
                    @endif
                </div>
            </div>

            <!-- Profile Avatar Frame -->
            @if($hasImage)
            @php
                $imgSrc = $profile->avatar ?? $profile->profile_image ?? '';
                $imgUrl = format_image_url($imgSrc);
            @endphp
            <div class="lg:col-span-5 flex justify-center lg:justify-end">
                <div class="relative group">
                    <div class="absolute -inset-2 rounded-3xl bg-gradient-to-r from-emerald-500 via-teal-400 to-cyan-500 opacity-20 blur-xl transition duration-700 group-hover:opacity-40"></div>
                    <div class="relative overflow-hidden rounded-2xl border border-slate-200/80 dark:border-slate-800 bg-white dark:bg-slate-900 p-2 shadow-2xl">
                        <img src="{{ $imgUrl }}"
                             alt="{{ $profile->name ?? 'Profile' }}"
                             class="w-56 h-56 sm:w-64 sm:h-64 object-cover rounded-xl transition-transform duration-700 group-hover:scale-105">
                    </div>
                </div>
            </div>
            @endif

        </div>
    </div>
</section>

