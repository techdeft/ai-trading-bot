<?php

use App\Models\User;
use App\Models\Deposit;
use App\Models\Withdrawal;
use App\Models\UserBot;

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$users = User::all();

echo "\n--- USERS ---\n";
foreach ($users as $user) {
    echo "ID: {$user->id} | Email: {$user->email} | Balance: \${$user->balance}\n";

    $deposits = Deposit::where('user_id', $user->id)->sum('amount');
    $withdrawals = Withdrawal::where('user_id', $user->id)->sum('amount');
    $bots = UserBot::where('user_id', $user->id)->where('status', 'active')->sum('amount');

    echo "  Total Deposits: \${$deposits}\n";
    echo "  Total Withdrawals: \${$withdrawals}\n";
    echo "  Active Bot Capital: \${$bots}\n";
    echo "  Expected Balance: " . ($deposits - $withdrawals - $bots) . "\n\n";
}
