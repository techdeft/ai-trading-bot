<?php

use Livewire\Volt\Component;

new class extends Component {
    //
}; ?>

<section class="w-full">
    @include('partials.settings-heading')

    <h2 class="sr-only">{{ __('Appearance Settings') }}</h2>

    <x-layouts.settings :heading="__('Appearance')" :subheading="__('Update the appearance settings for your account')">
        <div class="flex items-center space-x-4" x-data="{
            theme: localStorage.getItem('theme') || 'system',
            setTheme(val) {
                this.theme = val;
                if (val === 'dark' || (val === 'system' && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                    document.documentElement.classList.add('dark');
                } else {
                    document.documentElement.classList.remove('dark');
                }
                if (val === 'system') {
                    localStorage.removeItem('theme');
                } else {
                    localStorage.setItem('theme', val);
                }
            }
        }">
            <button x-on:click="setTheme('light')" :class="theme === 'light' ? 'bg-gray-100 dark:bg-gray-700' : ''"
                class="p-2 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-800 transition">
                <span class="material-symbols-outlined">light_mode</span>
                <span class="ml-2">{{ __('Light') }}</span>
            </button>
            <button x-on:click="setTheme('dark')" :class="theme === 'dark' ? 'bg-gray-100 dark:bg-gray-700' : ''"
                class="p-2 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-800 transition">
                <span class="material-symbols-outlined">dark_mode</span>
                <span class="ml-2">{{ __('Dark') }}</span>
            </button>
            <button x-on:click="setTheme('system')" :class="theme === 'system' ? 'bg-gray-100 dark:bg-gray-700' : ''"
                class="p-2 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-800 transition">
                <span class="material-symbols-outlined">desktop_windows</span>
                <span class="ml-2">{{ __('System') }}</span>
            </button>
        </div>
    </x-layouts.settings>
</section>