<?php

namespace App\Livewire\Pages\Admin;

use App\Models\BotPlan;
use App\Models\BotTradeLog;
use App\Models\User;
use App\Models\Wallet;
use Livewire\Component;

class SetupGuide extends Component
{
    public function render()
    {
        $status = [
            'wallets' => Wallet::count() > 0,
            'bot_plans' => BotPlan::count() > 0,
            'users' => User::count() > 1, // More than just the admin
            'scheduler' => BotTradeLog::where('created_at', '>=', now()->subDay())->exists(),
            'debug_mode' => config('app.debug'),
            'app_url' => config('app.url'),
            'db_connection' => config('database.default'),
        ];

        return view('livewire.pages.admin.setup-guide', [
            'status' => $status
        ])->layout('layouts.app');
    }
}
