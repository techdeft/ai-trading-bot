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
                <h1 class="text-3xl font-black tracking-tight mb-3 text-slate-900 dark:text-white">Verify Email</h1>
                <p class="text-slate-500 dark:text-[#90adcb] text-base">
                    {{ __('Please verify your email address.') }}
                </p>
            </div>

            <!-- Card -->
            <div
                class="bg-white dark:bg-[#182634] p-8 rounded-xl border border-slate-200 dark:border-slate-800 shadow-xl">
                <div class="mb-6 text-sm text-slate-600 dark:text-slate-400">
                    {{ __('Thanks for signing up! Before getting started, could you verify your email address by clicking on the link we just emailed to you? If you didn\'t receive the email, we will gladly send you another.') }}
                </div>

                @if (session('status') == 'verification-link-sent')
                    <div class="mb-6 font-medium text-sm text-green-600 dark:text-green-400">
                        {{ __('A new verification link has been sent to the email address you provided during registration.') }}
                    </div>
                @endif

                <div class="mt-4 flex items-center justify-between">
                    <form method="POST" action="{{ route('verification.send') }}">
                        @csrf
                        <button
                            class="bg-primary hover:bg-primary/90 text-white font-bold h-10 px-4 rounded-lg transition-all shadow-lg shadow-primary/20 active:scale-[0.98] text-sm"
                            type="submit">
                            {{ __('Resend Verification Email') }}
                        </button>
                    </form>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                            class="text-sm text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-slate-100 underline rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800">
                            {{ __('Log Out') }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </main>
</x-layouts.auth>