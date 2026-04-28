<x-layouts.guest>
    <x-slot name="title">Investment Plans | Falcon x</x-slot>

    <!-- Header -->
    <section class="relative py-20 overflow-hidden">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <div
                class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-primary/10 border border-primary/20 text-primary text-xs font-bold mb-6">
                <span class="material-symbols-outlined text-sm">rocket_launch</span>
                START EARNING TODAY
            </div>
            <h1 class="text-3xl md:text-5xl font-black mb-6">Choose Your <span class="text-primary">Wealth Path</span>
            </h1>
            <p class="text-lg text-slate-400 max-w-2xl mx-auto">Select an AI trading strategy that fits your budget and
                goals. All plans include 24/7 automated trading and instant withdrawals.</p>
        </div>
    </section>

    <!-- Plans Grid -->
    <section class="pb-24 px-4">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach(\App\Models\BotPlan::all() as $plan)
                    <div
                        class="bg-surface-dark rounded-2xl border border-border-dark overflow-hidden hover:border-primary/50 transition-all duration-300 relative group">
                        <div class="p-8">
                            <h3 class="text-2xl font-bold text-white mb-2">{{ $plan->name }}</h3>
                            <p class="text-slate-400 text-sm h-10 line-clamp-2 mb-6">{{ $plan->description }}</p>

                            <div class="flex items-baseline gap-1 mb-8">
                                <span class="text-4xl font-black text-primary">{{ $plan->roi_per_trade }}%</span>
                                <span class="text-slate-500 font-bold">ROI / Trade</span>
                            </div>

                            <ul class="space-y-4 mb-8">
                                <li class="flex items-center gap-3">
                                    <span class="material-symbols-outlined text-success-green">check_circle</span>
                                    <span class="text-slate-300">Min Investment: <span
                                            class="text-white font-bold">${{ number_format($plan->min_amount) }}</span></span>
                                </li>
                                <li class="flex items-center gap-3">
                                    <span class="material-symbols-outlined text-success-green">check_circle</span>
                                    <span class="text-slate-300">Max Investment: <span
                                            class="text-white font-bold">${{ number_format($plan->max_amount) }}</span></span>
                                </li>
                                <li class="flex items-center gap-3">
                                    <span class="material-symbols-outlined text-success-green">check_circle</span>
                                    <span class="text-slate-300">Duration: <span
                                            class="text-white font-bold">{{ $plan->duration_days }} Days</span></span>
                                </li>
                                <li class="flex items-center gap-3">
                                    <span class="material-symbols-outlined text-success-green">check_circle</span>
                                    <span class="text-slate-300">Total Trades: <span
                                            class="text-white font-bold">{{ $plan->trades_count }}</span></span>
                                </li>
                            </ul>

                            <a href="{{ route('register') }}"
                                class="block w-full py-4 rounded-xl bg-white text-background-dark font-bold text-center hover:bg-slate-200 transition-colors">
                                Choose Plan
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- FAQ CTA -->
    <section class="py-24 bg-surface-dark border-t border-border-dark">
        <div class="max-w-4xl mx-auto px-4 text-center">
            <h2 class="text-3xl font-bold mb-6">Have Questions?</h2>
            <p class="text-slate-400 mb-8">Not sure which plan is right for you? Check out our frequently asked
                questions or contact our support team.</p>
            <div class="flex justify-center gap-4">
                <a href="{{ route('guest.faq') }}"
                    class="px-6 py-3 rounded-lg bg-surface-dark border border-border-dark hover:bg-border-dark transition-colors font-bold text-white">Visit
                    FAQ</a>
                <a href="{{ route('guest.contact') }}"
                    class="px-6 py-3 rounded-lg bg-primary hover:bg-primary/90 transition-colors font-bold text-white">Contact
                    Support</a>
            </div>
        </div>
    </section>
</x-layouts.guest>