<?php

namespace App\Livewire\Pages\Admin;

use App\Models\BotPlan;
use App\Models\BotTradeLog;
use App\Models\Transaction;
use App\Models\User;
use App\Models\UserBot;
use App\Models\Wallet;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;
use Illuminate\Support\Str;
use Carbon\Carbon;

class CreateUser extends Component
{
    public $name;
    public $email;
    public $password;
    public $password_confirmation;

    // Simulation
    public $simulate_data = false;
    public $simulation_balance = 10000;
    public $simulation_duration_days = 30;

    protected $rules = [
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email',
        'password' => 'required|string|min:8|confirmed',
        'simulation_balance' => 'required_if:simulate_data,true|numeric|min:0',
        'simulation_duration_days' => 'required_if:simulate_data,true|integer|min:1|max:365',
    ];

    public function createUser()
    {
        $this->validate();

        // Calculate start date first to use for user creation if simulating
        $startDate = $this->simulate_data ? Carbon::now()->subDays($this->simulation_duration_days) : Carbon::now();

        $user = new User([
            'name' => $this->name,
            'email' => $this->email,
            'password' => Hash::make($this->password),
            'balance' => 0, // Will be updated if simulated
            'kyc_status' => 'unverified',
        ]);

        if ($this->simulate_data) {
            $user->timestamps = false;
            $user->created_at = $startDate;
            $user->updated_at = $startDate;
        }

        $user->save();

        if ($this->simulate_data) {
            $this->simulateData($user, $startDate);
        }

        session()->flash('message', 'User created successfully' . ($this->simulate_data ? ' with simulated data.' : '.'));
        return redirect()->route('admin.users');
    }

    protected function simulateData(User $user, Carbon $startDate)
    {
        // 1. Create Wallets (Backdated)
        $this->createBackdatedWallet($user, 'Bitcoin', 'BTC', 'bc1q' . Str::random(38), $startDate);
        $this->createBackdatedWallet($user, 'Ethereum', 'ETH', '0x' . Str::random(40), $startDate);
        $this->createBackdatedWallet($user, 'TRON', 'USDT', 'T' . Str::random(33), $startDate);

        // 2. Initial Deposit (Past Dated)
        $tx = new Transaction([
            'user_id' => $user->id,
            'type' => 'deposit',
            'asset_symbol' => 'USDT',
            'amount' => $this->simulation_balance,
            'price' => 1,
            'total' => $this->simulation_balance,
            'status' => 'completed',
        ]);
        $tx->timestamps = false; // Disable auto timestamps
        $tx->created_at = $startDate;
        $tx->updated_at = $startDate;
        $tx->save();

        // Update User Balance
        $user->balance = $this->simulation_balance;
        $user->save();

        // 3. Simulate Bot Activity
        $plan = BotPlan::first();
        if (!$plan)
            return;

        // Create a bot started in the past
        $userBot = new UserBot([
            'user_id' => $user->id,
            'bot_plan_id' => $plan->id,
            'amount' => $this->simulation_balance, // Invested entire balance for simplicity
            'trades_completed' => 0,
            'total_profit' => 0,
            'start_date' => $startDate,
            'end_date' => $startDate->copy()->addDays($plan->duration_days),
            'status' => 'active',
        ]);
        $userBot->timestamps = false;
        $userBot->created_at = $startDate;
        $userBot->updated_at = $startDate;
        $userBot->save();

        // Generate daily trades up to today
        $currentDate = $startDate->copy();
        $totalProfit = 0;
        $tradesCount = 0;

        while ($currentDate->lte(Carbon::now()) && $tradesCount < $plan->trades_count) {
            // Random ROI between min and max (simulated) or just fixed decent ROI
            $roi = mt_rand(50, 150) / 100; // 0.5% to 1.5%
            $profit = ($userBot->amount * $roi) / 100;

            $log = new BotTradeLog([
                'user_bot_id' => $userBot->id,
                'profit' => $profit,
                'roi_percentage' => $roi,
            ]);
            $log->timestamps = false;
            $log->created_at = $currentDate;
            $log->updated_at = $currentDate;
            $log->save();

            $totalProfit += $profit;
            $tradesCount++;
            $currentDate->addDay(); // One trade per day
        }

        // Update Bot stats
        $userBot->trades_completed = $tradesCount;
        $userBot->total_profit = $totalProfit;
        $userBot->save(); // Timestamps might update here to 'now', which is fine/correct for 'updated_at' but let's check.
        // Actually, we want 'updated_at' to be now since we just updated it?
        // Or should it be the last trade date?
        // Let's leave it as is, standard save() behavior is fine for updated_at here.

        // Update User Balance with Profit
        $user->balance += $totalProfit;
        $user->save();
    }

    protected function createBackdatedWallet($user, $network, $token, $address, $date)
    {
        $wallet = new Wallet([
            'user_id' => $user->id,
            'network' => $network,
            'token' => $token,
            'address' => $address,
        ]);
        $wallet->timestamps = false;
        $wallet->created_at = $date;
        $wallet->updated_at = $date;
        $wallet->save();
    }

    public function render()
    {
        return view('livewire.pages.admin.create-user')->layout('layouts.app');
    }
}
