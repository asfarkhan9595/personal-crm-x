<section id="contact" class="py-20 sm:py-24 relative">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Section Header -->
        <div class="text-center max-w-2xl mx-auto mb-16">
            <div class="inline-flex items-center gap-2 px-3.5 py-1 rounded-full bg-blue-500/10 dark:bg-cyan-500/10 border border-blue-500/20 dark:border-cyan-500/20 text-blue-600 dark:text-cyan-400 text-xs font-semibold uppercase tracking-wider backdrop-blur-md">
                <span class="w-2 h-2 rounded-full bg-blue-500 dark:bg-cyan-400 animate-pulse"></span>
                <span>Get In Touch</span>
            </div>
            <h2 class="text-3xl sm:text-5xl font-extrabold text-slate-900 dark:text-white tracking-tight mt-3">
                Let's Connect &amp; Collaborate
            </h2>
            <p class="mt-2 text-sm text-slate-600 dark:text-slate-400 max-w-xl mx-auto">
                Have a project in mind, python automation requirement, or a full-stack opportunity? Reach out directly or send a message below.
            </p>
        </div>

        @if(session('success'))
        <div class="mb-8 max-w-4xl mx-auto p-4 rounded-2xl bg-emerald-500/15 border border-emerald-500/30 text-emerald-600 dark:text-emerald-400 text-sm font-semibold backdrop-blur-md flex items-center gap-2">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
            <span>{{ session('success') }}</span>
        </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            
            <!-- Left Side: Direct Contact Channels & Social Links (Single Sleek Glass Panel) -->
            <div class="lg:col-span-5 flex flex-col">
                
                @php
                    $email = $settings->contact_email ?? ($profile->email ?? 'asfarkhan9595@gmail.com');
                    $showEmail = ($settings->show_email ?? '1') === '1';
                    $whatsapp = $settings->contact_whatsapp ?? '+919129599595';
                    $showWhatsapp = ($settings->show_whatsapp ?? '1') === '1';
                    $location = $settings->contact_location ?? ($profile->location ?? 'India');
                    $availability = $settings->contact_availability ?? 'Open to opportunities';
                    $showGithub = ($settings->show_github ?? '1') === '1';
                    $showLinkedin = ($settings->show_linkedin ?? '1') === '1';
                    $showTwitter = ($settings->show_twitter ?? '1') === '1';

                    $visibleSocialLinks = collect();
                    if (isset($socialLinks)) {
                        $visibleSocialLinks = $socialLinks->filter(function($link) use ($showGithub, $showLinkedin, $showTwitter) {
                            $platformLower = strtolower($link->platform ?? '');
                            if (str_contains($platformLower, 'email') || str_contains($platformLower, 'whatsapp')) return false;
                            if (str_contains($platformLower, 'github') && !$showGithub) return false;
                            if (str_contains($platformLower, 'linkedin') && !$showLinkedin) return false;
                            if ((str_contains($platformLower, 'twitter') || str_contains($platformLower, 'x')) && !$showTwitter) return false;
                            return true;
                        });
                    }
                @endphp

                <div class="p-6 sm:p-7 rounded-3xl nexus-glass-card shadow-2xl flex-1 flex flex-col justify-between space-y-6">
                    
                    <div class="space-y-5">
                        <!-- Card Header -->
                        <div>
                            <span class="text-[10px] font-mono font-bold uppercase tracking-wider text-blue-600 dark:text-cyan-400 block mb-1">// DIRECT CHANNELS</span>
                            <h3 class="text-xl font-extrabold text-slate-900 dark:text-white">Contact Information</h3>
                        </div>

                        <!-- Direct Contact Rows -->
                        <div class="space-y-3">
                            @if($showEmail && !empty($email))
                            <a href="mailto:{{ $email }}" class="group p-3.5 rounded-2xl bg-white/40 dark:bg-slate-900/40 border border-white/60 dark:border-white/10 flex items-center gap-3.5 transition-all hover:bg-white/70 dark:hover:bg-slate-900/70 hover:scale-[1.01] backdrop-blur-md">
                                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-blue-500/10 dark:bg-cyan-500/10 text-blue-600 dark:text-cyan-400 border border-blue-500/20 dark:border-cyan-500/20 group-hover:scale-110 transition-transform shrink-0">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                                </div>
                                <div class="min-w-0 flex-1">
                                    <span class="text-[10px] font-mono font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 block">Direct Email</span>
                                    <span class="font-bold text-xs sm:text-sm text-slate-900 dark:text-white group-hover:text-blue-600 dark:group-hover:text-cyan-400 transition-colors truncate block">{{ $email }}</span>
                                </div>
                                <svg class="w-4 h-4 text-slate-400 group-hover:text-blue-600 dark:group-hover:text-cyan-400 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                            </a>
                            @endif

                            @if($showWhatsapp && !empty($whatsapp))
                            @php $cleanWa = preg_replace('/[^0-9]/', '', $whatsapp); @endphp
                            <a href="https://wa.me/{{ $cleanWa }}" target="_blank" class="group p-3.5 rounded-2xl bg-white/40 dark:bg-slate-900/40 border border-white/60 dark:border-white/10 flex items-center gap-3.5 transition-all hover:bg-white/70 dark:hover:bg-slate-900/70 hover:scale-[1.01] backdrop-blur-md">
                                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-emerald-500/10 text-emerald-500 border border-emerald-500/20 group-hover:scale-110 transition-transform shrink-0">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
                                </div>
                                <div class="min-w-0 flex-1">
                                    <span class="text-[10px] font-mono font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 block">WhatsApp Chat</span>
                                    <span class="font-bold text-xs sm:text-sm text-slate-900 dark:text-white group-hover:text-emerald-500 transition-colors font-mono truncate block">{{ $whatsapp }}</span>
                                </div>
                                <svg class="w-4 h-4 text-slate-400 group-hover:text-emerald-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                            </a>
                            @endif
                        </div>

                        <!-- Location & Availability Strip -->
                        @if(!empty($location) || !empty($availability))
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 pt-1">
                            @if(!empty($location))
                            <div class="p-3 rounded-2xl bg-white/30 dark:bg-slate-900/30 border border-white/50 dark:border-white/10 flex items-center gap-3">
                                <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-red-500/10 text-red-500 border border-red-500/20 shrink-0">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                </div>
                                <div class="min-w-0">
                                    <span class="text-[9px] font-mono font-bold uppercase text-slate-500 dark:text-slate-400 block">Location</span>
                                    <span class="font-semibold text-xs text-slate-900 dark:text-slate-200 truncate block">{{ $location }}</span>
                                </div>
                            </div>
                            @endif

                            @if(!empty($availability))
                            <div class="p-3 rounded-2xl bg-white/30 dark:bg-slate-900/30 border border-white/50 dark:border-white/10 flex items-center gap-3">
                                <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-amber-500/10 text-amber-500 border border-amber-500/20 shrink-0">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                                </div>
                                <div class="min-w-0">
                                    <span class="text-[9px] font-mono font-bold uppercase text-slate-500 dark:text-slate-400 block">Status</span>
                                    <span class="font-semibold text-xs text-slate-900 dark:text-slate-200 truncate block">{{ $availability }}</span>
                                </div>
                            </div>
                            @endif
                        </div>
                        @endif
                    </div>

                    <!-- Social Links Footer Strip -->
                    @if($visibleSocialLinks->count() > 0)
                    <div class="pt-4 border-t border-white/60 dark:border-white/10">
                        <span class="text-[10px] font-mono font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-2.5 block">// CONNECT ON SOCIAL</span>
                        <div class="flex flex-wrap gap-2">
                            @foreach($visibleSocialLinks as $link)
                                <a href="{{ $link->url }}" target="_blank" rel="noopener noreferrer"
                                   class="px-3.5 py-2 rounded-xl bg-white/50 dark:bg-slate-900/50 border border-white/60 dark:border-white/10 text-slate-700 dark:text-slate-300 hover:text-blue-600 dark:hover:text-cyan-400 hover:bg-white dark:hover:bg-slate-900 font-semibold text-xs flex items-center gap-2 backdrop-blur-md transition-all hover:scale-105">
                                    <span>{{ $link->platform }}</span>
                                    <svg class="w-3 h-3 opacity-60" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                                </a>
                            @endforeach
                        </div>
                    </div>
                    @endif

                </div>

            </div>

            <!-- Right Side: Glass Contact Form -->
            <div class="lg:col-span-7">
                <div class="p-6 sm:p-8 rounded-3xl nexus-glass-card shadow-2xl">
                    <h3 class="text-xl font-bold text-slate-900 dark:text-white mb-2">Send a Direct Message</h3>
                    <p class="text-xs text-slate-600 dark:text-slate-400 mb-6">Fill in your inquiry details below and I will get back to you within 24 hours.</p>

                    <form action="{{ route('contact.store') }}" method="POST" class="space-y-4">
                        @csrf
                        
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label for="nexus-name" class="block text-[11px] font-mono font-bold text-slate-700 dark:text-slate-300 mb-1">YOUR NAME *</label>
                                <input type="text" id="nexus-name" name="name" value="{{ old('name') }}" required placeholder="John Doe"
                                       class="w-full rounded-xl bg-white/40 dark:bg-slate-900/40 border border-white/50 dark:border-white/10 px-4 py-3 text-xs text-slate-900 dark:text-white placeholder-slate-400 focus:border-blue-500 focus:outline-none backdrop-blur-md">
                            </div>
                            <div>
                                <label for="nexus-email" class="block text-[11px] font-mono font-bold text-slate-700 dark:text-slate-300 mb-1">EMAIL ADDRESS *</label>
                                <input type="email" id="nexus-email" name="email" value="{{ old('email') }}" required placeholder="john@example.com"
                                       class="w-full rounded-xl bg-white/40 dark:bg-slate-900/40 border border-white/50 dark:border-white/10 px-4 py-3 text-xs text-slate-900 dark:text-white placeholder-slate-400 focus:border-blue-500 focus:outline-none backdrop-blur-md">
                            </div>
                        </div>

                        <div>
                            <label for="nexus-subject" class="block text-[11px] font-mono font-bold text-slate-700 dark:text-slate-300 mb-1">SUBJECT</label>
                            <input type="text" id="nexus-subject" name="subject" value="{{ old('subject') }}" required placeholder="Project Proposal / Inquiry"
                                   class="w-full rounded-xl bg-white/40 dark:bg-slate-900/40 border border-white/50 dark:border-white/10 px-4 py-3 text-xs text-slate-900 dark:text-white placeholder-slate-400 focus:border-blue-500 focus:outline-none backdrop-blur-md">
                        </div>

                        <div>
                            <label for="nexus-message" class="block text-[11px] font-mono font-bold text-slate-700 dark:text-slate-300 mb-1">MESSAGE *</label>
                            <textarea id="nexus-message" name="message" rows="5" required placeholder="Describe your project goals, timelines, or technology requirements..."
                                      class="w-full rounded-xl bg-white/40 dark:bg-slate-900/40 border border-white/50 dark:border-white/10 px-4 py-3 text-xs text-slate-900 dark:text-white placeholder-slate-400 focus:border-blue-500 focus:outline-none backdrop-blur-md">{{ old('message') }}</textarea>
                        </div>

                        <button type="submit" class="w-full inline-flex items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-extrabold px-6 py-3.5 text-xs shadow-lg border border-white/20 transition-all hover:scale-[1.01] active:scale-[0.99] cursor-pointer">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg>
                            <span>Submit Message</span>
                        </button>
                    </form>
                </div>
            </div>

        </div>

    </div>
</section>
