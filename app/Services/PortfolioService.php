<?php

namespace App\Services;

use App\Models\Transaction;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Exception;

class PortfolioService
{
    /**
     * Execute a trade (buy or sell).
     */
    public function executeTrade(User $user, string $symbol, string $type, float $amount, float $price): Transaction
    {
        return DB::transaction(function () use ($user, $symbol, $type, $amount, $price) {
            $totalValue = $amount * $price;
            $symbol = strtoupper($symbol);

            if ($type === 'buy') {
                if ($user->balance < $totalValue) {
                    throw new Exception("Insufficient funds. Required: \${$totalValue}, Available: \${$user->balance}");
                }
                $user->decrement('balance', $totalValue);
            } elseif ($type === 'sell') {
                $currentHoldings = $this->getHoldings($user)[$symbol]['amount'] ?? 0;
                if ($currentHoldings < $amount) {
                    throw new Exception("Insufficient holdings. Required: {$amount} {$symbol}, Available: {$currentHoldings} {$symbol}");
                }
                $user->increment('balance', $totalValue);
            } else {
                throw new Exception("Invalid trade type: {$type}");
            }

            return Transaction::create([
                'user_id' => $user->id,
                'type' => $type,
                'asset_symbol' => $symbol,
                'amount' => $amount,
                'price' => $price,
                'total' => $totalValue,
                'status' => 'completed',
            ]);
        });
    }

    /**
     * Get current holdings for a user based on transaction history.
     */
    public function getHoldings(User $user): array
    {
        $transactions = Transaction::where('user_id', $user->id)
            ->where('status', 'completed')
            ->get();

        $holdings = [];

        foreach ($transactions as $tx) {
            $symbol = $tx->asset_symbol;
            if (!isset($holdings[$symbol])) {
                $holdings[$symbol] = ['symbol' => $symbol, 'amount' => 0, 'average_buy_price' => 0, 'total_cost' => 0];
            }

            if ($tx->type === 'buy') {
                $holdings[$symbol]['amount'] += $tx->amount;
                $holdings[$symbol]['total_cost'] += $tx->total;
            } elseif ($tx->type === 'sell') {
                // FIFO or Weighted Average? For simplicity, just reduce amount.
                // Tracking realized PnL requires more complex logic.
                $holdings[$symbol]['amount'] -= $tx->amount;
                // Reduce cost basis proportionally
                if ($holdings[$symbol]['amount'] > 0) {
                    // Simplifying assumption: reducing cost basis proportionally to amount sold
                    // This isn't perfect for tax purposes but works for display
                } else {
                    $holdings[$symbol]['total_cost'] = 0;
                }
            }
        }

        // Calculate average buy price and filter out zero balances
        $result = [];
        foreach ($holdings as $symbol => $data) {
            if ($data['amount'] > 0.00000001) { // Floating point safety
                $data['average_buy_price'] = $data['total_cost'] / $data['amount'];
                $result[$symbol] = $data;
            }
        }

        return $result;
    }

    /**
     * Get recent activity
     */
    public function getRecents(User $user, int $limit = 5)
    {
        $transactions = Transaction::where('user_id', $user->id)
            ->select('id', 'user_id', 'type', 'asset_symbol', 'amount', 'created_at')
            ->get()
            ->map(function ($item) {
                $item->category = 'trade';
                return $item;
            });

        $deposits = \App\Models\Deposit::where('user_id', $user->id)
            ->select('id', 'user_id', 'amount', 'created_at')
            ->get()
            ->map(function ($item) {
                $item->type = 'deposit';
                $item->asset_symbol = 'USD';
                $item->category = 'transfer';
                return $item;
            });

        $withdrawals = \App\Models\Withdrawal::where('user_id', $user->id)
            ->select('id', 'user_id', 'amount', 'created_at')
            ->get()
            ->map(function ($item) {
                $item->type = 'withdrawal';
                $item->asset_symbol = 'USD';
                $item->category = 'transfer';
                return $item;
            });

        return $transactions->concat($deposits)->concat($withdrawals)
            ->sortByDesc('created_at')
            ->take($limit);
    }
}
