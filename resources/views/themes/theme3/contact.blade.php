<section id="contact" class="py-20 sm:py-24 relative bg-slate-200/40 dark:bg-slate-900/40 border-t border-slate-300/50 dark:border-white/10 backdrop-blur-sm">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Section Header -->
        <div class="text-center max-w-2xl mx-auto mb-16">
            <span class="inline-flex items-center gap-2 px-3.5 py-1 rounded-full bg-blue-500/10 dark:bg-cyan-500/10 border border-blue-500/20 dark:border-cyan-500/20 text-blue-600 dark:text-cyan-400 text-xs font-bold uppercase tracking-wider backdrop-blur-md">
                <span class="w-2 h-2 rounded-full bg-blue-500 dark:bg-cyan-400 animate-pulse"></span>
                <span>Get In Touch</span>
            </span>
            <h2 class="text-3xl sm:text-5xl font-extrabold text-slate-900 dark:text-white tracking-tight mt-3">
                Let's Build Together
            </h2>
            <p class="mt-2 text-sm text-slate-600 dark:text-slate-300 max-w-xl mx-auto">
                Have a project, freelance request, or technical question? Send a message directly below.
            </p>
        </div>

        @if(session('success'))
        <div class="mb-8 max-w-4xl mx-auto p-4 rounded-2xl bg-emerald-500/15 border border-emerald-500/30 text-emerald-600 dark:text-emerald-400 text-sm font-semibold backdrop-blur-md flex items-center gap-2">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
            <span>{{ session('success') }}</span>
        </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            
            <!-- Left Info Panel -->
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

                <div class="p-7 sm:p-8 rounded-3xl bg-white/70 dark:bg-slate-900/70 backdrop-blur-2xl border border-white/80 dark:border-white/10 shadow-xl flex-1 flex flex-col justify-between space-y-6">
                    <div class="space-y-6">
                        <div>
                            <span class="text-xs font-bold uppercase tracking-wider text-blue-600 dark:text-cyan-400 block mb-1">Direct Channels</span>
                            <h3 class="text-xl font-extrabold text-slate-900 dark:text-white">Contact Info</h3>
                        </div>

                        <div class="space-y-3">
                            @if($showEmail && !empty($email))
                            <a href="mailto:{{ $email }}" class="group p-4 rounded-2xl bg-white/80 dark:bg-slate-800/80 border border-slate-200 dark:border-slate-700/80 flex items-center gap-4 transition-all hover:border-blue-500/40 hover:scale-[1.01]">
                                <div class="w-10 h-10 rounded-xl bg-blue-500/10 text-blue-600 dark:text-cyan-400 border border-blue-500/20 flex items-center justify-center flex-shrink-0">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                                </div>
                                <div class="min-w-0 flex-1">
                                    <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 block">Direct Email</span>
                                    <span class="font-bold text-xs sm:text-sm text-slate-900 dark:text-white group-hover:text-blue-600 dark:group-hover:text-cyan-400 transition-colors truncate block">{{ $email }}</span>
                                </div>
                            </a>
                            @endif

                            @if($showWhatsapp && !empty($whatsapp))
                            @php $cleanWa = preg_replace('/[^0-9]/', '', $whatsapp); @endphp
                            <a href="https://wa.me/{{ $cleanWa }}" target="_blank" class="group p-4 rounded-2xl bg-white/80 dark:bg-slate-800/80 border border-slate-200 dark:border-slate-700/80 flex items-center gap-4 transition-all hover:border-emerald-500/40 hover:scale-[1.01]">
                                <div class="w-10 h-10 rounded-xl bg-emerald-500/10 text-emerald-500 border border-emerald-500/20 flex items-center justify-center flex-shrink-0">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
                                </div>
                                <div class="min-w-0 flex-1">
                                    <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 block">WhatsApp Chat</span>
                                    <span class="font-bold text-xs sm:text-sm text-slate-900 dark:text-white group-hover:text-emerald-500 transition-colors truncate block">{{ $whatsapp }}</span>
                                </div>
                            </a>
                            @endif
                        </div>

                        <!-- Location & Availability -->
                        <div class="grid grid-cols-2 gap-3 pt-2">
                            <div class="p-3.5 rounded-2xl bg-slate-50/80 dark:bg-slate-800/50 border border-slate-200/50 dark:border-slate-700/50">
                                <span class="text-[9px] font-bold uppercase tracking-wider text-slate-400 block">Location</span>
                                <span class="font-bold text-xs text-slate-900 dark:text-slate-200 mt-0.5 block truncate">{{ $location }}</span>
                            </div>
                            <div class="p-3.5 rounded-2xl bg-slate-50/80 dark:bg-slate-800/50 border border-slate-200/50 dark:border-slate-700/50">
                                <span class="text-[9px] font-bold uppercase tracking-wider text-slate-400 block">Status</span>
                                <span class="font-bold text-xs text-blue-600 dark:text-cyan-400 mt-0.5 block truncate">{{ $availability }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Social Links -->
                    @if($visibleSocialLinks->count() > 0)
                    <div class="pt-4 border-t border-slate-200/60 dark:border-slate-800/60">
                        <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 mb-3 block">Social Networks</span>
                        <div class="flex flex-wrap gap-2">
                            @foreach($visibleSocialLinks as $link)
                            <a href="{{ $link->url }}" target="_blank" rel="noopener noreferrer"
                               class="px-3.5 py-2 rounded-full bg-white/80 dark:bg-slate-800/80 border border-slate-200 dark:border-slate-700 text-slate-700 dark:text-slate-300 hover:text-blue-600 dark:hover:text-cyan-400 text-xs font-bold backdrop-blur-md transition-all hover:scale-105">
                                {{ $link->platform }} ↗
                            </a>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Right Form Panel -->
            <div class="lg:col-span-7">
                <div class="p-7 sm:p-8 rounded-3xl bg-white/70 dark:bg-slate-900/70 backdrop-blur-2xl border border-white/80 dark:border-white/10 shadow-xl">
                    <h3 class="text-xl font-extrabold text-slate-900 dark:text-white mb-2">Send a Message</h3>
                    <p class="text-xs text-slate-600 dark:text-slate-300 mb-6">Leave your details and I will get back to you as soon as possible.</p>

                    <form action="{{ route('contact.store') }}" method="POST" class="space-y-4">
                        @csrf
                        
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label for="glass-name" class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Your Name *</label>
                                <input type="text" id="glass-name" name="name" value="{{ old('name') }}" required placeholder="Jane Doe"
                                       class="w-full rounded-2xl bg-white/80 dark:bg-slate-800/80 border border-slate-200 dark:border-slate-700 px-4 py-3 text-xs text-slate-900 dark:text-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500/50 backdrop-blur-md">
                            </div>
                            <div>
                                <label for="glass-email" class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Email Address *</label>
                                <input type="email" id="glass-email" name="email" value="{{ old('email') }}" required placeholder="jane@example.com"
                                       class="w-full rounded-2xl bg-white/80 dark:bg-slate-800/80 border border-slate-200 dark:border-slate-700 px-4 py-3 text-xs text-slate-900 dark:text-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500/50 backdrop-blur-md">
                            </div>
                        </div>

                        <div>
                            <label for="glass-subject" class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Subject</label>
                            <input type="text" id="glass-subject" name="subject" value="{{ old('subject') }}" required placeholder="Project Proposal"
                                   class="w-full rounded-2xl bg-white/80 dark:bg-slate-800/80 border border-slate-200 dark:border-slate-700 px-4 py-3 text-xs text-slate-900 dark:text-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500/50 backdrop-blur-md">
                        </div>

                        <div>
                            <label for="glass-message" class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Message *</label>
                            <textarea id="glass-message" name="message" rows="5" required placeholder="Tell me about your project or inquiry..."
                                      class="w-full rounded-2xl bg-white/80 dark:bg-slate-800/80 border border-slate-200 dark:border-slate-700 px-4 py-3 text-xs text-slate-900 dark:text-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500/50 backdrop-blur-md">{{ old('message') }}</textarea>
                        </div>

                        <button type="submit" class="w-full inline-flex items-center justify-center gap-2 rounded-full bg-blue-600 hover:bg-blue-700 text-white font-extrabold px-6 py-3.5 text-xs shadow-lg shadow-blue-500/25 transition-all hover:scale-[1.01] active:scale-[0.99] cursor-pointer">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg>
                            <span>Send Message</span>
                        </button>
                    </form>
                </div>
            </div>

        </div>

    </div>
</section>
