<x-layouts.guest>
    <x-slot name="title">FAQ | Falcon x</x-slot>

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
        <div class="text-center mb-16">
            <h1 class="text-4xl font-black mb-4">Frequently Asked Questions</h1>
            <p class="text-slate-400">Everything you need to know about Falcon x and our AI trading bots.</p>
        </div>

        <div class="space-y-6" x-data="{ active: null }">
            <!-- FAQ Item 1 -->
            <div class="bg-surface-dark border border-border-dark rounded-xl overflow-hidden">
                <button @click="active = active === 1 ? null : 1"
                    class="w-full flex justify-between items-center p-6 text-left">
                    <span class="font-bold text-lg text-white">How does the AI Trading Bot work?</span>
                    <span class="material-symbols-outlined transition-transform duration-300"
                        :class="active === 1 ? 'rotate-180 text-primary' : 'text-slate-500'">expand_more</span>
                </button>
                <div x-show="active === 1" x-collapse>
                    <div class="px-6 pb-6 text-slate-400 leading-relaxed">
                        Our AI analyzes market data from over 20 top exchanges in real-time. It uses machine learning
                        algorithms to identify profitable entry and exit points, executing trades automatically on your
                        behalf to maximize returns while minimizing risk.
                    </div>
                </div>
            </div>

            <!-- FAQ Item 2 -->
            <div class="bg-surface-dark border border-border-dark rounded-xl overflow-hidden">
                <button @click="active = active === 2 ? null : 2"
                    class="w-full flex justify-between items-center p-6 text-left">
                    <span class="font-bold text-lg text-white">Is my investment safe?</span>
                    <span class="material-symbols-outlined transition-transform duration-300"
                        :class="active === 2 ? 'rotate-180 text-primary' : 'text-slate-500'">expand_more</span>
                </button>
                <div x-show="active === 2" x-collapse>
                    <div class="px-6 pb-6 text-slate-400 leading-relaxed">
                        Yes. We use military-grade encryption to protect your data. Furthermore, 98% of user funds are
                        stored in offline cold wallets to prevent hacking. Our risk management AI also uses stop-loss
                        mechanisms to protect capital during market volatility.
                    </div>
                </div>
            </div>

            <!-- FAQ Item 3 -->
            <div class="bg-surface-dark border border-border-dark rounded-xl overflow-hidden">
                <button @click="active = active === 3 ? null : 3"
                    class="w-full flex justify-between items-center p-6 text-left">
                    <span class="font-bold text-lg text-white">What is the minimum deposit?</span>
                    <span class="material-symbols-outlined transition-transform duration-300"
                        :class="active === 3 ? 'rotate-180 text-primary' : 'text-slate-500'">expand_more</span>
                </button>
                <div x-show="active === 3" x-collapse>
                    <div class="px-6 pb-6 text-slate-400 leading-relaxed">
                        The minimum deposit depends on the plan you choose. Our Starter plan typically begins at $100.
                        Please check our <a href="{{ route('guest.plans') }}"
                            class="text-primary hover:underline">Investment Plans</a> page for specific details on each
                        tier.
                    </div>
                </div>
            </div>

            <!-- FAQ Item 4 -->
            <div class="bg-surface-dark border border-border-dark rounded-xl overflow-hidden">
                <button @click="active = active === 4 ? null : 4"
                    class="w-full flex justify-between items-center p-6 text-left">
                    <span class="font-bold text-lg text-white">When can I withdraw my profits?</span>
                    <span class="material-symbols-outlined transition-transform duration-300"
                        :class="active === 4 ? 'rotate-180 text-primary' : 'text-slate-500'">expand_more</span>
                </button>
                <div x-show="active === 4" x-collapse>
                    <div class="px-6 pb-6 text-slate-400 leading-relaxed">
                        You can withdraw your profits instantly once your bot plan duration is complete. Referrals
                        commissions can be withdrawn at any time. Withdrawals are processed automatically and typically
                        reach your wallet within minutes.
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.guest>