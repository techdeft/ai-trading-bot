<?php

use Livewire\Volt\Component;
use Livewire\Attributes\Layout;
use App\Services\MarketDataService;
use App\Services\PortfolioService;
use Illuminate\Support\Facades\Auth;

new #[Layout('layouts.app')] class extends Component {
    public function with(MarketDataService $marketData, PortfolioService $portfolioService): array
    {
        $user = Auth::user();
        $holdings = $portfolioService->getHoldings($user);

        $symbols = array_keys($holdings);
        $quotes = $marketData->getQuotes($symbols);
        $quotesMap = [];
        foreach ($quotes as $quote) {
            $quotesMap[$quote['symbol']] = $quote;
        }

        $enrichedPortfolio = collect($holdings)->map(function ($asset) use ($quotesMap) {
            $quote = $quotesMap[$asset['symbol']] ?? null;
            $price = $quote['price'] ?? 0;
            $value = $asset['amount'] * $price;

            return array_merge($asset, [
                'name' => $quote['name'] ?? $asset['symbol'],
                'price' => $price,
                'value' => $value,
                'change' => $quote['change'] ?? 0,
            ]);
        });

        $investedValue = $enrichedPortfolio->sum('value');
        $totalBalance = $investedValue + $user->balance;

        return [
            'portfolio' => $enrichedPortfolio,
            'cashBalance' => $user->balance,
            'investedValue' => $investedValue,
            'totalValue' => $totalBalance,
            'totalChange' => $enrichedPortfolio->avg('change') ?? 0,
        ];
    }
    public function deposit()
    {
        Auth::user()->increment('balance', 10000);
        $this->dispatch('balance-updated'); // Optional
        // No flash needed, UI updates automatically due to reactivity? 
        // Volt/Livewire re-renders on action.
    }
}; ?>

<div class="p-6 w-full mx-auto space-y-8">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 dark:text-white">Your Portfolio</h1>
            <p class="text-slate-500 dark:text-slate-400">Track your crypto and stock assets in real-time.</p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('wallet') }}"
                class="px-4 py-2 bg-primary hover:bg-primary/90 text-white rounded-lg font-medium transition-colors flex items-center gap-2">
                <span class="material-symbols-outlined text-xl">add</span>
                Deposit Funds
            </a>
            <button
                class="px-4 py-2 bg-white dark:bg-surface border border-slate-200 dark:border-slate-700 text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800 rounded-lg font-medium transition-colors">
                Withdraw
            </button>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Total Net Worth -->
        <div class="bg-white dark:bg-surface rounded-xl border border-slate-200 dark:border-slate-700 p-6 shadow-sm">
            <div class="flex justify-between items-start mb-4">
                <div class="p-2 bg-primary/10 rounded-lg text-primary">
                    <span class="material-symbols-outlined">account_balance_wallet</span>
                </div>
                <span
                    class="flex items-center gap-1 text-xs font-bold px-2 py-0.5 rounded-full bg-success/10 text-success">
                    <span class="material-symbols-outlined text-sm">trending_up</span>
                    +{{ number_format($totalChange, 2) }}%
                </span>
            </div>
            <p class="text-sm text-slate-500 dark:text-slate-400 font-medium">Total Net Worth</p>
            <h3 class="text-2xl font-bold text-slate-900 dark:text-white mt-1">
                ${{ number_format($totalValue, 2) }}</h3>
        </div>

        <!-- Available Cash -->
        <div class="bg-white dark:bg-surface rounded-xl border border-slate-200 dark:border-slate-700 p-6 shadow-sm">
            <div class="flex justify-between items-start mb-4">
                <div class="p-2 bg-success/10 rounded-lg text-success">
                    <span class="material-symbols-outlined">payments</span>
                </div>
            </div>
            <p class="text-sm text-slate-500 dark:text-slate-400 font-medium">Available Cash</p>
            <h3 class="text-2xl font-bold text-success mt-1">${{ number_format($cashBalance, 2) }}</h3>
        </div>

        <!-- Active Positions -->
        <div class="bg-white dark:bg-surface rounded-xl border border-slate-200 dark:border-slate-700 p-6 shadow-sm">
            <div class="flex justify-between items-start mb-4">
                <div class="p-2 bg-purple-500/10 rounded-lg text-purple-500">
                    <span class="material-symbols-outlined">pie_chart</span>
                </div>
            </div>
            <p class="text-sm text-slate-500 dark:text-slate-400 font-medium">Active Assets</p>
            <h3 class="text-2xl font-bold text-slate-900 dark:text-white mt-1">{{ count($portfolio) }}</h3>
        </div>
    </div>

    <!-- Assets Table -->
    <div
        class="bg-white dark:bg-surface rounded-xl border border-slate-200 dark:border-slate-700 overflow-hidden shadow-sm">
        <div class="p-6 border-b border-slate-200 dark:border-slate-700">
            <h2 class="text-lg font-bold text-slate-900 dark:text-white">Your Assets</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-slate-50 dark:bg-background-dark/50">
                    <tr>
                        <th
                            class="px-6 py-4 text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">
                            Asset</th>
                        <th
                            class="px-6 py-4 text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider text-right">
                            Balance</th>
                        <th
                            class="px-6 py-4 text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider text-right">
                            Price</th>
                        <th
                            class="px-6 py-4 text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider text-right">
                            Value</th>
                        <th
                            class="px-6 py-4 text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider text-right">
                            24h Change</th>
                        <th
                            class="px-6 py-4 text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider text-right">
                            Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 dark:divide-slate-700">
                    @foreach($portfolio as $asset)
                        <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div
                                        class="w-8 h-8 rounded-full bg-slate-100 dark:bg-slate-700 flex items-center justify-center font-bold text-xs text-slate-600 dark:text-slate-300">
                                        {{ $asset['symbol'][0] }}
                                    </div>
                                    <div>
                                        <p class="font-medium text-slate-900 dark:text-white">{{ $asset['name'] }}</p>
                                        <p class="text-xs text-slate-500">{{ $asset['symbol'] }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-right font-medium text-slate-700 dark:text-slate-300">
                                {{ $asset['amount'] }} {{ $asset['symbol'] }}
                            </td>
                            <td class="px-6 py-4 text-right font-medium text-slate-700 dark:text-slate-300">
                                ${{ number_format($asset['price'], 2) }}
                            </td>
                            <td class="px-6 py-4 text-right font-bold text-slate-900 dark:text-white">
                                ${{ number_format($asset['value'], 2) }}
                            </td>
                            <td class="px-6 py-4 text-right">
                                <span
                                    class="inline-flex items-center px-2 py-1 rounded text-xs font-bold {{ $asset['change'] >= 0 ? 'bg-success/10 text-success' : 'bg-danger/10 text-danger' }}">
                                    {{ $asset['change'] >= 0 ? '+' : '' }}{{ $asset['change'] }}%
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <a href="{{ route('trade', ['asset' => $asset['symbol']]) }}"
                                    class="text-sm font-medium text-primary hover:text-primary/80 transition-colors">Trade</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>