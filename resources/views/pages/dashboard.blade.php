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

        // 1. Portfolio Summary
        $holdings = $portfolioService->getHoldings($user);
        $symbols = array_keys($holdings);

        // Add default market mover symbols if user has no holdings, just to show something
        $marketMoverSymbols = ['BTC-USD', 'ETH-USD', 'SOL-USD', 'TSLA', 'AAPL'];
        $allSymbols = array_unique(array_merge($symbols, $marketMoverSymbols));

        $quotes = $marketData->getQuotes($allSymbols);
        $quotesMap = [];
        foreach ($quotes as $quote) {
            $quotesMap[$quote['symbol']] = $quote;
        }

        // Calculate Net Worth
        $investedValue = 0;
        foreach ($holdings as $symbol => $data) {
            $price = $quotesMap[$symbol]['price'] ?? 0;
            $investedValue += $data['amount'] * $price;
        }
        $totalNetWorth = $investedValue + $user->balance;

        // 2. Recent Activity
        $recentActivity = $portfolioService->getRecents($user, 5);

        // 3. Market Movers
        $marketMovers = collect($marketMoverSymbols)->map(function ($symbol) use ($quotesMap) {
            return $quotesMap[$symbol] ?? [
                'symbol' => $symbol,
                'name' => $symbol,
                'price' => 0,
                'change' => 0
            ];
        });

        return [
            'user' => $user,
            'netWorth' => $totalNetWorth,
            'cashBalance' => $user->balance,
            'recentActivity' => $recentActivity,
            'marketMovers' => $marketMovers,
            'holdingsCount' => count($holdings),
            'investedValue' => $investedValue,
        ];
    }
}; ?>

