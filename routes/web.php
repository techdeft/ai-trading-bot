<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::view('/performance', 'pages.guest.performance')->name('guest.performance');
Route::view('/about', 'pages.guest.about')->name('guest.about');
Route::view('/privacy-policy', 'pages.guest.privacy')->name('guest.privacy');
Route::view('/investment-plans', 'pages.guest.plans')->name('guest.plans');
Route::view('/faq', 'pages.guest.faq')->name('guest.faq');
Route::view('/contact', 'pages.guest.contact')->name('guest.contact');
Route::view('/terms-of-service', 'pages.guest.terms')->name('guest.terms');
Route::view('/security', 'pages.guest.security')->name('guest.security');

use Livewire\Volt\Volt;

Volt::route('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth', 'verified'])->group(function () {
    Volt::route('portfolio', 'portfolio')->name('portfolio');
    Volt::route('market', 'market')->name('market');
    Volt::route('trade/{asset?}', 'trade')->name('trade');
    Volt::route('history', 'history')->name('history');
    Volt::route('wallet', 'wallet')->name('wallet');
    Volt::route('bot-plans', 'bot-plans')->name('bot-plans');
    Route::get('kyc-verification', \App\Livewire\Pages\KycSubmission::class)->name('kyc.verification');

    // Admin Routes
    Route::prefix('admin')->middleware([\App\Http\Middleware\EnsureUserIsAdmin::class])->group(function () {
        Volt::route('wallets', 'admin.wallets')->name('admin.wallets');
        Volt::route('withdrawals', 'admin.withdrawals')->name('admin.withdrawals');
        Route::get('bot-plans', \App\Livewire\Pages\Admin\BotPlans::class)->name('admin.bot-plans');
        Volt::route('deposits', 'admin.deposits')->name('admin.deposits');
        Route::get('users', \App\Livewire\Pages\Admin\UsersList::class)->name('admin.users');
        Route::get('users/create', \App\Livewire\Pages\Admin\CreateUser::class)->name('admin.users.create');
        Route::get('users/{user}', \App\Livewire\Pages\Admin\UserDetails::class)->name('admin.users.show');
        Route::get('kyc-review', \App\Livewire\Pages\Admin\KycReview::class)->name('admin.kyc.review');
        Route::get('kyc-review/{kyc}', \App\Livewire\Pages\Admin\KycDetails::class)->name('admin.kyc.details');
        Route::get('setup-guide', \App\Livewire\Pages\Admin\SetupGuide::class)->name('admin.setup-guide');
    });
});

require __DIR__ . '/settings.php';
