<x-layouts.auth>
    <main class="flex-1 flex items-center justify-center p-6 relative overflow-hidden">
        <!-- Abstract Background Decoration -->
        <div class="absolute inset-0 grid-pattern pointer-events-none opacity-50"></div>
        <div class="absolute top-1/4 -left-20 w-96 h-96 bg-primary/10 rounded-full blur-[120px] pointer-events-none">
        </div>
        <div
            class="absolute bottom-1/4 -right-20 w-96 h-96 bg-primary/10 rounded-full blur-[120px] pointer-events-none">
        </div>

        <div class="w-full max-w-md z-10" x-data="{
            showRecoveryInput: @js($errors->has('recovery_code')),
            code: '',
            recovery_code: '',
            toggleInput() {
                this.showRecoveryInput = !this.showRecoveryInput;
                this.code = '';
                this.recovery_code = '';
                $nextTick(() => {
                    this.showRecoveryInput ?
                        this.$refs.recovery_code?.focus() :
                        this.$refs.code?.focus();
                });
            },
        }">
            <!-- Header Text -->
            <div class="text-center mb-8">
                <template x-if="!showRecoveryInput">
                    <div>
                        <h1 class="text-3xl font-black tracking-tight mb-3 text-slate-900 dark:text-white">
                            Authentication Code
                        </h1>
                        <p class="text-slate-500 dark:text-[#90adcb] text-base">
                            {{ __('Enter the authentication code provided by your authenticator application.') }}
                        </p>
                    </div>
                </template>
                <template x-if="showRecoveryInput">
                    <div>
                        <h1 class="text-3xl font-black tracking-tight mb-3 text-slate-900 dark:text-white">Recovery Code
                        </h1>
                        <p class="text-slate-500 dark:text-[#90adcb] text-base">
                            {{ __('Please confirm access to your account by entering one of your emergency recovery codes.') }}
                        </p>
                    </div>
                </template>
            </div>

            <!-- Card -->
            <div
                class="bg-white dark:bg-[#182634] p-8 rounded-xl border border-slate-200 dark:border-slate-800 shadow-xl">
                <form method="POST" action="{{ route('two-factor.login.store') }}" class="space-y-5">
                    @csrf

                    <!-- OTP Code Input -->
                    <div x-show="!showRecoveryInput" class="space-y-2">
                        <label class="text-sm font-semibold text-slate-700 dark:text-slate-200 ml-1">Code</label>
                        <input
                            class="w-full bg-white dark:bg-[#182634] border border-slate-200 dark:border-[#314d68] focus:border-primary dark:focus:border-primary focus:ring-1 focus:ring-primary rounded-lg h-12 px-4 text-base text-slate-900 dark:text-white transition-all placeholder:text-slate-400 dark:placeholder:text-[#90adcb] text-center tracking-widest"
                            type="text" inputmode="numeric" name="code" x-model="code" x-ref="code" placeholder="123456"
                            autofocus autocomplete="one-time-code" />
                        @error('code')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Recovery Code Input -->
                    <div x-show="showRecoveryInput" class="space-y-2" style="display: none;">
                        <label class="text-sm font-semibold text-slate-700 dark:text-slate-200 ml-1">Recovery
                            Code</label>
                        <input
                            class="w-full bg-white dark:bg-[#182634] border border-slate-200 dark:border-[#314d68] focus:border-primary dark:focus:border-primary focus:ring-1 focus:ring-primary rounded-lg h-12 px-4 text-base text-slate-900 dark:text-white transition-all placeholder:text-slate-400 dark:placeholder:text-[#90adcb]"
                            type="text" name="recovery_code" x-model="recovery_code" x-ref="recovery_code"
                            autocomplete="one-time-code" />
                        @error('recovery_code')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <button
                        class="w-full bg-primary hover:bg-primary/90 text-white font-bold h-12 rounded-lg transition-all shadow-lg shadow-primary/20 active:scale-[0.98] flex items-center justify-center gap-2"
                        type="submit">
                        {{ __('Continue') }}
                    </button>
                </form>

                <div class="mt-8 text-center">
                    <p class="text-sm text-slate-500 dark:text-[#90adcb]">
                        <span class="opacity-50">{{ __('or you can') }}</span>
                        <button class="text-primary font-bold hover:underline ml-1 focus:outline-none" type="button"
                            @click="toggleInput()">
                            <span x-show="!showRecoveryInput">{{ __('login using a recovery code') }}</span>
                            <span x-show="showRecoveryInput"
                                style="display: none;">{{ __('login using an authentication code') }}</span>
                        </button>
                    </p>
                </div>
            </div>
        </div>
    </main>
</x-layouts.auth>