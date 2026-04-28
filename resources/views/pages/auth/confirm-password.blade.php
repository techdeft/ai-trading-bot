<x-layouts.auth>
    <main class="flex-1 flex items-center justify-center p-6 relative overflow-hidden">
        <!-- Abstract Background Decoration -->
        <div class="absolute inset-0 grid-pattern pointer-events-none opacity-50"></div>
        <div class="absolute top-1/4 -left-20 w-96 h-96 bg-primary/10 rounded-full blur-[120px] pointer-events-none">
        </div>
        <div
            class="absolute bottom-1/4 -right-20 w-96 h-96 bg-primary/10 rounded-full blur-[120px] pointer-events-none">
        </div>

        <div class="w-full max-w-md z-10">
            <!-- Header Text -->
            <div class="text-center mb-8">
                <h1 class="text-3xl font-black tracking-tight mb-3 text-slate-900 dark:text-white">Confirm Password</h1>
                <p class="text-slate-500 dark:text-[#90adcb] text-base">
                    {{ __('This is a secure area of the application. Please confirm your password before continuing.') }}
                </p>
            </div>

            <x-auth-session-status class="mb-4 text-center" :status="session('status')" />

            <!-- Card -->
            <div
                class="bg-white dark:bg-[#182634] p-8 rounded-xl border border-slate-200 dark:border-slate-800 shadow-xl">
                <form method="POST" action="{{ route('password.confirm.store') }}" class="space-y-5">
                    @csrf

                    <!-- Password -->
                    <div class="space-y-2">
                        <label class="text-sm font-semibold text-slate-700 dark:text-slate-200 ml-1">Password</label>
                        <div class="flex items-stretch">
                            <input
                                class="flex-1 bg-white dark:bg-[#182634] border border-slate-200 dark:border-[#314d68] border-r-0 focus:border-primary dark:focus:border-primary focus:ring-1 focus:ring-primary rounded-lg h-12 px-4 text-base text-slate-900 dark:text-white transition-all placeholder:text-slate-400 dark:placeholder:text-[#90adcb]"
                                placeholder="••••••••" type="password" name="password" id="password" required
                                autocomplete="current-password" />
                            <div class="absolute right-3 top-1/2 -translate-y-1/2">
                                <button class="text-slate-400 hover:text-primary transition-colors focus:outline-none"
                                    type="button"
                                    onclick="const input = document.getElementById('password'); input.type = input.type === 'password' ? 'text' : 'password';">
                                    <span class="material-symbols-outlined text-[22px]">visibility</span>
                                </button>
                            </div>
                        </div>
                        @error('password')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <button
                        class="w-full bg-primary hover:bg-primary/90 text-white font-bold h-12 rounded-lg transition-all shadow-lg shadow-primary/20 active:scale-[0.98] flex items-center justify-center gap-2"
                        type="submit">
                        {{ __('Confirm') }}
                    </button>
                </form>
            </div>
        </div>
    </main>
</x-layouts.auth>