<div class="p-6 w-full mx-auto space-y-8">
    <!-- Welcome Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 dark:text-white">Welcome back, {{ Auth::user()->name }}</h1>
            <p class="text-slate-500 dark:text-slate-400">Here's what's happening with your portfolio today.</p>
        </div>
        <div>
            <a href="{{ route('trade') }}"
                class="bg-primary hover:bg-primary/90 text-white px-4 py-2 rounded-lg font-bold transition-all flex items-center gap-2">
                <span class="material-symbols-outlined">add_circle</span>
                New Trade
            </a>
        </div>
    </div>

    <!-- Quick Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Net Worth Card -->
        <div
            class="bg-gradient-to-br from-slate-900 to-slate-800 dark:from-surface dark:to-surface-accent rounded-xl p-6 text-white shadow-lg relative overflow-hidden">
            <div class="relative z-10">
                <p class="text-slate-400 text-sm font-medium mb-1">Total Net Worth</p>
                <h2 class="text-3xl font-bold">${{ number_format($netWorth, 2) }}</h2>
                <div class="mt-4 flex items-center gap-2">
                    <span class="bg-white/10 px-2 py-1 rounded text-xs font-bold text-white">
                        ${{ number_format($cashBalance, 2) }} Cash
                    </span>
                    <span class="bg-white/10 px-2 py-1 rounded text-xs font-bold text-white">
                        ${{ number_format($investedValue, 2) }} Invested
                    </span>
                </div>
            </div>
            <!-- Decorative circle -->
            <div class="absolute -right-4 -top-4 w-24 h-24 rounded-full bg-primary/20 blur-xl"></div>
        </div>

        <!-- Portfolio Status -->
        <div class="bg-white dark:bg-surface rounded-xl border border-slate-200 dark:border-slate-700 p-6 shadow-sm">
            <h3 class="text-sm font-bold text-slate-500 dark:text-slate-400 uppercase mb-4">Portfolio Status</h3>
            <div class="flex items-center justify-between mb-2">
                <span class="text-slate-700 dark:text-slate-300">Active Positions</span>
                <span class="font-bold text-slate-900 dark:text-white">{{ $holdingsCount }}</span>
            </div>
            <div class="w-full bg-slate-100 dark:bg-slate-700 rounded-full h-2 mb-4">
                <div class="bg-primary h-2 rounded-full"
                    style="width: {{ $netWorth > 0 ? min(($investedValue / $netWorth) * 100, 100) : 0 }}%"></div>
            </div>
            <p class="text-xs text-slate-500">
                {{ $netWorth > 0 ? number_format(($investedValue / $netWorth) * 100, 1) : 0 }}% allocated to assets
            </p>
        </div>

        <!-- Quick Action -->
        <div
            class="bg-white dark:bg-surface rounded-xl border border-slate-200 dark:border-slate-700 p-6 shadow-sm flex flex-col justify-center items-center text-center">
            <div class="w-12 h-12 bg-success/10 text-success rounded-full flex items-center justify-center mb-3">
                <span class="material-symbols-outlined text-2xl">savings</span>
            </div>
            <h3 class="font-bold text-slate-900 dark:text-white mb-1">Ready to invest?</h3>
            <p class="text-xs text-slate-500 dark:text-slate-400 mb-4">Deposit funds to start trading instantly.</p>
            <a href="{{ route('wallet') }}" class="text-sm font-bold text-primary hover:underline">Go to Wallet
                &rarr;</a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Market Movers -->
        <div class="lg:col-span-2 space-y-6">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-bold text-slate-900 dark:text-white">Market Movers</h3>
                <a href="{{ route('market') }}" class="text-sm font-medium text-primary hover:text-primary/80">View
                    All</a>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($marketMovers as $mover)
                    <a href="{{ route('trade', ['asset' => $mover['symbol']]) }}"
                        class="group bg-white dark:bg-surface border border-slate-200 dark:border-slate-700 rounded-xl p-4 hover:border-primary/50 transition-all shadow-sm hover:shadow-md">
                        <div class="flex justify-between items-start mb-2">
                            <div
                                class="w-8 h-8 rounded-full bg-slate-100 dark:bg-slate-700 flex items-center justify-center font-bold text-xs">
                                {{ substr($mover['symbol'], 0, 1) }}
                            </div>
                            <span class="text-xs font-bold {{ $mover['change'] >= 0 ? 'text-success' : 'text-danger' }}">
                                {{ $mover['change'] >= 0 ? '+' : '' }}{{ $mover['change'] }}%
                            </span>
                        </div>
                        <div>
                            <h4 class="font-bold text-slate-900 dark:text-white group-hover:text-primary transition-colors">
                                {{ $mover['name'] }}
                            </h4>
                            <p class="font-mono text-sm text-slate-500 dark:text-slate-400">
                                ${{ number_format($mover['price'], 2) }}</p>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="lg:col-span-1">
            <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-6">Recent Activity</h3>
            <div
                class="bg-white dark:bg-surface rounded-xl border border-slate-200 dark:border-slate-700 overflow-hidden shadow-sm">
                @if(count($recentActivity) > 0)
                    <div class="divide-y divide-slate-100 dark:divide-slate-800">
                        @foreach($recentActivity as $activity)
                            <div
                                class="p-4 flex items-center gap-3 hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors">
                                <div
                                    class="w-8 h-8 rounded-full flex items-center justify-center text-white text-xs font-bold
                                                        {{ $activity->type === 'buy' || $activity->type === 'deposit' ? 'bg-success' : ($activity->type === 'sell' || $activity->type === 'withdrawal' ? 'bg-danger' : 'bg-primary') }}">
                                    @if($activity->type === 'deposit')
                                        <span class="material-symbols-outlined text-sm">arrow_downward</span>
                                    @elseif($activity->type === 'withdrawal')
                                        <span class="material-symbols-outlined text-sm">arrow_upward</span>
                                    @else
                                        {{ substr(strtoupper($activity->type), 0, 1) }}
                                    @endif
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-bold text-slate-900 dark:text-white truncate">
                                        {{ ucfirst($activity->type) }}
                                        {{ $activity->category === 'transfer' ? 'USD' : $activity->asset_symbol }}
                                    </p>
                                    <p class="text-xs text-slate-500">{{ $activity->created_at->diffForHumans() }}</p>
                                </div>
                                <div class="text-right">
                                    <p
                                        class="text-sm font-bold {{ in_array($activity->type, ['buy', 'sell']) ? 'text-slate-900 dark:text-white' : ($activity->type === 'deposit' ? 'text-success' : 'text-danger') }}">
                                        @if($activity->category === 'transfer')
                                            {{ $activity->type === 'deposit' ? '+' : '-' }}${{ number_format($activity->amount, 2) }}
                                        @else
                                            {{ $activity->amount }} {{ $activity->asset_symbol }}
                                        @endif
                                    </p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="p-8 text-center">
                        <span
                            class="material-symbols-outlined text-4xl text-slate-300 dark:text-slate-600 mb-2">history</span>
                        <p class="text-sm text-slate-500">No recent activity.</p>
                    </div>
                @endif
                <div
                    class="p-3 bg-slate-50 dark:bg-background-dark/50 text-center border-t border-slate-200 dark:border-slate-800">
                    <a href="#" class="text-xs font-bold text-primary hover:underline">View Full History</a>
                </div>
            </div>
        </div>
    </div>
</div>