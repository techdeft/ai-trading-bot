<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Transaction;
use App\Models\Deposit;
use App\Models\Withdrawal;
use Carbon\Carbon;

class UserHistorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get the first user (usually the admin/dev)
        $user = User::first();

        if (!$user) {
            $this->command->info('No user found to seed history for.');
            return;
        }

        $this->command->info("Seeding history for user: {$user->email}");

        // Get or create a wallet for the user
        $wallet = \App\Models\Wallet::firstOrCreate(
            ['user_id' => $user->id, 'token' => 'USDT'],
            ['network' => 'TRC20', 'address' => '0x' . md5(uniqid())]
        );

        // 1. Create a large initial deposit 30 days ago
        Deposit::create([
            'user_id' => $user->id,
            'wallet_id' => $wallet->id,
            'amount' => 50000,
            'transaction_hash' => '0x' . md5(uniqid()),
            'status' => 'approved', // changed to approved so it shows as completed/successful
            'created_at' => Carbon::now()->subDays(30),
        ]);

        // 2. Create some random trades over the last month
        $assets = ['BTC', 'ETH', 'SOL', 'AVAX', 'DOT'];

        for ($i = 0; $i < 15; $i++) {
            $type = rand(0, 1) ? 'buy' : 'sell';
            $amount = rand(1, 10) / 10; // 0.1 to 1.0
            $price = rand(2000, 60000);
            $total = $amount * $price;
            $date = Carbon::now()->subDays(rand(1, 29));

            Transaction::create([
                'user_id' => $user->id,
                'type' => $type,
                'asset_symbol' => $assets[array_rand($assets)],
                'amount' => $amount,
                'price' => $price,
                'total' => $total,
                'status' => 'completed',
                'created_at' => $date,
            ]);
        }

        // 3. Create a recent withdrawal
        Withdrawal::create([
            'user_id' => $user->id,
            'amount' => 2500,
            'token' => 'USDT',
            'wallet_address' => '0x' . md5(uniqid()),
            'status' => 'completed',
            'created_at' => Carbon::now()->subDays(2),
        ]);

        // 4. Create an Active Bot (Deduct from balance)
        $plan = \App\Models\BotPlan::first();
        if (!$plan) {
            $plan = \App\Models\BotPlan::create([
                'name' => 'AI Starter',
                'description' => 'Low risk, stable returns',
                'min_amount' => 100,
                'max_amount' => 5000,
                'trades_count' => 30,
                'roi_per_trade' => 0.5,
                'duration_days' => 30,
            ]);
        }

        if ($plan) {
            $botAmount = 5000;
            $bot = \App\Models\UserBot::create([
                'user_id' => $user->id,
                'bot_plan_id' => $plan->id,
                'amount' => $botAmount,
                'status' => 'active',
                'start_date' => Carbon::now()->subDays(5),
                'end_date' => Carbon::now()->addDays($plan->duration_days - 5),
                'total_profit' => 150.50, // Fake accumulated profit (not in wallet yet)
                'trades_completed' => 5,
            ]);

            // Create some trade logs for this bot
            for ($j = 0; $j < 5; $j++) {
                \App\Models\BotTradeLog::create([
                    'user_bot_id' => $bot->id,
                    'profit' => 30.10,
                    'roi_percentage' => 0.6,
                    'created_at' => Carbon::now()->subDays(5 - $j),
                ]);
            }
        } else {
            $botAmount = 0;
        }

        // 5. Update User Balance to match history
        // 50,000 (Deposit) - 2,500 (Withdrawal) - 5,000 (Bot) = 42,500
        $user->balance = 50000 - 2500 - $botAmount;
        $user->save();

        $this->command->info("User balance updated to: {$user->balance}");

        $this->command->info('User history seeded successfully.');
    }
}
