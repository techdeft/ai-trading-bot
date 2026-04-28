<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class SimulateBotTrades extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bot:simulate-trades';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Simulate trades for active user bots based on plan configuration';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $activeBots = \App\Models\UserBot::where('status', 'active')->with('botPlan')->get();

        foreach ($activeBots as $bot) {
            $this->processBot($bot);
        }
    }

    private function processBot($bot)
    {
        // 1. Calculate Trade Interval
        $totalDurationMinutes = $bot->botPlan->duration_days * 24 * 60;
        $totalTrades = $bot->botPlan->trades_count;

        if ($totalTrades <= 0)
            return;

        // Spread trades evenly over the duration
        $intervalMinutes = $totalDurationMinutes / $totalTrades;

        // 2. Check last trade time
        $lastTrade = $bot->tradeLogs()->latest()->first();
        $lastTradeTime = $lastTrade ? $lastTrade->created_at : $bot->start_date;

        // Ensure strictly positive elapsed time
        $diff = abs(now()->diffInMinutes($lastTradeTime));

        // If enough time has passed since last trade (or start)
        if ($diff >= $intervalMinutes) {
            $this->executeTrade($bot);
        }
    }

    private function executeTrade($bot)
    {
        \Illuminate\Support\Facades\DB::transaction(function () use ($bot) {
            // 3. Calculate Random ROI
            // Base ROI from plan (e.g. 0.5%)
            $baseRoi = $bot->botPlan->roi_per_trade;

            // Randomize between 80% and 120% of base ROI for "organic" feel
            // e.g. 0.5% * 0.8 = 0.4%, 0.5% * 1.2 = 0.6%
            $randomFactor = mt_rand(80, 120) / 100;
            $startRoi = $baseRoi * $randomFactor;

            // Allow negative trades occasionally? For now, keep it positive as per request "profit"
            // Ensure strictly positive for "profit" unless we want loss simulation
            $actualRoi = max(0.01, $startRoi);

            $profit = $bot->amount * ($actualRoi / 100);

            // 4. Create Log
            \App\Models\BotTradeLog::create([
                'user_bot_id' => $bot->id,
                'profit' => $profit,
                'roi_percentage' => $actualRoi,
            ]);

            // 5. Update Bot Stats
            $bot->total_profit += $profit;
            $bot->trades_completed += 1;

            // 6. Update User Balance (Credit Profit immediately) - REMOVED
            // Profits are held in the bot until completion
            // $user = $bot->user;
            // $user->balance += $profit;
            // $user->save();

            // 7. Check Completion
            if ($bot->trades_completed >= $bot->botPlan->trades_count) {
                $bot->status = 'completed';
                $bot->end_date = now();

                // Return Capital + Total Profit
                $user = $bot->user;
                $user->balance += ($bot->amount + $bot->total_profit);
                $user->save();

                $this->info("Bot #{$bot->id} completed! Capital + Profit returned.");
            }

            $bot->save();

            $this->info("Trade executed for Bot #{$bot->id}: ROI {$actualRoi}%, Profit {$profit}");
        });
    }
}
