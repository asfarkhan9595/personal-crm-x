<section id="contact" class="py-20 relative">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-3xl mx-auto mb-16">
            <span class="text-xs font-bold uppercase tracking-widest text-emerald-500 bg-emerald-500/10 px-3 py-1 rounded-full">
                Get In Touch
            </span>
            <h2 class="text-3xl font-extrabold text-slate-900 dark:text-slate-100 mt-3 sm:text-4xl">
                Contact Me
            </h2>
            <div class="mt-2 h-1 w-12 bg-emerald-500 mx-auto rounded-full"></div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12">
            <!-- Contact Info -->
            <div class="lg:col-span-5 space-y-6">
                <div class="p-6 rounded-2xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 shadow-md">
                    <h3 class="text-xl font-bold text-slate-900 dark:text-slate-100 mb-4">Let's Connect</h3>
                    <p class="text-slate-600 dark:text-slate-400 text-sm leading-relaxed mb-6">
                        Have a project in mind, a question, or an opportunity? Feel free to reach out to me directly or use the contact form.
                    </p>

                    <div class="space-y-4">
                        @if(isset($profile->email))
                        <div class="flex items-center gap-3">
                            <div class="p-3 rounded-xl bg-emerald-500/10 text-emerald-500">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                            </div>
                            <div>
                                <span class="text-xs text-slate-400 block">Email Address</span>
                                <a href="mailto:{{ $profile->email }}" class="font-semibold text-slate-800 dark:text-slate-200 hover:text-emerald-500">{{ $profile->email }}</a>
                            </div>
                        </div>
                        @endif

                        @if(isset($profile->location))
                        <div class="flex items-center gap-3">
                            <div class="p-3 rounded-xl bg-emerald-500/10 text-emerald-500">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                            </div>
                            <div>
                                <span class="text-xs text-slate-400 block">Location</span>
                                <span class="font-semibold text-slate-800 dark:text-slate-200">{{ $profile->location }}</span>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Contact Form -->
            <div class="lg:col-span-7">
                <div class="p-8 rounded-2xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 shadow-xl">
                    @if(session('success'))
                    <div class="mb-6 p-4 rounded-xl bg-emerald-500/15 border border-emerald-500/30 text-emerald-600 dark:text-emerald-400 text-sm font-medium">
                        {{ session('success') }}
                    </div>
                    @endif

                    <form action="{{ route('contact.store') }}" method="POST" class="space-y-6">
                        @csrf
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            <div>
                                <label for="name" class="block text-xs font-bold uppercase tracking-wider text-slate-600 dark:text-slate-400 mb-2">Name *</label>
                                <input type="text" id="name" name="name" required class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950 text-slate-900 dark:text-slate-100 focus:ring-2 focus:ring-emerald-500 focus:outline-none transition-colors" placeholder="Your Name">
                            </div>
                            <div>
                                <label for="email" class="block text-xs font-bold uppercase tracking-wider text-slate-600 dark:text-slate-400 mb-2">Email *</label>
                                <input type="email" id="email" name="email" required class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950 text-slate-900 dark:text-slate-100 focus:ring-2 focus:ring-emerald-500 focus:outline-none transition-colors" placeholder="your@email.com">
                            </div>
                        </div>

                        <div>
                            <label for="subject" class="block text-xs font-bold uppercase tracking-wider text-slate-600 dark:text-slate-400 mb-2">Subject</label>
                            <input type="text" id="subject" name="subject" class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950 text-slate-900 dark:text-slate-100 focus:ring-2 focus:ring-emerald-500 focus:outline-none transition-colors" placeholder="Project Inquiry">
                        </div>

                        <div>
                            <label for="message" class="block text-xs font-bold uppercase tracking-wider text-slate-600 dark:text-slate-400 mb-2">Message *</label>
                            <textarea id="message" name="message" rows="5" required class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950 text-slate-900 dark:text-slate-100 focus:ring-2 focus:ring-emerald-500 focus:outline-none transition-colors" placeholder="Tell me about your project..."></textarea>
                        </div>

                        <button type="submit" class="w-full py-4 rounded-xl bg-emerald-500 hover:bg-emerald-600 text-white font-bold text-base shadow-lg shadow-emerald-500/25 transition-all hover:scale-[1.01] active:scale-[0.99] cursor-pointer">
                            Send Message
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

