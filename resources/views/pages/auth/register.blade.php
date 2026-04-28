<x-layouts.auth>
    <!-- Main Content -->
    <main class="flex-1 flex flex-col items-center justify-center p-6 relative overflow-hidden">
        <!-- Abstract Background Decoration -->
        <div class="absolute inset-0 grid-pattern pointer-events-none opacity-50"></div>
        <div class="absolute top-1/4 -left-20 w-96 h-96 bg-primary/10 rounded-full blur-[120px] pointer-events-none">
        </div>
        <div
            class="absolute bottom-1/4 -right-20 w-96 h-96 bg-primary/10 rounded-full blur-[120px] pointer-events-none">
        </div>
        <div class="w-full max-w-[480px] z-10">
            <!-- Header Text -->
            <div class="text-center mb-8">
                <h1 class="text-3xl md:text-4xl text-white tracking-tight mb-3">Create Your Account</h1>
                <p class="text-slate-500 dark:text-[#90adcb] text-base">Join TradePro — The gateway to professional
                    trading.</p>
            </div>

            <x-auth-session-status class="mb-4 text-center" :status="session('status')" />

            <!-- Sign Up Card -->
            <div class="auth-card-blur border border-slate-200 dark:border-[#314d68] rounded-xl p-8 shadow-2xl">
                <form action="{{ route('register.store') }}" class="space-y-5" method="POST">
                    @csrf
                    <!-- Full Name -->
                    <div class="space-y-2">
                        <label class="text-sm font-semibold text-slate-700 dark:text-slate-200 ml-1">Full Name</label>
                        <div class="relative group">
                            <input
                                class="w-full bg-white dark:bg-[#182634] border border-slate-200 dark:border-[#314d68] focus:border-primary dark:focus:border-primary focus:ring-1 focus:ring-primary rounded-lg h-12 px-4 text-base text-slate-900 dark:text-white transition-all placeholder:text-slate-400 dark:placeholder:text-[#90adcb]"
                                placeholder="John Doe" type="text" name="name" value="{{ old('name') }}" required
                                autofocus autocomplete="name" />
                        </div>
                        @error('name')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>
                    <!-- Email Address -->
                    <div class="space-y-2">
                        <label class="text-sm font-semibold text-slate-700 dark:text-slate-200 ml-1">Email
                            Address</label>
                        <div class="relative">
                            <input
                                class="w-full bg-white dark:bg-[#182634] border border-slate-200 dark:border-[#314d68] focus:border-primary dark:focus:border-primary focus:ring-1 focus:ring-primary rounded-lg h-12 px-4 text-base text-slate-900 dark:text-white transition-all placeholder:text-slate-400 dark:placeholder:text-[#90adcb]"
                                placeholder="name@company.com" type="email" name="email" value="{{ old('email') }}"
                                required autocomplete="email" />
                        </div>
                        @error('email')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>
                    <!-- Password -->
                    <div class="space-y-2">
                        <label class="text-sm font-semibold text-slate-700 dark:text-slate-200 ml-1">Password</label>
                        <div class="flex items-stretch">
                            <input
                                class="flex-1 bg-white dark:bg-[#182634] border border-slate-200 dark:border-[#314d68] border-r-0 focus:border-primary dark:focus:border-primary focus:ring-1 focus:ring-primary rounded-l-lg h-12 px-4 text-base text-slate-900 dark:text-white transition-all placeholder:text-slate-400 dark:placeholder:text-[#90adcb]"
                                placeholder="••••••••" type="password" name="password" id="password" required
                                autocomplete="new-password" />
                            <button
                                class="px-3 flex items-center justify-center bg-white dark:bg-[#182634] border border-slate-200 dark:border-[#314d68] border-l-0 rounded-r-lg text-slate-400 dark:text-[#90adcb] hover:text-primary transition-colors"
                                type="button"
                                onclick="const input = document.getElementById('password'); input.type = input.type === 'password' ? 'text' : 'password';">
                                <span class="material-symbols-outlined">visibility</span>
                            </button>
                        </div>
                        @error('password')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror

                    </div>

                    <!-- Confirm Password -->
                    <div class="space-y-2">
                        <label class="text-sm font-semibold text-slate-700 dark:text-slate-200 ml-1">Confirm
                            Password</label>
                        <div class="flex items-stretch">
                            <input
                                class="flex-1 bg-white dark:bg-[#182634] border border-slate-200 dark:border-[#314d68] border-r-0 focus:border-primary dark:focus:border-primary focus:ring-1 focus:ring-primary rounded-l-lg h-12 px-4 text-base text-slate-900 dark:text-white transition-all placeholder:text-slate-400 dark:placeholder:text-[#90adcb]"
                                placeholder="••••••••" type="password" name="password_confirmation"
                                id="password_confirmation" required autocomplete="new-password" />
                            <button
                                class="px-3 flex items-center justify-center bg-white dark:bg-[#182634] border border-slate-200 dark:border-[#314d68] border-l-0 rounded-r-lg text-slate-400 dark:text-[#90adcb] hover:text-primary transition-colors"
                                type="button"
                                onclick="const input = document.getElementById('password_confirmation'); input.type = input.type === 'password' ? 'text' : 'password';">
                                <span class="material-symbols-outlined">visibility</span>
                            </button>
                        </div>
                    </div>

                    <!-- Terms -->
                    <div class="flex items-start gap-3 py-2">
                        <div class="flex items-center h-5">
                            <input
                                class="w-4 h-4 rounded border-slate-300 dark:border-[#314d68] text-primary focus:ring-primary bg-white dark:bg-[#182634]"
                                id="terms" type="checkbox" required />
                        </div>
                        <label class="text-xs text-slate-600 dark:text-[#90adcb] leading-relaxed" for="terms">
                            I agree to the <a class="text-primary hover:underline font-medium" href="#">Terms &amp;
                                Conditions</a> and <a class="text-primary hover:underline font-medium" href="#">Privacy
                                Policy</a>.
                        </label>
                    </div>
                    <!-- Submit Button -->
                    <button
                        class="w-full bg-primary hover:bg-primary/90 text-white font-bold h-12 rounded-lg transition-all shadow-lg shadow-primary/20 active:scale-[0.98] flex items-center justify-center gap-2"
                        type="submit">
                        Create Account
                        <span class="material-symbols-outlined text-sm">arrow_forward</span>
                    </button>

                </form>
                <div class="mt-8 text-center">
                    <p class="text-sm text-slate-500 dark:text-[#90adcb]">
                        Already have an account?
                        <a class="text-primary font-bold hover:underline ml-1" href="{{ route('login') }}"
                            wire:navigate>Log In</a>
                    </p>
                </div>
            </div>
            <!-- Footer Small -->
            <footer class="mt-8 text-center">
                <p class="text-[10px] text-slate-400 dark:text-slate-500 uppercase tracking-widest font-medium">
                    © 2024 TradePro Financial Inc. All rights reserved.
                </p>
            </footer>
</x-layouts.auth>