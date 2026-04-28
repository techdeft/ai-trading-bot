<!DOCTYPE html>
<html class="dark" lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>Falcon x | Automate Your Wealth with AI</title>

    <link rel="icon" href="/favicon.ico" sizes="any">
    <link rel="icon" href="/favicon.svg" type="image/svg+xml">
    <link rel="apple-touch-icon" href="/apple-touch-icon.png">
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&amp;display=swap"
        rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght@100..700,0..1&amp;display=swap"
        rel="stylesheet" />
    <link
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap"
        rel="stylesheet" />
    <!-- Lightweight Charts removed from head to be placed in body -->
    <script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        "primary": "#0d7ff2",
                        "background-light": "#f5f7f8",
                        "background-dark": "#101922",
                        "surface-dark": "#182634",
                        "border-dark": "#223649",
                        "success-green": "#10b981",
                        "error-red": "#ef4444"
                    },
                    fontFamily: {
                        "display": ["Inter", "sans-serif"]
                    },
                    borderRadius: { "DEFAULT": "0.25rem", "lg": "0.5rem", "xl": "0.75rem", "full": "9999px" },
                },
            },
        }
    </script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }

        .glass-nav {
            backdrop-filter: blur(12px);
            background-color: rgba(16, 25, 34, 0.8);
        }

        .sparkline-green {
            stroke: #10b981;
            fill: none;
            stroke-width: 2;
        }
    </style>
</head>

