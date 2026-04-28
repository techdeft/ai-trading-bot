@props(['class' => ''])

<a href="{{ route('dashboard') }}" {{ $attributes->merge(['class' => 'flex items-center gap-2 ' . $class]) }}
    wire:navigate>
    <div class="flex items-center justify-center size-8 rounded-lg bg-primary/10 text-primary">
        <x-app-logo-icon class="size-6 fill-current" />
    </div>
    <span class="text-xl font-bold tracking-tight text-slate-900 dark:text-white">Falcon x</span>
</a>