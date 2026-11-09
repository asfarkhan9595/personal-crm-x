<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
<style>
.t3-services-swiper .swiper-pagination-bullet {
    background: #94a3b8;
    opacity: 0.4;
    transition: all 0.3s ease;
}
.t3-services-swiper .swiper-pagination-bullet-active {
    background: #3b82f6;
    opacity: 1;
    width: 24px;
    border-radius: 12px;
}
.dark .t3-services-swiper .swiper-pagination-bullet {
    background: #cbd5e1;
}
.dark .t3-services-swiper .swiper-pagination-bullet-active {
    background: #22d3ee;
}
.t3-services-swiper .swiper-button-disabled {
    opacity: 0 !important;
    pointer-events: none;
}
</style>

<section id="services" class="py-20 sm:py-24 relative bg-slate-200/40 dark:bg-slate-900/40 border-y border-slate-300/50 dark:border-white/10 backdrop-blur-sm">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative">
        
        <!-- Section Header -->
        <div class="text-center max-w-3xl mx-auto mb-16">
            <span class="inline-flex items-center gap-2 px-3.5 py-1 rounded-full bg-blue-500/10 dark:bg-cyan-500/10 border border-blue-500/20 dark:border-cyan-500/20 text-blue-600 dark:text-cyan-400 text-xs font-bold uppercase tracking-wider backdrop-blur-md">
                <span class="w-2 h-2 rounded-full bg-blue-500 dark:bg-cyan-400 animate-pulse"></span>
                <span>Offerings &amp; Services</span>
            </span>
            <h2 class="text-3xl sm:text-5xl font-extrabold text-slate-900 dark:text-white tracking-tight mt-3">
                Services &amp; Solutions
            </h2>
            <p class="mt-2 text-sm text-slate-600 dark:text-slate-300 max-w-xl mx-auto">
                High-performance backend systems, REST APIs, full-stack web platforms, and automated workflow solutions.
            </p>
        </div>

        @if(isset($services) && count($services) > 0)
        <!-- Swiper Container -->
        <div class="swiper t3-services-swiper !pb-14 !p-4 -m-4">
            <div class="swiper-wrapper">
                @foreach($services as $srv)
                @php
                    $srvTitle = is_array($srv) ? $srv['title'] : $srv->title;
                    $srvDesc = is_array($srv) ? $srv['description'] : $srv->description;
                    $srvIcon = is_array($srv) ? $srv['icon'] : $srv->icon;
                @endphp
                <div class="swiper-slide !h-auto">
                    <div class="group h-full p-6 sm:p-8 rounded-3xl bg-white/70 dark:bg-slate-900/70 backdrop-blur-xl border border-white/80 dark:border-white/10 shadow-lg hover:shadow-xl hover:shadow-blue-500/10 hover:border-blue-500/40 hover:-translate-y-1 transition-all duration-300 flex flex-col justify-between">
                        <div>
                            <div class="w-12 h-12 rounded-2xl bg-blue-500/10 dark:bg-cyan-500/10 border border-blue-500/20 text-blue-600 dark:text-cyan-400 flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                                @if(!empty($srvIcon) && str_contains($srvIcon, '<svg'))
                                    {!! $srvIcon !!}
                                @else
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path></svg>
                                @endif
                            </div>

                            <h3 class="text-xl font-bold text-slate-900 dark:text-white group-hover:text-blue-600 dark:group-hover:text-cyan-400 transition-colors mb-3">
                                {{ $srvTitle }}
                            </h3>

                            <p class="text-xs leading-relaxed text-slate-600 dark:text-slate-300 mb-4">
                                {{ $srvDesc }}
                            </p>
                        </div>

                        @if(isset($skillCategories) && $skillCategories->first()?->skills->count())
                        <div class="mt-auto flex flex-wrap gap-1.5 pt-4 border-t border-slate-200/60 dark:border-white/10">
                            @foreach($skillCategories->first()->skills->take(4) as $sk)
                            <span class="px-2.5 py-1 rounded-full text-[10px] font-medium bg-blue-500/10 dark:bg-cyan-500/10 text-blue-600 dark:text-cyan-400 border border-blue-500/20 dark:border-cyan-500/20">
                                {{ $sk->name }}
                            </span>
                            @endforeach
                        </div>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="swiper-pagination"></div>

            <!-- Navigation Buttons - Theme 3 Glass Styling -->
            <div class="swiper-button-prev t3-services-prev !text-slate-700 dark:!text-white hover:!text-blue-600 dark:hover:!text-cyan-400 !bg-white/70 dark:!bg-slate-900/70 !w-12 !h-12 !rounded-full shadow-lg backdrop-blur-xl flex border border-white/80 dark:border-white/10 hover:border-blue-500/40 after:!text-sm transition-all duration-300 !left-0"></div>
            <div class="swiper-button-next t3-services-next !text-slate-700 dark:!text-white hover:!text-blue-600 dark:hover:!text-cyan-400 !bg-white/70 dark:!bg-slate-900/70 !w-12 !h-12 !rounded-full shadow-lg backdrop-blur-xl flex border border-white/80 dark:border-white/10 hover:border-blue-500/40 after:!text-sm transition-all duration-300 !right-0"></div>
        </div>
        @endif

    </div>
</section>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', () => {
    if (typeof Swiper !== 'undefined') {
        new Swiper('.t3-services-swiper', {
            slidesPerView: 1,
            spaceBetween: 24,
            watchSlidesProgress: true,
            autoplay: {
                delay: 4500,
                disableOnInteraction: true,
                pauseOnMouseEnter: true,
            },
            pagination: {
                el: '.swiper-pagination',
                clickable: true,
            },
            navigation: {
                nextEl: '.t3-services-next',
                prevEl: '.t3-services-prev',
            },
            breakpoints: {
                768: {
                    slidesPerView: 2,
                    spaceBetween: 24,
                },
                1024: {
                    slidesPerView: 3,
                    spaceBetween: 24,
                },
            },
            a11y: {
                prevSlideMessage: 'Previous services',
                nextSlideMessage: 'Next services',
            }
        });
    }
});
</script>
@endpush

