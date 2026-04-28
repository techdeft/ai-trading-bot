<?php

use Laravel\Fortify\Actions\ConfirmTwoFactorAuthentication;
use Laravel\Fortify\Actions\DisableTwoFactorAuthentication;
use Laravel\Fortify\Actions\EnableTwoFactorAuthentication;
use Laravel\Fortify\Features;
use Laravel\Fortify\Fortify;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Validate;
use Livewire\Volt\Component;
use Symfony\Component\HttpFoundation\Response;

new class extends Component {
    #[Locked]
    public bool $twoFactorEnabled;

    #[Locked]
    public bool $requiresConfirmation;

    #[Locked]
    public string $qrCodeSvg = '';

    #[Locked]
    public string $manualSetupKey = '';

    public bool $showModal = false;

    public bool $showVerificationStep = false;

    #[Validate('required|string|size:6', onUpdate: false)]
    public string $code = '';

    /**
     * Mount the component.
     */
    public function mount(DisableTwoFactorAuthentication $disableTwoFactorAuthentication): void
    {
        abort_unless(Features::enabled(Features::twoFactorAuthentication()), Response::HTTP_FORBIDDEN);

        if (Fortify::confirmsTwoFactorAuthentication() && is_null(auth()->user()->two_factor_confirmed_at)) {
            $disableTwoFactorAuthentication(auth()->user());
        }

        $this->twoFactorEnabled = auth()->user()->hasEnabledTwoFactorAuthentication();
        $this->requiresConfirmation = Features::optionEnabled(Features::twoFactorAuthentication(), 'confirm');
    }

    /**
     * Enable two-factor authentication for the user.
     */
    public function enable(EnableTwoFactorAuthentication $enableTwoFactorAuthentication): void
    {
        $enableTwoFactorAuthentication(auth()->user());

        if (!$this->requiresConfirmation) {
            $this->twoFactorEnabled = auth()->user()->hasEnabledTwoFactorAuthentication();
        }

        $this->loadSetupData();

        $this->showModal = true;
        $this->dispatch('open-modal', 'two-factor-setup-modal');
    }

    /**
     * Load the two-factor authentication setup data for the user.
     */
    private function loadSetupData(): void
    {
        $user = auth()->user();

        try {
            $this->qrCodeSvg = $user?->twoFactorQrCodeSvg();
            $this->manualSetupKey = decrypt($user->two_factor_secret);
        } catch (Exception) {
            $this->addError('setupData', 'Failed to fetch setup data.');

            $this->reset('qrCodeSvg', 'manualSetupKey');
        }
    }

    /**
     * Show the two-factor verification step if necessary.
     */
    public function showVerificationIfNecessary(): void
    {
        if ($this->requiresConfirmation) {
            $this->showVerificationStep = true;

            $this->resetErrorBag();

            return;
        }

        $this->closeModal();
    }

    /**
     * Confirm two-factor authentication for the user.
     */
    public function confirmTwoFactor(ConfirmTwoFactorAuthentication $confirmTwoFactorAuthentication): void
    {
        $this->validate();

        try {
            $confirmTwoFactorAuthentication(auth()->user(), $this->code);
        } catch (\Illuminate\Validation\ValidationException $e) {
            $this->addError('code', $e->getMessage() ?: __('The provided two factor authentication code was invalid.'));
            return;
        }

        $this->closeModal();

        $this->twoFactorEnabled = true;
    }

    /**
     * Reset two-factor verification state.
     */
    public function resetVerification(): void
    {
        $this->reset('code', 'showVerificationStep');

        $this->resetErrorBag();
    }

    /**
     * Disable two-factor authentication for the user.
     */
    public function disable(DisableTwoFactorAuthentication $disableTwoFactorAuthentication): void
    {
        $disableTwoFactorAuthentication(auth()->user());

        $this->twoFactorEnabled = false;
    }

    /**
     * Close the two-factor authentication modal.
     */
    public function closeModal(): void
    {
        $this->reset(
            'code',
            'manualSetupKey',
            'qrCodeSvg',
            'showModal',
            'showVerificationStep',
        );

        $this->resetErrorBag();

        if (!$this->requiresConfirmation) {
            $this->twoFactorEnabled = auth()->user()->hasEnabledTwoFactorAuthentication();
        }

        $this->dispatch('close-modal', 'two-factor-setup-modal');
    }

    /**
     * Get the current modal configuration state.
     */
    public function getModalConfigProperty(): array
    {
        if ($this->twoFactorEnabled) {
            return [
                'title' => __('Two-Factor Authentication Enabled'),
                'description' => __('Two-factor authentication is now enabled. Scan the QR code or enter the setup key in your authenticator app.'),
                'buttonText' => __('Close'),
            ];
        }

        if ($this->showVerificationStep) {
            return [
                'title' => __('Verify Authentication Code'),
                'description' => __('Enter the 6-digit code from your authenticator app.'),
                'buttonText' => __('Continue'),
            ];
        }

        return [
            'title' => __('Enable Two-Factor Authentication'),
            'description' => __('To finish enabling two-factor authentication, scan the QR code or enter the setup key in your authenticator app.'),
            'buttonText' => __('Continue'),
        ];
    }
} ?>

