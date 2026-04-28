<?php

use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;
use Livewire\Volt\Volt;

Route::middleware(['auth'])->group(function () {
    Volt::route('settings/profile', 'settings.profile')->name('profile.edit');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Volt::route('settings/password', 'settings.password')->name('user-password.edit');
    Volt::route('settings/appearance', 'settings.appearance')->name('appearance.edit');

    Volt::route('settings/two-factor', 'settings.two-factor')
        ->name('two-factor.show');
});
