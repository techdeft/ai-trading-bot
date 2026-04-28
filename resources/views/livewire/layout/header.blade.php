<?php

use Livewire\Volt\Component;
use App\Services\MarketDataService;
use App\Services\PortfolioService;
use Illuminate\Support\Facades\Auth;

new class extends Component {
    public function with(MarketDataService $marketData, PortfolioService $portfolioService): array
    {
        $user = Auth::user();
        if (!$user) {
            return [
                'netWorth' => 0,
                'changePercent' => 0,
                'btcEquivalent' => 0,
            ];
        }

        $holdings = $portfolioService->getHoldings($user);
        $symbols = array_keys($holdings);

        // Always fetch BTC for conversion
        if (!in_array('BTC-USD', $symbols)) {
            $symbols[] = 'BTC-USD';
        }

        $quotes = $marketData->getQuotes($symbols);
        $quotesMap = [];
        foreach ($quotes as $quote) {
            $quotesMap[$quote['symbol']] = $quote;
        }

        $investedValue = 0;
        $weightedChangeSum = 0;

        foreach ($holdings as $symbol => $data) {
            $quote = $quotesMap[$symbol] ?? ['price' => 0, 'change' => 0];
            $value = $data['amount'] * $quote['price'];
            $investedValue += $value;
            $weightedChangeSum += $value * ($quote['change'] ?? 0);
        }

        $netWorth = $investedValue + $user->balance;

        // Calculate weighted change for the whole portfolio (cash has 0% change)
        $portfolioChangePercent = $netWorth > 0 ? ($weightedChangeSum / $netWorth) : 0;

        // BTC Equivalent
        $btcPrice = $quotesMap['BTC-USD']['price'] ?? 1; // Avoid divide by zero
        $btcEquivalent = $btcPrice > 0 ? ($netWorth / $btcPrice) : 0;

        return [
            'netWorth' => $netWorth,
            'changePercent' => $portfolioChangePercent,
            'btcEquivalent' => $btcEquivalent,
        ];
    }
}; ?>

<header
    class="sticky top-0 z-10 flex items-center justify-between px-8 py-4 bg-background-light/80 dark:bg-background-dark/80 backdrop-blur-md border-b border-slate-200 dark:border-slate-800">
    <div class="flex flex-col">
        <h2 class="text-sm font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">
            Portfolio Overview</h2>
        <div class="flex items-center gap-3 mt-1">
            <span class="text-2xl font-bold">${{ number_format($netWorth, 2) }}</span>
            <span
                class="flex items-center gap-1 text-xs font-bold px-2 py-0.5 rounded-full uppercase {{ $changePercent >= 0 ? 'bg-success/20 text-success' : 'bg-danger/20 text-danger' }}">
                <span
                    class="material-symbols-outlined text-sm">{{ $changePercent >= 0 ? 'trending_up' : 'trending_down' }}</span>
                {{ number_format(abs($changePercent), 2) }}%
            </span>
        </div>
    </div>
    <div class="flex items-center gap-4">
        <div class="hidden lg:flex flex-col items-end pr-4 border-r border-slate-200 dark:border-slate-800">
            <span class="text-xs text-slate-500">BTC Equivalent</span>
            <span class="text-sm font-mono font-semibold">{{ number_format($btcEquivalent, 6) }} BTC</span>
        </div>
        <a href="{{ route('portfolio') }}"
            class="bg-primary hover:bg-primary/90 text-white px-4 py-2 rounded-lg font-semibold text-sm transition-all flex items-center gap-2">
            <span class="material-symbols-outlined text-lg">add</span>
            Deposit
        </a>
    </div>
</header>