<body class="bg-background-light dark:bg-background-dark text-slate-900 dark:text-white antialiased">
    <!-- Navigation Bar -->
    <header class="fixed top-0 w-full z-50 border-b border-border-dark glass-nav" x-data="{ open: false }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center gap-2">
                    <div class="text-primary">
                        <svg class="w-8 h-8" fill="currentColor" viewbox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path d="M12 2L4.5 20.29l.71.71L12 18l6.79 3 .71-.71L12 2z"></path>
                        </svg>
                    </div>
                    <span class="text-xl font-bold tracking-tight">Falcon x</span>
                </div>

                <!-- Desktop Nav -->
                <nav class="hidden md:flex space-x-8">
                    <a class="text-sm font-medium hover:text-primary transition-colors"
                        href="{{ route('home') }}">Home</a>
                    <a class="text-sm font-medium hover:text-primary transition-colors"
                        href="{{ route('guest.performance') }}">Performance</a>
                    <a class="text-sm font-medium hover:text-primary transition-colors"
                        href="{{ route('guest.plans') }}">Plans</a>
                    <a class="text-sm font-medium hover:text-primary transition-colors"
                        href="{{ route('guest.about') }}">About</a>
                    <a class="text-sm font-medium hover:text-primary transition-colors"
                        href="{{ route('guest.faq') }}">FAQ</a>
                    <a class="text-sm font-medium hover:text-primary transition-colors"
                        href="{{ route('guest.contact') }}">Contact</a>
                </nav>

                <div class="flex items-center gap-4">
                    <div class="hidden md:flex items-center gap-4">
                        <a class="text-sm font-medium hover:text-primary hidden sm:block"
                            href="{{ route('login') }}">Login</a>
                        <a href="{{ route('register') }}"
                            class="bg-primary hover:bg-primary/90 text-white px-5 py-2 rounded-lg text-sm font-bold transition-all shadow-lg shadow-primary/20">
                            Get Started
                        </a>
                    </div>

                    <!-- Mobile Menu Button -->
                    <button @click="open = !open" class="md:hidden text-slate-300 hover:text-white focus:outline-none">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path x-show="!open" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6h16M4 12h16M4 18h16"></path>
                            <path x-show="open" x-cloak stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Menu -->
        <div x-show="open" x-cloak
            class="md:hidden bg-background-dark border-b border-border-dark absolute w-full left-0 top-16"
            x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 -translate-y-2"
            x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 -translate-y-2">
            <div class="px-4 pt-2 pb-6 space-y-1">
                <a href="{{ route('home') }}"
                    class="block px-3 py-2 text-base font-medium text-slate-300 hover:text-white hover:bg-surface-dark rounded-md">Home</a>
                <a href="{{ route('guest.performance') }}"
                    class="block px-3 py-2 text-base font-medium text-slate-300 hover:text-white hover:bg-surface-dark rounded-md">Performance</a>
                <a href="{{ route('guest.plans') }}"
                    class="block px-3 py-2 text-base font-medium text-slate-300 hover:text-white hover:bg-surface-dark rounded-md">Plans</a>
                <a href="{{ route('guest.about') }}"
                    class="block px-3 py-2 text-base font-medium text-slate-300 hover:text-white hover:bg-surface-dark rounded-md">About</a>
                <a href="{{ route('guest.faq') }}"
                    class="block px-3 py-2 text-base font-medium text-slate-300 hover:text-white hover:bg-surface-dark rounded-md">FAQ</a>
                <a href="{{ route('guest.contact') }}"
                    class="block px-3 py-2 text-base font-medium text-slate-300 hover:text-white hover:bg-surface-dark rounded-md">Contact</a>
                <div class="pt-4 mt-4 border-t border-border-dark">
                    <a href="{{ route('login') }}"
                        class="block px-3 py-2 text-base font-medium text-slate-300 hover:text-white hover:bg-surface-dark rounded-md">Login</a>
                    <a href="{{ route('register') }}"
                        class="block px-3 py-2 text-base font-medium text-primary hover:text-primary/80 hover:bg-surface-dark rounded-md">Get
                        Started</a>
                </div>
            </div>
        </div>
    </header>
    <main class="pt-16">
        <!-- Hero Section -->
        <section class="relative overflow-hidden py-20 lg:py-32">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
                <div class="grid lg:grid-cols-2 gap-12 items-center">
                    <div class="max-w-2xl">
                        <div
                            class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-primary/10 border border-primary/20 text-primary text-xs font-bold mb-6">
                            <span class="flex h-2 w-2 rounded-full bg-primary animate-pulse"></span>
                            AI TRADING BOT V2.0 IS LIVE
                        </div>
                        <h1 class="text-5xl lg:text-7xl font-black tracking-tight leading-[1.1] mb-6">Automate Your
                            <span class="text-primary">Wealth</span> with AI
                        </h1>
                        <p class="text-lg lg:text-xl text-slate-400 mb-10 leading-relaxed">Stop staring at charts. Let
                            our institutional-grade AI analyze markets 24/7 and execute profitable trades while you
                            sleep.</p>
                        <div class="flex flex-col sm:flex-row gap-4">
                            <button
                                class="bg-primary hover:bg-primary/90 text-white px-8 py-4 rounded-xl text-lg font-bold transition-all flex items-center justify-center gap-2 group">
                                Start AI Bot Now
                                <span
                                    class="material-symbols-outlined group-hover:translate-x-1 transition-transform">smart_toy</span>
                            </button>
                            <a href="{{ route('guest.performance') }}"
                                class="bg-surface-dark border border-border-dark hover:bg-border-dark text-white px-8 py-4 rounded-xl text-lg font-bold transition-all flex items-center justify-center">
                                View Performance
                            </a>
                        </div>
                        <div class="mt-10 flex items-center gap-8 text-slate-500">
                            <div class="flex flex-col">
                                <span class="text-white font-bold text-xl">$4.2M+</span>
                                <span class="text-xs uppercase tracking-widest">AI Profit Generated</span>
                            </div>
                            <div class="w-px h-8 bg-border-dark"></div>
                            <div class="flex flex-col">
                                <span class="text-white font-bold text-xl">2.5K+</span>
                                <span class="text-xs uppercase tracking-widest">Active Bots</span>
                            </div>
                        </div>
                    </div>
                    <div class="relative hidden lg:block">
                        <div class="absolute -top-20 -right-20 w-96 h-96 bg-primary/20 rounded-full blur-[120px]"></div>
                        <div
                            class="relative bg-gradient-to-br from-surface-dark to-background-dark p-1 rounded-2xl border border-border-dark shadow-2xl">
                            <div class="bg-background-dark rounded-xl overflow-hidden aspect-[4/3] relative group">
                                <!-- AI Overlay -->
                                <div
                                    class="absolute top-4 left-4 z-10 flex flex-col gap-2 transition-opacity duration-500">
                                    <div
                                        class="bg-surface-dark/90 backdrop-blur border border-border-dark px-3 py-1.5 rounded-lg flex items-center gap-2 shadow-lg">
                                        <div class="w-2 h-2 rounded-full bg-success-green animate-pulse"></div>
                                        <span class="text-xs font-bold text-white uppercase tracking-wider">AI Agent
                                            Active</span>
                                    </div>
                                    <div
                                        class="bg-surface-dark/90 backdrop-blur border border-border-dark px-3 py-1.5 rounded-lg shadow-lg">
                                        <span class="text-[10px] text-slate-400 uppercase tracking-wider block">Session
                                            Profit</span>
                                        <span class="text-sm font-mono font-bold text-success-green"
                                            id="ai-profit">+$0.00</span>
                                    </div>
                                </div>
                                <div class="absolute bottom-4 right-4 z-10">
                                    <div
                                        class="bg-surface-dark/90 backdrop-blur border border-border-dark px-3 py-1.5 rounded-lg shadow-lg flex items-center gap-2">
                                        <span
                                            class="material-symbols-outlined text-primary text-sm animate-spin">cyclone</span>
                                        <span class="text-xs font-bold text-white" id="ai-status">Scanning
                                            Market...</span>
                                    </div>
                                </div>

                                <!-- Chart Container -->
                                <div id="ai-trading-chart" class="w-full h-full"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- Live Market Data -->
        <section class="py-12 bg-background-dark">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="bg-surface-dark/50 rounded-2xl border border-border-dark overflow-hidden">
                    <div class="p-6 border-b border-border-dark flex justify-between items-center">
                        <h2 class="text-xl font-bold">Market Overview</h2>
                        <a class="text-primary text-sm font-semibold hover:underline" href="#">See all 200+ assets</a>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="text-slate-500 text-sm uppercase tracking-wider">
                                    <th class="px-6 py-4 font-semibold">Asset</th>
                                    <th class="px-6 py-4 font-semibold">Price</th>
                                    <th class="px-6 py-4 font-semibold">24h Change</th>
                                    <th class="px-6 py-4 font-semibold hidden md:table-cell">Last 7 Days</th>
                                    <th class="px-6 py-4 font-semibold text-right">Action</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-border-dark">
                                <!-- BTC Row -->
                                <tr class="hover:bg-primary/5 transition-colors">
                                    <td class="px-6 py-5">
                                        <div class="flex items-center gap-3">
                                            <div
                                                class="w-10 h-10 rounded-full bg-orange-500/20 flex items-center justify-center text-orange-500">
                                                <span class="material-symbols-outlined">currency_bitcoin</span>
                                            </div>
                                            <div>
                                                <p class="font-bold">Bitcoin</p>
                                                <p class="text-xs text-slate-500">BTC</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-5 font-mono text-lg">$64,230.15</td>
                                    <td class="px-6 py-5">
                                        <span
                                            class="inline-flex items-center px-2 py-1 rounded bg-success-green/10 text-success-green text-sm font-bold">
                                            +2.45%
                                        </span>
                                    </td>
                                    <td class="px-6 py-5 hidden md:table-cell">
                                        <svg class="w-32 h-10" viewbox="0 0 100 30">
                                            <path class="sparkline-green"
                                                d="M0 25 Q 10 20, 20 22 T 40 15 T 60 18 T 80 5 T 100 10"></path>
                                        </svg>
                                    </td>
                                    <td class="px-6 py-5 text-right">
                                        <button
                                            class="bg-white/5 hover:bg-white/10 px-4 py-2 rounded-lg text-sm font-bold">Trade</button>
                                    </td>
                                </tr>
                                <!-- TSLA Row -->
                                <tr class="hover:bg-primary/5 transition-colors">
                                    <td class="px-6 py-5">
                                        <div class="flex items-center gap-3">
                                            <div
                                                class="w-10 h-10 rounded-full bg-red-500/20 flex items-center justify-center text-red-500">
                                                <span class="material-symbols-outlined">show_chart</span>
                                            </div>
                                            <div>
                                                <p class="font-bold">Tesla Inc.</p>
                                                <p class="text-xs text-slate-500">TSLA</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-5 font-mono text-lg">$175.22</td>
                                    <td class="px-6 py-5">
                                        <span
                                            class="inline-flex items-center px-2 py-1 rounded bg-success-green/10 text-success-green text-sm font-bold">
                                            +1.85%
                                        </span>
                                    </td>
                                    <td class="px-6 py-5 hidden md:table-cell">
                                        <svg class="w-32 h-10" viewbox="0 0 100 30">
                                            <path class="sparkline-green"
                                                d="M0 20 Q 20 15, 40 18 T 60 10 T 80 12 T 100 2"></path>
                                        </svg>
                                    </td>
                                    <td class="px-6 py-5 text-right">
                                        <button
                                            class="bg-white/5 hover:bg-white/10 px-4 py-2 rounded-lg text-sm font-bold">Trade</button>
                                    </td>
                                </tr>
                                <!-- ETH Row -->
                                <tr class="hover:bg-primary/5 transition-colors">
                                    <td class="px-6 py-5">
                                        <div class="flex items-center gap-3">
                                            <div
                                                class="w-10 h-10 rounded-full bg-indigo-500/20 flex items-center justify-center text-indigo-500">
                                                <span class="material-symbols-outlined">diamond</span>
                                            </div>
                                            <div>
                                                <p class="font-bold">Ethereum</p>
                                                <p class="text-xs text-slate-500">ETH</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-5 font-mono text-lg">$3,450.12</td>
                                    <td class="px-6 py-5">
                                        <span
                                            class="inline-flex items-center px-2 py-1 rounded bg-success-green/10 text-success-green text-sm font-bold">
                                            +1.12%
                                        </span>
                                    </td>
                                    <td class="px-6 py-5 hidden md:table-cell">
                                        <svg class="w-32 h-10" viewbox="0 0 100 30">
                                            <path class="sparkline-green"
                                                d="M0 20 Q 20 25, 40 10 T 60 15 T 80 12 T 100 5"></path>
                                        </svg>
                                    </td>
                                    <td class="px-6 py-5 text-right">
                                        <button
                                            class="bg-white/5 hover:bg-white/10 px-4 py-2 rounded-lg text-sm font-bold">Trade</button>
                                    </td>
                                </tr>
                                <!-- BYD Row -->
                                <tr class="hover:bg-primary/5 transition-colors">
                                    <td class="px-6 py-5">
                                        <div class="flex items-center gap-3">
                                            <div
                                                class="w-10 h-10 rounded-full bg-blue-500/20 flex items-center justify-center text-blue-500">
                                                <span class="material-symbols-outlined">directions_car</span>
                                            </div>
                                            <div>
                                                <p class="font-bold">BYD Company</p>
                                                <p class="text-xs text-slate-500">BYDDF</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-5 font-mono text-lg">$28.45</td>
                                    <td class="px-6 py-5">
                                        <span
                                            class="inline-flex items-center px-2 py-1 rounded bg-success-green/10 text-success-green text-sm font-bold">
                                            +3.12%
                                        </span>
                                    </td>
                                    <td class="px-6 py-5 hidden md:table-cell">
                                        <svg class="w-32 h-10" viewbox="0 0 100 30">
                                            <path class="sparkline-green"
                                                d="M0 28 Q 15 25, 30 20 T 50 15 T 70 10 T 90 12 T 100 5"></path>
                                        </svg>
                                    </td>
                                    <td class="px-6 py-5 text-right">
                                        <button
                                            class="bg-white/5 hover:bg-white/10 px-4 py-2 rounded-lg text-sm font-bold">Trade</button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </section>

        <!-- About Section -->
        <section class="py-24 bg-surface-dark border-y border-border-dark">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid lg:grid-cols-2 gap-16 items-center">
                    <div>
                        <div
                            class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-error-red/10 border border-error-red/20 text-error-red text-xs font-bold mb-6">
                            <span class="material-symbols-outlined text-sm">warning</span>
                            THE PROBLEM
                        </div>
                        <h2 class="text-3xl lg:text-5xl font-bold mb-6">Manual Trading is <br><span
                                class="text-error-red">Broken</span></h2>
                        <div class="space-y-6 text-slate-400 text-lg">
                            <p>Statistics show that <strong class="text-white">95% of day traders lose money</strong>.
                                Why? Because humans are emotional, need sleep, and can't process millions of data points
                                effectively.</p>
                            <p>FOMO, panic selling, and fatigue are the enemies of profit. While you sleep, the market
                                moves, and opportunities are lost forever.</p>
                        </div>
                    </div>
                    <div>
                        <div
                            class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-success-green/10 border border-success-green/20 text-success-green text-xs font-bold mb-6">
                            <span class="material-symbols-outlined text-sm">check_circle</span>
                            THE SOLUTION
                        </div>
                        <h2 class="text-3xl lg:text-5xl font-bold mb-6">Falcon X AI is <br><span
                                class="text-success-green">Flawless</span></h2>
                        <div class="space-y-6 text-slate-400 text-lg">
                            <p>Our AI never sleeps, has no emotions, and executes trades in milliseconds. It processes
                                news, sentiment, and technical indicators instantly.</p>
                            <ul class="space-y-4 mt-8">
                                <li class="flex items-center gap-3">
                                    <span class="material-symbols-outlined text-success-green">bolt</span>
                                    <span class="text-white">Instant Execution (0.05ms)</span>
                                </li>
                                <li class="flex items-center gap-3">
                                    <span class="material-symbols-outlined text-success-green">data_usage</span>
                                    <span class="text-white">Analyzes 50,000+ Data Points/Sec</span>
                                </li>
                                <li class="flex items-center gap-3">
                                    <span class="material-symbols-outlined text-success-green">savings</span>
                                    <span class="text-white">Consistent Daily Compounding</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- Features Section -->
        <section class="py-24 relative">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-16">
                    <h2 class="text-3xl lg:text-5xl font-bold mb-4">Why Choose AI Trading?</h2>
                    <p class="text-slate-400 max-w-2xl mx-auto">Eliminate emotional trading errors. Our algorithms
                        process market data 24/7 to find opportunities you'd miss.</p>
                </div>
                <div class="grid md:grid-cols-3 gap-8">
                    <div
                        class="bg-surface-dark p-8 rounded-2xl border border-border-dark group hover:border-primary/50 transition-all duration-300">
                        <div
                            class="w-14 h-14 bg-primary/10 rounded-xl flex items-center justify-center text-primary mb-6 group-hover:scale-110 transition-transform">
                            <span class="material-symbols-outlined text-3xl">psychology</span>
                        </div>
                        <h3 class="text-xl font-bold mb-4">Smart Algorithms</h3>
                        <p class="text-slate-400 leading-relaxed">Our AI models adapt to changing market conditions in
                            real-time, using machine learning to predict price movements with high accuracy.</p>
                    </div>
                    <div
                        class="bg-surface-dark p-8 rounded-2xl border border-border-dark group hover:border-primary/50 transition-all duration-300">
                        <div
                            class="w-14 h-14 bg-primary/10 rounded-xl flex items-center justify-center text-primary mb-6 group-hover:scale-110 transition-transform">
                            <span class="material-symbols-outlined text-3xl">schedule</span>
                        </div>
                        <h3 class="text-xl font-bold mb-4">24/7 Automation</h3>
                        <p class="text-slate-400 leading-relaxed">Crypto markets never sleep using our automated bots.
                            Your portfolio grows day and night, without you lifting a finger.</p>
                    </div>
                    <div
                        class="bg-surface-dark p-8 rounded-2xl border border-border-dark group hover:border-primary/50 transition-all duration-300">
                        <div
                            class="w-14 h-14 bg-primary/10 rounded-xl flex items-center justify-center text-primary mb-6 group-hover:scale-110 transition-transform">
                            <span class="material-symbols-outlined text-3xl">shield</span>
                        </div>
                        <h3 class="text-xl font-bold mb-4">Advanced Risk Management</h3>
                        <p class="text-slate-400 leading-relaxed">Built-in stop-loss and trailing-profit mechanisms
                            protect your capital while maximizing gains during bull runs.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- How It Works Section -->
        <!-- How It Works Section -->
        <section class="py-24 bg-surface-dark/50 border-t border-border-dark relative overflow-hidden">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
                <div class="text-center mb-16">
                    <h2 class="text-3xl lg:text-5xl font-bold mb-6">Start Earning in 3 Simple Steps</h2>
                    <p class="text-slate-400 max-w-2xl mx-auto text-lg">No trading experience required. Our system
                        handles everything.</p>
                </div>

                <div class="grid md:grid-cols-3 gap-8 text-center">
                    <!-- Step 1 -->
                    <div class="p-6">
                        <div
                            class="w-20 h-20 mx-auto bg-surface-dark border-2 border-border-dark rounded-full flex items-center justify-center mb-6 shadow-lg shadow-black/20">
                            <span class="text-2xl font-black text-slate-500">1</span>
                        </div>
                        <h3 class="text-xl font-bold mb-4">Create Free Account</h3>
                        <p class="text-slate-400 leading-relaxed">Sign up in seconds. No credit card required to explore
                            the platform.</p>
                    </div>

                    <!-- Step 2 -->
                    <div class="p-6">
                        <div
                            class="w-20 h-20 mx-auto bg-surface-dark border-2 border-border-dark rounded-full flex items-center justify-center mb-6 shadow-lg shadow-black/20">
                            <span class="text-2xl font-black text-slate-500">2</span>
                        </div>
                        <h3 class="text-xl font-bold mb-4">Fund Your Wallet</h3>
                        <p class="text-slate-400 leading-relaxed">Deposit Crypto or Link Bank Account. Funds are stored
                            in cold wallets.</p>
                    </div>

                    <!-- Step 3 -->
                    <div class="p-6">
                        <div
                            class="w-20 h-20 mx-auto bg-primary border-4 border-primary/20 rounded-full flex items-center justify-center mb-6 shadow-lg shadow-primary/20">
                            <span class="material-symbols-outlined text-3xl text-white">power_settings_new</span>
                        </div>
                        <h3 class="text-xl font-bold mb-4 text-white">Activate AI Bot</h3>
                        <p class="text-slate-400 leading-relaxed">Select a strategy and click Start. Watch profits grow
                            in real-time.</p>
                    </div>
                </div>
            </div>
        </section>

        <section class="py-24 bg-surface-dark/30">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-16">
                    <h2 class="text-3xl lg:text-4xl font-bold mb-4">Passive Income Stories</h2>
                </div>
                <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                    <div class="bg-surface-dark p-8 rounded-2xl border border-border-dark">
                        <div class="flex gap-1 text-yellow-500 mb-4">
                            <span class="material-symbols-outlined">star</span><span
                                class="material-symbols-outlined">star</span><span
                                class="material-symbols-outlined">star</span><span
                                class="material-symbols-outlined">star</span><span
                                class="material-symbols-outlined">star</span>
                        </div>
                        <p class="text-slate-300 mb-6 italic">"I was skeptical about AI bots, but this one generated
                            consistent daily profits even when I was on vacation. It literally pays for my travel now."
                        </p>
                        <div class="flex items-center gap-4">
                            <div
                                class="w-12 h-12 rounded-full bg-primary/20 flex items-center justify-center font-bold text-primary">
                                JD</div>
                            <div>
                                <p class="font-bold text-white">James D.</p>
                                <p class="text-xs text-slate-500">Early Adopter</p>
                            </div>
                        </div>
                    </div>
                    <div class="bg-surface-dark p-8 rounded-2xl border border-border-dark">
                        <div class="flex gap-1 text-yellow-500 mb-4">
                            <span class="material-symbols-outlined">star</span><span
                                class="material-symbols-outlined">star</span><span
                                class="material-symbols-outlined">star</span><span
                                class="material-symbols-outlined">star</span><span
                                class="material-symbols-outlined">star</span>
                        </div>
                        <p class="text-slate-300 mb-6 italic">"The risk management is what impressed me. It exited
                            trades before the crash last week, saving me thousands. Manual trading never again."</p>
                        <div class="flex items-center gap-4">
                            <div
                                class="w-12 h-12 rounded-full bg-primary/20 flex items-center justify-center font-bold text-primary">
                                SR</div>
                            <div>
                                <p class="font-bold text-white">Sarah R.</p>
                                <p class="text-xs text-slate-500">Verified User</p>
                            </div>
                        </div>
                    </div>
                    <div class="bg-surface-dark p-8 rounded-2xl border border-border-dark">
                        <div class="flex gap-1 text-yellow-500 mb-4">
                            <span class="material-symbols-outlined">star</span><span
                                class="material-symbols-outlined">star</span><span
                                class="material-symbols-outlined">star</span><span
                                class="material-symbols-outlined">star</span><span
                                class="material-symbols-outlined">star</span>
                        </div>
                        <p class="text-slate-300 mb-6 italic">"Setup was super easy. I deposited USDT, clicked start,
                            and saw green notifications within an hour. The dashboard is unmatched."</p>
                        <div class="flex items-center gap-4">
                            <div
                                class="w-12 h-12 rounded-full bg-primary/20 flex items-center justify-center font-bold text-primary">
                                MK</div>
                            <div>
                                <p class="font-bold text-white">Michael K.</p>
                                <p class="text-xs text-slate-500">Crypto Investor</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- CTA Banner -->
        <section class="py-20 px-4">
            <div class="max-w-7xl mx-auto">
                <div class="bg-primary rounded-[2rem] p-8 lg:p-16 relative overflow-hidden text-center lg:text-left">
                    <div class="absolute top-0 right-0 w-1/2 h-full bg-white/5 skew-x-[-20deg] translate-x-1/2"></div>
                    <div class="relative z-10 grid lg:grid-cols-2 gap-12 items-center">
                        <div>
                            <h2 class="text-3xl lg:text-5xl font-black text-white mb-6">Start Your Passive Income
                                Journey
                            </h2>
                            <p class="text-white/80 text-lg lg:text-xl mb-0">Join 10M+ users who are automating their
                                Trades. Set up your AI Trading Bot in under 5 minutes.</p>
                        </div>
                        <div class="flex flex-col sm:flex-row gap-4 lg:justify-end">
                            <button
                                class="bg-white text-primary px-10 py-5 rounded-xl text-lg font-bold hover:bg-slate-50 transition-all shadow-xl">
                                Create Free Bot
                            </button>
                            <button
                                class="bg-primary-dark/20 border border-white/30 text-white px-10 py-5 rounded-xl text-lg font-bold hover:bg-white/10 transition-all">
                                View Help Center
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>
    <!-- Footer -->
    <x-guest.footer />

    <script
        src="https://cdn.jsdelivr.net/npm/lightweight-charts@4.1.1/dist/lightweight-charts.standalone.production.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            if (!window.LightweightCharts) {
                console.error('LightweightCharts library not loaded!');
                document.getElementById('ai-status').textContent = 'Error loading chart...';
                return;
            }

            const chartOptions = {
                layout: {
                    textColor: '#94a3b8',
                    background: { type: 'solid', color: '#101922' },
                },
                grid: {
                    vertLines: { color: '#1e293b' },
                    horzLines: { color: '#1e293b' },
                },
                timeScale: {
                    timeVisible: true,
                    secondsVisible: false,
                    borderVisible: false,
                },
                rightPriceScale: {
                    borderVisible: false,
                },
                crosshair: {
                    mode: LightweightCharts.CrosshairMode.Normal,
                },
            };

            const container = document.getElementById('ai-trading-chart');
            if (!container) return;

            const chart = LightweightCharts.createChart(container, chartOptions);

            const candleSeries = chart.addCandlestickSeries({
                upColor: '#10b981',
                downColor: '#ef4444',
                borderVisible: false,
                wickUpColor: '#10b981',
                wickDownColor: '#ef4444',
            });

            // Generate initial data
            let data = [];
            let time = Math.floor(Date.now() / 1000) - 10000;
            let price = 64000;

            for (let i = 0; i < 100; i++) {
                time += 60;
                let change = (Math.random() - 0.5) * 50;
                let open = price;
                let close = price + change;
                let high = Math.max(open, close) + Math.random() * 10;
                let low = Math.min(open, close) - Math.random() * 10;
                price = close;

                data.push({ time, open, high, low, close });
            }

            candleSeries.setData(data);
            chart.timeScale().fitContent();

            // Simulation Loop
            let lastClose = data[data.length - 1].close;
            let lastTime = data[data.length - 1].time;
            let sessionProfit = 0;
            let position = null; // 'buy' or null
            let entryPrice = 0;

            const updateChart = () => {
                lastTime += 5; // Fake 5s candles for speed
                let change = (Math.random() - 0.5) * 30;

                // Trend bias if in position to simulate AI winning
                if (position === 'buy') {
                    change += (Math.random() * 20);
                }

                let open = lastClose;
                let close = lastClose + change;
                let high = Math.max(open, close) + Math.random() * 5;
                let low = Math.min(open, close) - Math.random() * 5;
                lastClose = close;

                const candle = { time: lastTime, open, high, low, close };
                candleSeries.update(candle);
            };

            // Force start if library loaded
            const statusEl = document.getElementById('ai-status');
            statusEl.textContent = 'Scanning Market...';
            statusEl.className = 'text-xs font-bold text-slate-400';

            const aiLogic = () => {
                const profitEl = document.getElementById('ai-profit');
                const markers = candleSeries.markers();

                if (!position) {
                    // Try to buy - Aggressive Mode (90% chance)
                    if (Math.random() > 0.1) {
                        position = 'buy';
                        entryPrice = lastClose;
                        statusEl.textContent = 'Buying...';
                        statusEl.className = 'text-xs font-bold text-success-green animate-pulse';

                        const newMarker = {
                            time: lastTime,
                            position: 'belowBar',
                            color: '#10b981',
                            shape: 'arrowUp',
                            text: 'BUY @ ' + lastClose.toFixed(2),
                        };
                        candleSeries.setMarkers([...markers, newMarker]);
                    } else {
                        statusEl.textContent = 'Scanning Market...';
                        statusEl.className = 'text-xs font-bold text-slate-400';
                    }
                } else {
                    // Try to sell (take profit)
                    // Scalping: Sell quickly if any profit > $2
                    const currentProfit = lastClose - entryPrice;

                    if (currentProfit > 2 || (currentProfit > -5 && Math.random() > 0.2)) {
                        position = null;
                        sessionProfit += currentProfit;
                        profitEl.textContent = '+$' + sessionProfit.toFixed(2);

                        statusEl.textContent = 'Sold (Profit)';
                        statusEl.className = 'text-xs font-bold text-primary';

                        const newMarker = {
                            time: lastTime,
                            position: 'aboveBar',
                            color: '#ef4444',
                            shape: 'arrowDown',
                            text: 'SELL (+$' + currentProfit.toFixed(2) + ')',
                        };
                        candleSeries.setMarkers([...markers, newMarker]);
                    }
                }
            };

            setInterval(updateChart, 100);
            setInterval(aiLogic, 1000); // 1s interval

            // Initial Force Buy after 2 seconds to ensure user sees something
            setTimeout(() => {
                if (!position) {
                    position = 'buy';
                    entryPrice = lastClose;
                    statusEl.textContent = 'Buying...';
                    statusEl.className = 'text-xs font-bold text-success-green animate-pulse';
                    const newMarker = {
                        time: lastTime,
                        position: 'belowBar',
                        color: '#10b981',
                        shape: 'arrowUp',
                        text: 'BUY @ ' + lastClose.toFixed(2),
                    };
                    candleSeries.setMarkers([...candleSeries.markers(), newMarker]);
                }
            }, 2000);

            // Resize handler
            window.addEventListener('resize', () => {
                chart.resize(container.clientWidth, container.clientHeight);
            });
        });
    </script>
</body>

</html>