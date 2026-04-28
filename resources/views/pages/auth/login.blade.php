<x-layouts.auth>
    <main class="flex-1 flex items-center justify-center p-6">
        <div class="w-full max-w-lg">
            <!-- Login Card -->
            <div
                class="bg-white dark:bg-[#182634] p-8 rounded-xl border border-slate-200 dark:border-slate-800 shadow-xl">
                <!-- Header -->
                <div class="mb-8 text-center sm:text-left">
                    <h1 class="text-slate-900 dark:text-white text-3xl font-black leading-tight tracking-tight mb-2">
                        Welcome back</h1>
                    <p class="text-slate-500 dark:text-slate-400 text-base font-normal">Enter your details to access
                        your account.</p>
                </div>

                <x-auth-session-status class="mb-4 text-center" :status="session('status')" />

                <!-- Form -->
                <form action="{{ route('login.store') }}" class="space-y-5" method="POST">
                    @csrf
                    <!-- Email -->
                    <div class="flex flex-col gap-2">
                        <label class="text-slate-700 dark:text-slate-200 text-sm font-semibold leading-normal"
                            for="email">Email Address</label>
                        <input
                            class="form-input w-full rounded-lg text-slate-900 dark:text-white focus:outline-0 focus:ring-2 focus:ring-primary/50 border border-slate-300 dark:border-slate-700 bg-white dark:bg-[#101922] h-12 px-4 text-base font-normal transition-all"
                            id="email" name="email" placeholder="name@company.com" type="email"
                            value="{{ old('email') }}" required autofocus autocomplete="email" />
                        @error('email')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>
                    <!-- Password -->
                    <div class="flex flex-col gap-2">
                        <div class="flex justify-between items-center">
                            <label class="text-slate-700 dark:text-slate-200 text-sm font-semibold leading-normal"
                                for="password">Password</label>
                        </div>
                        <div class="relative group">
                            <input
                                class="form-input w-full rounded-lg text-slate-900 dark:text-white focus:outline-0 focus:ring-2 focus:ring-primary/50 border border-slate-300 dark:border-slate-700 bg-white dark:bg-[#101922] h-12 pl-4 pr-12 text-base font-normal transition-all"
                                id="password" name="password" placeholder="••••••••" type="password" required
                                autocomplete="current-password" />
                            <button
                                class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-primary transition-colors"
                                type="button"
                                onclick="const input = document.getElementById('password'); input.type = input.type === 'password' ? 'text' : 'password';">
                                <span class="material-symbols-outlined text-[22px]">visibility</span>
                            </button>
                        </div>
                        @error('password')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>
                    <!-- Remember & Forgot -->
                    <div class="flex items-center justify-between">
                        <label class="flex items-center gap-2 cursor-pointer group">
                            <div class="relative flex items-center">
                                <input
                                    class="checkbox-custom h-5 w-5 rounded border-slate-300 dark:border-slate-700 bg-transparent text-primary focus:ring-primary/20 focus:ring-offset-0 transition-all cursor-pointer"
                                    type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }} />
                            </div>
                            <span
                                class="text-slate-600 dark:text-slate-400 text-sm font-medium group-hover:text-slate-900 dark:group-hover:text-slate-200 transition-colors">Remember
                                me</span>
                        </label>
                        @if (Route::has('password.request'))
                            <a class="text-primary text-sm font-bold hover:underline" href="{{ route('password.request') }}"
                                wire:navigate>Forgot password?</a>
                        @endif
                    </div>
                    <!-- Login Button -->
                    <button
                        class="w-full flex cursor-pointer items-center justify-center rounded-lg h-12 px-6 bg-primary text-white text-base font-bold leading-normal tracking-wide hover:bg-primary/90 active:scale-[0.98] transition-all shadow-lg shadow-primary/20"
                        type="submit">
                        Login
                    </button>
                </form>

                <!-- Footer -->
                <p class="mt-8 text-center text-sm text-slate-500 dark:text-slate-400">
                    New here?
                    @if (Route::has('register'))
                        <a class="text-primary font-bold hover:underline ml-1" wire:navigate
                            href="{{ route('register') }}">Create an account</a>
                    @endif
                </p>
            </div>
            <!-- Additional Links -->
            <div class="mt-8 flex justify-center gap-6 text-xs text-slate-400 dark:text-slate-600 font-medium">
                <a class="hover:text-primary transition-colors uppercase tracking-widest" href="#">Privacy Policy</a>
                <a class="hover:text-primary transition-colors uppercase tracking-widest" href="#">Terms of Service</a>
                <a class="hover:text-primary transition-colors uppercase tracking-widest" href="#">Contact Us</a>
            </div>
        </div>
    </main>
</x-layouts.auth>