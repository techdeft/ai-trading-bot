<x-layouts.guest>
    <x-slot name="title">About Us | Falcon x</x-slot>

    <!-- Hero Section -->
    <section class="relative py-24 lg:py-40 overflow-hidden">
        <div class="absolute inset-0 bg-background-dark">
            <div class="absolute inset-0 bg-gradient-to-b from-primary/5 to-transparent opacity-50"></div>
            <!-- Animated background elements -->
            <div class="absolute top-20 left-10 w-72 h-72 bg-primary/10 rounded-full blur-[100px] animate-pulse"></div>
            <div class="absolute bottom-20 right-10 w-96 h-96 bg-success-green/5 rounded-full blur-[120px] animate-pulse"
                style="animation-delay: 2s"></div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center">
            <div
                class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-surface-dark border border-border-dark text-slate-300 text-xs font-bold mb-8 backdrop-blur-sm">
                <span class="w-2 h-2 rounded-full bg-success-green animate-pulse"></span>
                ESTABLISHED 2023
            </div>
            <h1 class="text-5xl lg:text-7xl font-black mb-8 tracking-tight">
                Institutional Wealth <br>
                <span
                    class="text-transparent bg-clip-text bg-gradient-to-r from-primary to-emerald-400">Democratized</span>
            </h1>
            <p class="text-xl lg:text-2xl text-slate-400 max-w-3xl mx-auto leading-relaxed">
                We're dismantling the barriers between retail investors and high-frequency algorithmic trading.
                <span class="text-white font-medium">Falcon x puts Wall Street's most powerful tools in your
                    pocket.</span>
            </p>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="border-y border-border-dark bg-surface-dark/50 backdrop-blur-sm relative z-20 -mt-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-2 md:grid-cols-4 divide-x divide-border-dark/50">
                <div class="py-10 text-center p-4">
                    <p class="text-4xl lg:text-5xl font-black text-white mb-2">$4.2M+</p>
                    <p class="text-xs font-bold text-slate-500 uppercase tracking-widest">Profit Generated</p>
                </div>
                <div class="py-10 text-center p-4">
                    <p class="text-4xl lg:text-5xl font-black text-white mb-2">2,500+</p>
                    <p class="text-xs font-bold text-slate-500 uppercase tracking-widest">Active Investors</p>
                </div>
                <div class="py-10 text-center p-4">
                    <p class="text-4xl lg:text-5xl font-black text-success-green mb-2">99.9%</p>
                    <p class="text-xs font-bold text-slate-500 uppercase tracking-widest">Uptime</p>
                </div>
                <div class="py-10 text-center p-4">
                    <p class="text-4xl lg:text-5xl font-black text-white mb-2">24/7</p>
                    <p class="text-xs font-bold text-slate-500 uppercase tracking-widest">Support</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Mission & Story -->
    <section class="py-24 lg:py-32 relative overflow-hidden">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid lg:grid-cols-2 gap-16 lg:gap-24 items-center">
                <div class="relative">
                    <div
                        class="absolute -inset-4 bg-gradient-to-tr from-primary/20 to-success-green/20 rounded-2xl blur-xl">
                    </div>
                    <div class="relative rounded-2xl overflow-hidden border border-border-dark shadow-2xl group">
                        <img src="https://images.unsplash.com/photo-1642543492481-44e81e3914a7?q=80&w=2070&auto=format&fit=crop"
                            alt="Trading Data Analysis"
                            class="w-full h-auto transform group-hover:scale-105 transition-transform duration-700">
                        <div class="absolute inset-0 bg-gradient-to-t from-background-dark/90 to-transparent"></div>
                        <div class="absolute bottom-0 left-0 p-8">
                            <p class="text-white font-bold text-lg">Our trading engine processes 50,000+ data points per
                                second.</p>
                        </div>
                    </div>
                </div>

                <div class="space-y-8">
                    <div>
                        <h2 class="text-primary font-bold tracking-widest uppercase text-sm mb-3">Our Mission</h2>
                        <h3 class="text-3xl lg:text-5xl font-black text-white mb-6">Leveling the Playing Field</h3>
                        <div class="space-y-6 text-slate-400 text-lg leading-relaxed">
                            <p>
                                Founded in 2023 by a team of ex-Google engineers and quantitative analysts, Falcon x was
                                built to solve a glaring inequality:
                                <span class="text-white font-medium border-b border-primary/50 pb-1">The Retail Trading
                                    Disadvantage.</span>
                            </p>
                            <p>
                                While institutions use high-frequency bots, dark pools, and AI sentiment analysis to
                                extract profit, individual investors are often left guessing.
                            </p>
                            <p>
                                We changed the game. By building an institutional-grade AI engine and making it
                                accessible via a simple web dashboard, we've given everyone a seat at the table.
                            </p>
                        </div>
                    </div>

                    <div class="flex gap-4">
                        <a href="{{ route('register') }}"
                            class="px-8 py-4 bg-white text-background-dark font-bold rounded-xl hover:bg-slate-200 transition-colors">
                            Join the Revolution
                        </a>
                        <a href="{{ route('guest.performance') }}"
                            class="px-8 py-4 bg-transparent border border-border-dark text-white font-bold rounded-xl hover:bg-surface-dark transition-colors">
                            View Performance
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Core Values -->
    <section class="py-24 bg-surface-dark border-y border-border-dark">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-16">
                <h2 class="text-3xl lg:text-5xl font-bold mb-6">Built on Trust & Technology</h2>
                <p class="text-slate-400 text-lg">We don't just build bots; we build financial futures. Our core values
                    guide every line of code we write.</p>
            </div>

            <div class="grid md:grid-cols-3 gap-8">
                <!-- Card 1 -->
                <div
                    class="bg-background-dark p-8 rounded-2xl border border-border-dark hover:border-primary/50 transition-all duration-300 group hover:-translate-y-1">
                    <div
                        class="w-14 h-14 bg-primary/10 rounded-xl flex items-center justify-center text-primary mb-6 group-hover:scale-110 transition-transform">
                        <span class="material-symbols-outlined text-3xl">verified_user</span>
                    </div>
                    <h3 class="text-xl font-bold text-white mb-4">Radical Transparency</h3>
                    <p class="text-slate-400 leading-relaxed">
                        We don't hide behind jargon or "proprietary black boxes" when it comes to results. Our fees,
                        performance metrics, and risks are always clear, real-time, and accessible.
                    </p>
                </div>

                <!-- Card 2 -->
                <div
                    class="bg-background-dark p-8 rounded-2xl border border-border-dark hover:border-primary/50 transition-all duration-300 group hover:-translate-y-1">
                    <div
                        class="w-14 h-14 bg-primary/10 rounded-xl flex items-center justify-center text-primary mb-6 group-hover:scale-110 transition-transform">
                        <span class="material-symbols-outlined text-3xl">lock</span>
                    </div>
                    <h3 class="text-xl font-bold text-white mb-4">Security First</h3>
                    <p class="text-slate-400 leading-relaxed">
                        Your capital is your livelihood. We treat it with military-grade encryption, cold storage for
                        98% of funds, and rigorous third-party audits.
                    </p>
                </div>

                <!-- Card 3 -->
                <div
                    class="bg-background-dark p-8 rounded-2xl border border-border-dark hover:border-primary/50 transition-all duration-300 group hover:-translate-y-1">
                    <div
                        class="w-14 h-14 bg-primary/10 rounded-xl flex items-center justify-center text-primary mb-6 group-hover:scale-110 transition-transform">
                        <span class="material-symbols-outlined text-3xl">psychology</span>
                    </div>
                    <h3 class="text-xl font-bold text-white mb-4">Relentless Innovation</h3>
                    <p class="text-slate-400 leading-relaxed">
                        The market evolves every second. So do we. Our AI models are retrained daily on the latest
                        datasets to ensure they adapt to new volatility patterns instantly.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA -->
    <section class="py-24 relative overflow-hidden">
        <div class="absolute inset-0 bg-primary/5 -z-10"></div>
        <div class="max-w-4xl mx-auto px-4 text-center">
            <h2 class="text-4xl lg:text-6xl font-black mb-8">Ready to Automate Your Success?</h2>
            <p class="text-xl text-slate-400 mb-10 max-w-2xl mx-auto">Join thousands of investors who have already
                switched to Falcon x. Setup takes less than 2 minutes.</p>
            <a href="{{ route('register') }}"
                class="inline-block px-10 py-5 bg-primary hover:bg-primary/90 text-white font-bold text-lg rounded-xl transition-all shadow-xl shadow-primary/20 hover:shadow-primary/40 transform hover:-translate-y-1">
                Start Investing Now
            </a>
        </div>
    </section>
</x-layouts.guest>