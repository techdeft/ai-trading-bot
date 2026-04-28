<?php

use Laravel\Fortify\Actions\GenerateNewRecoveryCodes;
use Livewire\Attributes\Locked;
use Livewire\Volt\Component;

new class extends Component {
    #[Locked]
    public array $recoveryCodes = [];

    /**
     * Mount the component.
     */
    public function mount(): void
    {
        $this->loadRecoveryCodes();
    }

    /**
     * Generate new recovery codes for the user.
     */
    public function regenerateRecoveryCodes(GenerateNewRecoveryCodes $generateNewRecoveryCodes): void
    {
        $generateNewRecoveryCodes(auth()->user());

        $this->loadRecoveryCodes();
    }

    /**
     * Load the recovery codes for the user.
     */
    private function loadRecoveryCodes(): void
    {
        $user = auth()->user();

        if ($user->hasEnabledTwoFactorAuthentication() && $user->two_factor_recovery_codes) {
            try {
                $this->recoveryCodes = json_decode(decrypt($user->two_factor_recovery_codes), true);
            } catch (Exception) {
                $this->addError('recoveryCodes', 'Failed to load recovery codes');

                $this->recoveryCodes = [];
            }
        }
    }
}; ?>

<div class="py-6 space-y-6 border shadow-sm rounded-xl border-zinc-200 dark:border-white/10" wire:cloak
    x-data="{ showRecoveryCodes: false }">
    <div class="px-6 space-y-2">
        <div class="flex items-center gap-2">
            <span class="material-symbols-outlined text-lg">lock</span>
            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">{{ __('2FA Recovery Codes') }}</h3>
        </div>
        <p class="text-sm text-gray-500 dark:text-gray-400">
            {{ __('Recovery codes let you regain access if you lose your 2FA device. Store them in a secure password manager.') }}
        </p>
    </div>

    <div class="px-6">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <button x-show="!showRecoveryCodes"
                class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150"
                @click="showRecoveryCodes = true;" aria-expanded="false" aria-controls="recovery-codes-section">
                <span class="material-symbols-outlined text-sm mr-2">visibility</span>
                {{ __('View Recovery Codes') }}
            </button>

            <button x-show="showRecoveryCodes"
                class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150"
                @click="showRecoveryCodes = false" aria-expanded="true" aria-controls="recovery-codes-section">
                <span class="material-symbols-outlined text-sm mr-2">visibility_off</span>
                {{ __('Hide Recovery Codes') }}
            </button>

            @if (filled($recoveryCodes))
                <button x-show="showRecoveryCodes"
                    class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-500 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 disabled:opacity-25 transition ease-in-out duration-150"
                    wire:click="regenerateRecoveryCodes">
                    <span class="material-symbols-outlined text-sm mr-2">refresh</span>
                    {{ __('Regenerate Codes') }}
                </button>
            @endif
        </div>

        <div x-show="showRecoveryCodes" x-transition id="recovery-codes-section" class="relative overflow-hidden"
            x-bind:aria-hidden="!showRecoveryCodes">
            <div class="mt-3 space-y-3">
                @error('recoveryCodes')
                    <div class="rounded-md bg-red-50 p-4">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <span class="material-symbols-outlined text-red-400">error</span>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-red-800">{{ $message }}</h3>
                            </div>
                        </div>
                    </div>
                @enderror

                @if (filled($recoveryCodes))
                    <div class="grid gap-1 p-4 font-mono text-sm rounded-lg bg-zinc-100 dark:bg-white/5" role="list"
                        aria-label="{{ __('Recovery codes') }}">
                        @foreach($recoveryCodes as $code)
                            <div role="listitem" class="select-text" wire:loading.class="opacity-50 animate-pulse">
                                {{ $code }}
                            </div>
                        @endforeach
                    </div>
                    <p class="text-xs text-gray-500 dark:text-gray-400">
                        {{ __('Each recovery code can be used once to access your account and will be removed after use. If you need more, click Regenerate Codes above.') }}
                    </p>
                @endif
            </div>
        </div>
    </div>
</div>