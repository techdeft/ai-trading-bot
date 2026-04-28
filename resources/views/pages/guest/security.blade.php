<x-layouts.guest>
    <x-slot name="title">Security & Safety | Falcon x</x-slot>

    <!-- Hero -->
    <section class="relative py-24 overflow-hidden">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <div
                class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-primary/10 border border-primary/20 text-primary text-xs font-bold mb-6">
                <span class="material-symbols-outlined text-sm">shield</span>
                BANK-GRADE PROTECTION
            </div>
            <h1 class="text-4xl lg:text-6xl font-black mb-6">Your Funds Are <br><span class="text-primary">Safe With
                    Us</span></h1>
            <p class="text-xl text-slate-400 max-w-2xl mx-auto">Security isn't an afterthought at Falcon x. It's the
                foundation of our entire platform. We use state-of-the-art technology to keep your assets protected
                24/7.</p>
        </div>
    </section>

    <!-- Security Features Grid -->
    <section class="pb-24 px-4">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid md:grid-cols-2 gap-8">
                <!-- Feature 1 -->
                <div
                    class="bg-surface-dark p-8 rounded-2xl border border-border-dark flex gap-6 items-start group hover:border-primary/50 transition-all duration-300">
                    <div
                        class="w-16 h-16 rounded-xl bg-primary/10 flex items-center justify-center text-primary shrink-0 group-hover:scale-110 transition-transform">
                        <span class="material-symbols-outlined text-4xl">lock</span>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-white mb-3">Cold Storage Wallets</h3>
                        <p class="text-slate-400 leading-relaxed">98% of all digital assets on Falcon x are stored
                            offline in multi-signature cold wallets. This means they are physically disconnected from
                            the internet and immune to cyberattacks.</p>
                    </div>
                </div>

                <!-- Feature 2 -->
                <div
                    class="bg-surface-dark p-8 rounded-2xl border border-border-dark flex gap-6 items-start group hover:border-primary/50 transition-all duration-300">
                    <div
                        class="w-16 h-16 rounded-xl bg-primary/10 flex items-center justify-center text-primary shrink-0 group-hover:scale-110 transition-transform">
                        <span class="material-symbols-outlined text-4xl">verified_user</span>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-white mb-3">256-bit Encryption</h3>
                        <p class="text-slate-400 leading-relaxed">All sensitive data, including personal information and
                            API keys, is encrypted using military-grade AES-256 encryption both in transit and at rest.
                        </p>
                    </div>
                </div>

                <!-- Feature 3 -->
                <div
                    class="bg-surface-dark p-8 rounded-2xl border border-border-dark flex gap-6 items-start group hover:border-primary/50 transition-all duration-300">
                    <div
                        class="w-16 h-16 rounded-xl bg-primary/10 flex items-center justify-center text-primary shrink-0 group-hover:scale-110 transition-transform">
                        <span class="material-symbols-outlined text-4xl">security</span>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-white mb-3">DDoS Protection</h3>
                        <p class="text-slate-400 leading-relaxed">Our infrastructure is protected by advanced
                            Distributed Denial of Service (DDoS) mitigation systems that can handle massive traffic
                            spikes without service interruption.</p>
                    </div>
                </div>

                <!-- Feature 4 -->
                <div
                    class="bg-surface-dark p-8 rounded-2xl border border-border-dark flex gap-6 items-start group hover:border-primary/50 transition-all duration-300">
                    <div
                        class="w-16 h-16 rounded-xl bg-primary/10 flex items-center justify-center text-primary shrink-0 group-hover:scale-110 transition-transform">
                        <span class="material-symbols-outlined text-4xl">phonelink_lock</span>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-white mb-3">2-Factor Authentication</h3>
                        <p class="text-slate-400 leading-relaxed">We mandate Two-Factor Authentication (2FA) for all
                            sensitive account actions, including withdrawals and changing passwords, adding an extra
                            layer of security.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Bug Bounty -->
    <section class="py-24 bg-surface-dark border-y border-border-dark">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-3xl font-bold mb-6">White Hat Bug Bounty Program</h2>
            <p class="text-slate-400 max-w-2xl mx-auto mb-10">We work with the world's best ethical hackers to identify
                vulnerabilities before they can be exploited. Found a bug? Report it and get rewarded.</p>
            <a href="{{ route('guest.contact') }}"
                class="inline-flex items-center gap-2 px-8 py-4 bg-background-dark border border-border-dark rounded-xl font-bold text-white hover:bg-border-dark transition-colors">
                <span class="material-symbols-outlined">bug_report</span>
                Report Vulnerability
            </a>
        </div>
    </section>
</x-layouts.guest>