<div class="flex items-start max-md:flex-col gap-6">
    <div class="w-full md:w-64 flex-shrink-0">
        <nav class="space-y-1">
            <a href="{{ route('profile.edit') }}" wire:navigate
                class="block px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('profile.edit') ? 'bg-gray-100 dark:bg-gray-800 text-gray-900 dark:text-gray-100' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-800 hover:text-gray-900 dark:hover:text-gray-100' }}">
                {{ __('Profile') }}
            </a>
            <a href="{{ route('user-password.edit') }}" wire:navigate
                class="block px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('user-password.edit') ? 'bg-gray-100 dark:bg-gray-800 text-gray-900 dark:text-gray-100' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-800 hover:text-gray-900 dark:hover:text-gray-100' }}">
                {{ __('Password') }}
            </a>
            @if (Laravel\Fortify\Features::canManageTwoFactorAuthentication())
                <a href="{{ route('two-factor.show') }}" wire:navigate
                    class="block px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('two-factor.show') ? 'bg-gray-100 dark:bg-gray-800 text-gray-900 dark:text-gray-100' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-800 hover:text-gray-900 dark:hover:text-gray-100' }}">
                    {{ __('Two-Factor Auth') }}
                </a>
            @endif
            <a href="{{ route('appearance.edit') }}" wire:navigate
                class="block px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('appearance.edit') ? 'bg-gray-100 dark:bg-gray-800 text-gray-900 dark:text-gray-100' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-800 hover:text-gray-900 dark:hover:text-gray-100' }}">
                {{ __('Appearance') }}
            </a>

            <div class="border-t border-gray-200 dark:border-gray-700 my-2"></div>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                    class="w-full text-left px-3 py-2 rounded-md text-sm font-medium text-rose-600 hover:bg-rose-50 dark:hover:bg-rose-900/20 transition-colors">
                    {{ __('Log Out') }}
                </button>
            </form>
        </nav>
    </div>

    <div class="md:hidden w-full border-t border-gray-200 dark:border-gray-700 my-4"></div>

    <div class="flex-1 min-w-0">
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">{{ $heading ?? '' }}</h2>
        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">{{ $subheading ?? '' }}</p>

        <div class="mt-6 w-full max-w-xl">
            {{ $slot }}
        </div>
    </div>
</div>