<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\BotPlan;

class BotPlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $plans = [
            [
                'name' => 'Starter AI',
                'description' => 'Perfect for beginners. Low risk, steady returns.',
                'min_amount' => 100,
                'max_amount' => 1000,
                'trades_count' => 50,
                'roi_per_trade' => 0.5, // 0.5% per trade
                'duration_days' => 7,
            ],
            [
                'name' => 'Advanced Trader',
                'description' => 'For experienced investors looking for higher yields.',
                'min_amount' => 1000,
                'max_amount' => 5000,
                'trades_count' => 100,
                'roi_per_trade' => 0.8, // 0.8% per trade
                'duration_days' => 14,
            ],
            [
                'name' => 'Pro Algo',
                'description' => 'Maximum performance with high-frequency trading algorithms.',
                'min_amount' => 5000,
                'max_amount' => null, // Unlimited
                'trades_count' => 200,
                'roi_per_trade' => 1.2, // 1.2% per trade
                'duration_days' => 30,
            ],
        ];

        foreach ($plans as $plan) {
            BotPlan::create($plan);
        }
    }
}
