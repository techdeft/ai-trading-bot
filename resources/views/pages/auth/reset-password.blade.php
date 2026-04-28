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
                <h1 class="text-3xl font-black tracking-tight mb-3 text-slate-900 dark:text-white">Reset Password</h1>
                <p class="text-slate-500 dark:text-[#90adcb] text-base">
                    {{ __('Please enter your new password below.') }}
                </p>
            </div>

            <x-auth-session-status class="mb-4 text-center" :status="session('status')" />

            <!-- Card -->
            <div
                class="bg-white dark:bg-[#182634] p-8 rounded-xl border border-slate-200 dark:border-slate-800 shadow-xl">
                <form method="POST" action="{{ route('password.update') }}" class="space-y-5">
                    @csrf
                    <!-- Token -->
                    <input type="hidden" name="token" value="{{ request()->route('token') }}">

                    <!-- Email Address -->
                    <div class="space-y-2">
                        <label class="text-sm font-semibold text-slate-700 dark:text-slate-200 ml-1">Email
                            Address</label>
                        <div class="relative">
                            <input
                                class="w-full bg-white dark:bg-[#182634] border border-slate-200 dark:border-[#314d68] focus:border-primary dark:focus:border-primary focus:ring-1 focus:ring-primary rounded-lg h-12 px-4 text-base text-slate-900 dark:text-white transition-all placeholder:text-slate-400 dark:placeholder:text-[#90adcb]"
                                placeholder="name@company.com" type="email" name="email"
                                value="{{ old('email', request('email')) }}" required autofocus autocomplete="email" />
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

                    <button
                        class="w-full bg-primary hover:bg-primary/90 text-white font-bold h-12 rounded-lg transition-all shadow-lg shadow-primary/20 active:scale-[0.98] flex items-center justify-center gap-2"
                        type="submit">
                        {{ __('Reset password') }}
                    </button>
                </form>
            </div>
        </div>
    </main>
</x-layouts.auth>