<section class="w-full">
    @include('partials.settings-heading')

    <h2 class="sr-only">{{ __('Two-Factor Authentication Settings') }}</h2>

    <x-layouts.settings :heading="__('Two Factor Authentication')" :subheading="__('Manage your two-factor authentication settings')">
        <div class="flex flex-col w-full mx-auto space-y-6 text-sm" wire:cloak>
            @if ($twoFactorEnabled)
                <div class="space-y-4">
                    <div class="flex items-center gap-3">
                        <span
                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-800/20 dark:text-green-400">
                            {{ __('Enabled') }}
                        </span>
                    </div>

                    <p class="text-gray-600 dark:text-gray-400">
                        {{ __('With two-factor authentication enabled, you will be prompted for a secure, random pin during login, which you can retrieve from the TOTP-supported application on your phone.') }}
                    </p>

                    <livewire:settings.two-factor.recovery-codes :$requiresConfirmation />

                    <div class="flex justify-start">
                        <button wire:click="disable"
                            class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-500 active:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                            {{ __('Disable 2FA') }}
                        </button>
                    </div>
                </div>
            @else
                <div class="space-y-4">
                    <div class="flex items-center gap-3">
                        <span
                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-800/20 dark:text-red-400">
                            {{ __('Disabled') }}
                        </span>
                    </div>

                    <p class="text-gray-600 dark:text-gray-400">
                        {{ __('When you enable two-factor authentication, you will be prompted for a secure pin during login. This pin can be retrieved from a TOTP-supported application on your phone.') }}
                    </p>

                    <button wire:click="enable"
                        class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                        {{ __('Enable 2FA') }}
                    </button>
                </div>
            @endif
        </div>
    </x-layouts.settings>

    <x-modal name="two-factor-setup-modal" :show="$showModal" focusable>
        <div class="p-6 space-y-6">
            <div class="flex flex-col items-center space-y-4">
                <div class="space-y-2 text-center">
                    <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">{{ $this->modalConfig['title'] }}
                    </h2>
                    <p class="text-sm text-gray-600 dark:text-gray-400">{{ $this->modalConfig['description'] }}</p>
                </div>
            </div>

            @if ($showVerificationStep)
                <div class="space-y-6">
                    <div class="flex flex-col items-center space-y-3 justify-center">
                        <div>
                            <label for="code" class="sr-only">OTP Code</label>
                            <input wire:model="code" id="code" type="text" inputmode="numeric" pattern="[0-9]*"
                                autocomplete="one-time-code"
                                class="block w-48 text-center rounded-md border-gray-300 dark:border-gray-700 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-lg dark:bg-gray-900 dark:text-white" />
                            @error('code')
                                <div class="text-red-500 text-sm mt-1 text-center">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="flex items-center space-x-3">
                        <button wire:click="resetVerification"
                            class="flex-1 inline-flex justify-center items-center px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-500 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 disabled:opacity-25 transition ease-in-out duration-150">
                            {{ __('Back') }}
                        </button>

                        <button wire:click="confirmTwoFactor"
                            class="flex-1 inline-flex justify-center items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                            {{ __('Confirm') }}
                        </button>
                    </div>
                </div>
            @else
                @error('setupData')
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

                <div class="flex justify-center">
                    <div
                        class="relative w-64 overflow-hidden border rounded-lg border-gray-200 dark:border-gray-700 aspect-square p-4 bg-white">
                        @empty($qrCodeSvg)
                            <div class="absolute inset-0 flex items-center justify-center animate-pulse">
                                <span class="material-symbols-outlined text-4xl text-gray-300">qr_code</span>
                            </div>
                        @else
                            {!! $qrCodeSvg !!}
                        @endempty
                    </div>
                </div>

                <div>
                    <button @if($errors->has('setupData')) disabled @endif wire:click="showVerificationIfNecessary"
                        class="w-full inline-flex justify-center items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150 disabled:opacity-50">
                        {{ $this->modalConfig['buttonText'] }}
                    </button>
                </div>

                <div class="space-y-4">
                    <div class="relative flex items-center justify-center w-full">
                        <div class="absolute inset-0 w-full h-px top-1/2 bg-gray-200 dark:bg-gray-700"></div>
                        <span class="relative px-2 text-sm bg-white dark:bg-gray-900 text-gray-600 dark:text-gray-400">
                            {{ __('or, enter the code manually') }}
                        </span>
                    </div>

                    <div class="flex items-center space-x-2" x-data="{
                                copied: false,
                                async copy() {
                                    try {
                                        await navigator.clipboard.writeText('{{ $manualSetupKey }}');
                                        this.copied = true;
                                        setTimeout(() => this.copied = false, 1500);
                                    } catch (e) {
                                        console.warn('Could not copy to clipboard');
                                    }
                                }
                            }">
                        <div
                            class="flex items-center w-full border rounded-md dark:border-gray-700 bg-gray-50 dark:bg-gray-800">
                            @empty($manualSetupKey)
                                <div class="flex items-center justify-center w-full p-3">
                                    <span class="material-symbols-outlined animate-spin text-gray-400">sync</span>
                                </div>
                            @else
                                <input type="text" readonly value="{{ $manualSetupKey }}"
                                    class="w-full p-3 bg-transparent outline-none text-gray-900 dark:text-gray-100 text-sm font-mono border-none focus:ring-0" />

                                <button @click="copy()"
                                    class="px-3 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 transition-colors">
                                    <span class="material-symbols-outlined text-lg" x-show="!copied">content_copy</span>
                                    <span class="material-symbols-outlined text-lg text-green-500" x-show="copied">check</span>
                                </button>
                            @endempty
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </x-modal>
</section>