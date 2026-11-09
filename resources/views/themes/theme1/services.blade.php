<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
<style>
.services-swiper .swiper-pagination-bullet {
    background: #64748b;
    opacity: 0.4;
    transition: all 0.3s ease;
}
.services-swiper .swiper-pagination-bullet-active {
    background: #3b82f6;
    opacity: 1;
    width: 24px;
    border-radius: 12px;
}
.dark .services-swiper .swiper-pagination-bullet {
    background: #cbd5e1;
}
.dark .services-swiper .swiper-pagination-bullet-active {
    background: #22d3ee;
}
.services-swiper .swiper-button-disabled {
    opacity: 0 !important;
    pointer-events: none;
}
</style>

<section id="services" class="py-12 sm:py-16 relative">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 relative">
        
        <!-- Section Header -->
        <div class="text-center max-w-2xl mx-auto mb-10">
            <div class="inline-flex items-center gap-1.5 px-3 py-0.5 rounded-full bg-blue-500/10 dark:bg-cyan-500/10 border border-blue-500/20 dark:border-cyan-500/20 text-blue-600 dark:text-cyan-400 text-[11px] font-semibold uppercase tracking-wider backdrop-blur-md">
                <span class="w-1.5 h-1.5 rounded-full bg-blue-500 dark:bg-cyan-400 animate-pulse"></span>
                <span>Offerings</span>
            </div>
            <h2 class="text-2xl sm:text-4xl font-extrabold text-slate-900 dark:text-white tracking-tight mt-2">
                Services
            </h2>
        </div>

        @if(isset($services) && count($services) > 0)
        <!-- Swiper Container -->
        <div class="swiper services-swiper !p-4 -m-4 !pb-14">
            <div class="swiper-wrapper">
                @foreach($services as $srv)
                    <div class="swiper-slide !h-auto">
                        @include('themes.theme1.components.service-card', [
                            'title' => is_array($srv) ? $srv['title'] : $srv->title,
                            'description' => is_array($srv) ? $srv['description'] : $srv->description,
                            'icon' => is_array($srv) ? $srv['icon'] : $srv->icon,
                            'skills' => $skillCategories->first()?->skills
                        ])
                    </div>
                @endforeach
            </div>
            
            <!-- Pagination -->
            <div class="swiper-pagination"></div>

            <!-- Navigation Buttons -->
            <div class="swiper-button-prev !text-blue-600 dark:!text-cyan-400 !bg-white/90 dark:!bg-slate-800/90 !w-10 !h-10 !rounded-full shadow-[0_4px_20px_-4px_rgba(0,0,0,0.1)] backdrop-blur-md flex border border-black/5 dark:border-white/10 after:!text-sm transition-opacity duration-300 !left-0"></div>
            <div class="swiper-button-next !text-blue-600 dark:!text-cyan-400 !bg-white/90 dark:!bg-slate-800/90 !w-10 !h-10 !rounded-full shadow-[0_4px_20px_-4px_rgba(0,0,0,0.1)] backdrop-blur-md flex border border-black/5 dark:border-white/10 after:!text-sm transition-opacity duration-300 !right-0"></div>
        </div>
        @endif

    </div>
</section>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', () => {
    if (typeof Swiper !== 'undefined') {
        new Swiper('.services-swiper', {
            slidesPerView: 1,
            spaceBetween: 24,
            watchSlidesProgress: true,
            // Graceful auto-play
            autoplay: {
                delay: 4000,
                disableOnInteraction: true,
                pauseOnMouseEnter: true,
            },
            pagination: {
                el: '.swiper-pagination',
                clickable: true,
            },
            navigation: {
                nextEl: '.swiper-button-next',
                prevEl: '.swiper-button-prev',
            },
            breakpoints: {
                // Tablet
                768: {
                    slidesPerView: 2,
                    spaceBetween: 24,
                },
                // Desktop
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
