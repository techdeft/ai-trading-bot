<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Wallet;
use App\Models\Withdrawal;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class WalletSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Create Admin User
        User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin User',
                'password' => bcrypt('password'),
                'email_verified_at' => now(),
                'is_admin' => true,
            ]
        );

        // 2. Create Regular User
        $user = User::firstOrCreate(
            ['email' => 'user@example.com'],
            [
                'name' => 'Regular User',
                'password' => bcrypt('password'),
                'email_verified_at' => now(),
                'balance' => 5000.00,
            ]
        );

        // 3. Create Unassigned Wallets (The Pool)
        $tokens = ['USDT', 'BTC'];
        foreach ($tokens as $token) {
            for ($i = 0; $i < 5; $i++) {
                Wallet::create([
                    'address' => $this->generateAddress($token),
                    'network' => $token === 'USDT' ? 'TRC20' : 'BTC',
                    'token' => $token,
                    'user_id' => null, // Unassigned
                ]);
            }
        }

        // 4. Assign Wallets to Regular User (Simulate Registration assignment)
        foreach ($tokens as $token) {
            if (!$user->wallets()->where('token', $token)->exists()) {
                $wallet = Wallet::whereNull('user_id')->where('token', $token)->first();
                if ($wallet) {
                    $wallet->update(['user_id' => $user->id]);
                }
            }
        }

        // 5. Create some dummy withdrawals
        Withdrawal::create([
            'user_id' => $user->id,
            'amount' => 100.50,
            'token' => 'USDT',
            'wallet_address' => 'T9yK5...',
            'status' => 'pending',
        ]);

        Withdrawal::create([
            'user_id' => $user->id,
            'amount' => 0.05,
            'token' => 'BTC',
            'wallet_address' => '1BvBM...',
            'status' => 'approved',
        ]);
    }

    private function generateAddress($token)
    {
        return $token === 'USDT'
            ? 'T' . Str::random(33)
            : '1' . Str::random(33);
    }
}
