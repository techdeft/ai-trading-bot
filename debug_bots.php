<?php

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\UserBot;
use App\Models\BotPlan;

$bots = UserBot::where('status', 'active')->with('botPlan')->get();

echo "Active Bots Count: " . $bots->count() . "\n";

foreach ($bots as $bot) {
    echo "Bot ID: {$bot->id}\n";
    echo "Plan: {$bot->botPlan->name}\n";
    echo "Duration: {$bot->botPlan->duration_days} days\n";
    echo "Total Trades: {$bot->botPlan->trades_count}\n";
    echo "Start Date: {$bot->start_date}\n";

    $totalMinutes = $bot->botPlan->duration_days * 24 * 60;
    $interval = $totalMinutes / $bot->botPlan->trades_count;
    echo "Calculated Interval: {$interval} minutes\n";

    $lastTrade = $bot->tradeLogs()->latest()->first();
    $lastTradeTime = $lastTrade ? $lastTrade->created_at : $bot->start_date;
    echo "Last Trade/Start: {$lastTradeTime}\n";

    $diff = now()->diffInMinutes($lastTradeTime);
    echo "Minutes since last: {$diff}\n";

    if ($diff >= $interval) {
        echo "STATUS: SHOULD TRADE\n";
    } else {
        echo "STATUS: WAITING (Need {$interval} mins, have {$diff})\n";
    }
    echo "-------------------\n";
}
