<!DOCTYPE html>

<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
<meta charset="utf-8" />
<meta content="width=device-width, initial-scale=1.0" name="viewport" />
<title>{{ config('app.name', 'Laravel') }}</title>
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&amp;display=swap"
    rel="stylesheet" />
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap"
    rel="stylesheet" />
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap"
    rel="stylesheet" />
<script id="tailwind-config">
    tailwind.config = {
        darkMode: "class",
        theme: {
            extend: {
                colors: {
                    "primary": "#0d7ff2",
                    "background-light": "#f5f7f8",
                    "background-dark": "#101922",
                    "surface": "#1a2632",
                    "surface-accent": "#223649",
                    "success": "#0bda5b",
                    "danger": "#ef4444"
                },
                fontFamily: {
                    "display": ["Inter"]
                },
                borderRadius: { "DEFAULT": "0.25rem", "lg": "0.5rem", "xl": "0.75rem", "full": "9999px" },
            },
        },
    }
</script>
<style>
    body {
        font-family: 'Inter', sans-serif;
    }

    .no-scrollbar::-webkit-scrollbar {
        display: none;
    }

    .no-scrollbar {
        -ms-overflow-style: none;
        scrollbar-width: none;
    }

    /* Custom Doughnut Chart Simulation */
    .doughnut-container {
        position: relative;
        width: 180px;
        height: 180px;
        border-radius: 50%;
        background: conic-gradient(#0d7ff2 0% 45%,
                #6366f1 45% 70%,
                #a855f7 70% 85%,
                #223649 85% 100%);
    }

    .doughnut-hole {
        position: absolute;
        top: 20%;
        left: 20%;
        width: 60%;
        height: 60%;
        background-color: #1a2632;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
    }
</style>
</head>

<body class="bg-background-light dark:bg-background-dark text-slate-900 dark:text-slate-100 antialiased min-h-screen">
    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar -->
        <aside
            class="w-64 flex-shrink-0 bg-surface dark:bg-background-dark border-r border-slate-200 dark:border-slate-800 hidden md:flex flex-col relative z-50">
            <div class="p-6">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-lg bg-primary flex items-center justify-center text-white">
                        <span class="material-symbols-outlined">currency_bitcoin</span>
                    </div>
                    <div>
                        <h1 class="font-bold text-lg tracking-tight">Falcon x</h1>
                        <p class="text-xs text-slate-500 dark:text-slate-400">Institutional Terminal</p>
                    </div>
                </div>
            </div>
            <nav class="flex-1 px-4 space-y-1">
                <a class="flex items-center gap-3 px-3 py-2 rounded-lg transition-colors {{ request()->routeIs('dashboard') ? 'bg-primary/10 text-primary' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-surface-accent' }}"
                    href="{{ route('dashboard') }}">
                    <span class="material-symbols-outlined">dashboard</span>
                    <span class="text-sm font-medium">Dashboard</span>
                </a>
                <a class="flex items-center gap-3 px-3 py-2 rounded-lg transition-colors {{ request()->routeIs('portfolio') ? 'bg-primary/10 text-primary' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-surface-accent' }}"
                    href="{{ route('portfolio') }}">
                    <span class="material-symbols-outlined leading-none"
                        style="font-variation-settings: 'FILL' 1">account_balance_wallet</span>
                    <span class="text-sm font-medium">Portfolio</span>
                </a>
                <a class="flex items-center gap-3 px-3 py-2 rounded-lg transition-colors {{ request()->routeIs('wallet') ? 'bg-primary/10 text-primary' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-surface-accent' }}"
                    href="{{ route('wallet') }}">
                    <span class="material-symbols-outlined">account_balance_wallet</span>
                    <span class="text-sm font-medium">Wallet</span>
                </a>
                <a class="flex items-center gap-3 px-3 py-2 rounded-lg transition-colors {{ request()->routeIs('market') ? 'bg-primary/10 text-primary' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-surface-accent' }}"
                    href="{{ route('market') }}">
                    <span class="material-symbols-outlined">monitoring</span>
                    <span class="text-sm font-medium">Market</span>
                </a>
                <a class="flex items-center gap-3 px-3 py-2 rounded-lg transition-colors {{ request()->routeIs('trade') ? 'bg-primary/10 text-primary' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-surface-accent' }}"
                    href="{{ route('trade') }}">
                    <span class="material-symbols-outlined">swap_horizontal_circle</span>
                    <span class="text-sm font-medium">Trade</span>
                </a>
                <a class="flex items-center gap-3 px-3 py-2 rounded-lg transition-colors {{ request()->routeIs('bot-plans') ? 'bg-primary/10 text-primary' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-surface-accent' }}"
                    href="{{ route('bot-plans') }}">
                    <span class="material-symbols-outlined">smart_toy</span>
                    <span class="text-sm font-medium">AI Bots</span>
                </a>
                <a class="flex items-center gap-3 px-3 py-2 rounded-lg transition-colors {{ request()->routeIs('history') ? 'bg-primary/10 text-primary' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-surface-accent' }}"
                    href="{{ route('history') }}">
                    <span class="material-symbols-outlined">history</span>
                    <span class="text-sm font-medium">Activity</span>
                </a>
                <a class="flex items-center gap-3 px-3 py-2 rounded-lg transition-colors {{ request()->routeIs('kyc.verification') ? 'bg-primary/10 text-primary' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-surface-accent' }}"
                    href="{{ route('kyc.verification') }}">
                    <span class="material-symbols-outlined">verified_user</span>
                    <span class="text-sm font-medium">Verify Identity</span>
                </a>

                @if(auth()->user()->is_admin)
                    <div class="px-3 py-2 mt-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">
                        Admin
                    </div>
                    <a class="flex items-center gap-3 px-3 py-2 rounded-lg transition-colors {{ request()->routeIs('admin.users') ? 'bg-primary/10 text-primary' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-surface-accent' }}"
                        href="{{ route('admin.users') }}">
                        <span class="material-symbols-outlined">group</span>
                        <span class="text-sm font-medium">Users</span>
                    </a>
                    <a class="flex items-center gap-3 px-3 py-2 rounded-lg transition-colors {{ request()->routeIs('admin.wallets') ? 'bg-primary/10 text-primary' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-surface-accent' }}"
                        href="{{ route('admin.wallets') }}">
                        <span class="material-symbols-outlined">account_balance_wallet</span>
                        <span class="text-sm font-medium">Wallets</span>
                    </a>
                    <a class="flex items-center gap-3 px-3 py-2 rounded-lg transition-colors {{ request()->routeIs('admin.withdrawals') ? 'bg-primary/10 text-primary' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-surface-accent' }}"
                        href="{{ route('admin.withdrawals') }}">
                        <span class="material-symbols-outlined">payments</span>
                        <span class="text-sm font-medium">Withdrawals</span>
                    </a>
                    <a class="flex items-center gap-3 px-3 py-2 rounded-lg transition-colors {{ request()->routeIs('admin.deposits') ? 'bg-primary/10 text-primary' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-surface-accent' }}"
                        href="{{ route('admin.deposits') }}">
                        <span class="material-symbols-outlined">account_balance</span>
                        <span class="text-sm font-medium">Deposits</span>
                    </a>
                    <a class="flex items-center gap-3 px-3 py-2 rounded-lg transition-colors {{ request()->routeIs('admin.kyc.review') ? 'bg-primary/10 text-primary' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-surface-accent' }}"
                        href="{{ route('admin.kyc.review') }}">
                        <span class="material-symbols-outlined">verified_user</span>
                        <span class="text-sm font-medium">KYC Review</span>
                    </a>
                    <a class="flex items-center gap-3 px-3 py-2 rounded-lg transition-colors {{ request()->routeIs('admin.bot-plans') ? 'bg-primary/10 text-primary' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-surface-accent' }}"
                        href="{{ route('admin.bot-plans') }}">
                        <span class="material-symbols-outlined">smart_toy</span>
                        <span class="text-sm font-medium">Bot Plans</span>
                    </a>
                    <a class="flex items-center gap-3 px-3 py-2 rounded-lg transition-colors {{ request()->routeIs('admin.setup-guide') ? 'bg-primary/10 text-primary' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-surface-accent' }}"
                        href="{{ route('admin.setup-guide') }}">
                        <span class="material-symbols-outlined">description</span>
                        <span class="text-sm font-medium">Setup Guide</span>
                    </a>
                @endif
            </nav>
            <div class="p-4 border-t border-slate-200 dark:border-slate-800 space-y-2">
                <a href="{{ route('profile.edit') }}"
                    class="flex items-center gap-3 p-2 rounded-lg hover:bg-slate-100 dark:hover:bg-surface-accent cursor-pointer transition-colors group">
                    <div class="w-10 h-10 rounded-full bg-slate-200 dark:bg-surface-accent overflow-hidden">
                        <img class="w-full h-full object-cover" data-alt="User profile avatar placeholder"
                            src="https://lh3.googleusercontent.com/aida-public/AB6AXuBHn8deIE1hmV20flCTZNxxAXOdcVDgc03rQE1__bwfDT4asScOPAuK_bW56MeQFAhOwY5u10ca-sp0VMU7uEX6TXAs0-FTv-8Dpp01UzBxn_63S9jdrJrEvmRiSI59XLNEFTE3TYXMJXPl6neCvcu7EY3yvW5Bj0Tsk3h8wHihpBwepnnw5Zv5ADgc5L140LW21a9MuqxyMKWS7hvIiCF86ELg1x4vf6sPLoPmlLY5XaE94z8gMU9taWn97voDURzD8Auok6OVKq8p" />
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-semibold truncate">{{ Auth::user()->name }}</p>
                        <p class="text-xs text-slate-500 truncate">Pro Account</p>
                    </div>
                    <span
                        class="material-symbols-outlined text-slate-400 text-sm group-hover:text-primary transition-colors">settings</span>
                </a>

                <!-- Logout -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                        class="w-full flex items-center gap-3 p-2 rounded-lg hover:bg-slate-100 dark:hover:bg-surface-accent transition-colors text-slate-600 dark:text-slate-400 hover:text-danger dark:hover:text-danger group">
                        <span class="material-symbols-outlined text-xl group-hover:text-danger">logout</span>
                        <span class="text-sm font-medium">Log Out</span>
                    </button>
                </form>
            </div>
        </aside>
        <!-- Main Content -->
        <main
            class="flex-1 flex flex-col min-w-0 overflow-y-auto bg-background-light dark:bg-background-dark no-scrollbar pb-24 md:pb-0">
            <!-- Top Navigation / Header -->
            <livewire:layout.header />

            @if (session()->has('message'))
                <div class="px-8 py-4">
                    <div class="bg-success/10 border border-success/20 text-success px-4 py-3 rounded relative"
                        role="alert">
                        <strong class="font-bold">Success!</strong>
                        <span class="block sm:inline">{{ session('message') }}</span>
                    </div>
                </div>
            @endif

            @if (isset($title))
                <h1 class="text-2xl font-bold text-white mb-6">{{ $title }}</h1>
            @endif
            {{ $slot }}
            <!-- Footer Stats -->
            <!-- <footer
                class="mt-auto border-t border-slate-200 dark:border-slate-800 p-6 bg-white dark:bg-surface grid grid-cols-2 md:grid-cols-4 gap-6">
                <div>
                    <p class="text-xs text-slate-500 uppercase font-semibold mb-1">Total Deposits</p>
                    <p class="text-lg font-bold">$245,000.00</p>
                </div>
                <div>
                    <p class="text-xs text-slate-500 uppercase font-semibold mb-1">Total Withdrawals</p>
                    <p class="text-lg font-bold">$120,407.55</p>
                </div>
                <div>
                    <p class="text-xs text-slate-500 uppercase font-semibold mb-1">Net PnL</p>
                    <p class="text-lg font-bold text-success">+$24,192.45</p>
                </div>
                <div>
                    <p class="text-xs text-slate-500 uppercase font-semibold mb-1">Portfolio Risk</p>
                    <div class="flex items-center gap-2">
                        <p class="text-lg font-bold">Moderate</p>
                        <div class="w-12 h-2 bg-slate-200 dark:bg-surface-accent rounded-full overflow-hidden">
                            <div class="h-full bg-primary w-1/2"></div>
                        </div>
                    </div>
                </div>
            </footer> -->
        </main>
    </div>
    <x-layouts.mobile-nav />
</body>

</html>