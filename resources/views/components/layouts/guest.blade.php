<!DOCTYPE html>
<html class="dark" lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>{{ $title ?? 'Falcon x | Automate Your Wealth with AI' }}</title>

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
                <a href="{{ route('home') }}" class="flex items-center gap-2">
                    <div class="text-primary">
                        <svg class="w-8 h-8" fill="currentColor" viewbox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path d="M12 2L4.5 20.29l.71.71L12 18l6.79 3 .71-.71L12 2z"></path>
                        </svg>
                    </div>
                    <span class="text-xl font-bold tracking-tight">Falcon x</span>
                </a>

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
        {{ $slot }}
    </main>

    <!-- Footer -->
    <x-guest.footer />
</body>

</html>