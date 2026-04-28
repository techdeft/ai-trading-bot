<?php

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\UserBot;

$bots = UserBot::where('status', 'active')->get();

foreach ($bots as $bot) {
    echo "Updating Bot ID: {$bot->id}\n";
    echo "Old Start: {$bot->start_date}\n";

    $bot->start_date = now()->subHours(5); // Move back 5 hours
    $bot->save();

    echo "New Start: {$bot->start_date}\n";
    echo "-------------------\n";
}
