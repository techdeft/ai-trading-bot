<?php

use Livewire\Volt\Component;
use Livewire\Attributes\Layout;
use App\Services\MarketDataService;

new #[Layout('layouts.app')] class extends Component {
    public string $search = '';
    public string $filter = 'all'; // all, crypto, stocks, etf

    // Base list of assets to track
    public array $trackedAssets = [
        ['symbol' => 'BTC-USD', 'name' => 'Bitcoin', 'type' => 'crypto'],
        ['symbol' => 'ETH-USD', 'name' => 'Ethereum', 'type' => 'crypto'],
        ['symbol' => 'TSLA', 'name' => 'Tesla Inc.', 'type' => 'stock'],
        ['symbol' => 'AAPL', 'name' => 'Apple Inc.', 'type' => 'stock'],
        ['symbol' => 'SOL-USD', 'name' => 'Solana', 'type' => 'crypto'],
        ['symbol' => 'NVDA', 'name' => 'NVIDIA Corp', 'type' => 'stock'],
        ['symbol' => 'SPY', 'name' => 'SPDR S&P 500', 'type' => 'etf'],
    ];

    public function with(MarketDataService $marketData): array
    {
        // Fetch live data for all tracked assets
        $symbols = array_column($this->trackedAssets, 'symbol');

        // In a real app, you'd likely fetch these in batches or cache heavily
        // For now, we'll fetch them individually or via a batch method if we implemented it
        // The service's getQuotes method should handle this efficiently

        $quotes = $marketData->getQuotes($symbols);
        $quotesMap = [];
        foreach ($quotes as $quote) {
            $quotesMap[$quote['symbol']] = $quote;
        }

        $assets = collect($this->trackedAssets)->map(function ($asset) use ($quotesMap) {
            $quote = $quotesMap[$asset['symbol']] ?? null;
            return [
                'symbol' => $asset['symbol'],
                'name' => $asset['name'],
                'type' => $asset['type'],
                'price' => $quote['price'] ?? 0,
                'change' => $quote['change'] ?? 0,
                'volume' => $this->formatVolume($quote['volume'] ?? 0),
                'marketCap' => $this->formatVolume($quote['marketCap'] ?? 0),
            ];
        });

        $filteredAssets = $assets
            ->filter(function ($asset) {
                if ($this->filter !== 'all' && $asset['type'] !== $this->filter) {
                    return false;
                }
                if ($this->search && stripos($asset['name'], $this->search) === false && stripos($asset['symbol'], $this->search) === false) {
                    return false;
                }
                return true;
            });

        return [
            'filteredAssets' => $filteredAssets,
        ];
    }

    private function formatVolume($number)
    {
        if ($number >= 1e12)
            return round($number / 1e12, 2) . 'T';
        if ($number >= 1e9)
            return round($number / 1e9, 2) . 'B';
        if ($number >= 1e6)
            return round($number / 1e6, 2) . 'M';
        if ($number >= 1e3)
            return round($number / 1e3, 2) . 'K';
        return (string) $number;
    }
}; ?>

<div class="p-6 w-full mx-auto space-y-8">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 dark:text-white">Market Overview</h1>
            <p class="text-slate-500 dark:text-slate-400">Live prices for Crypto, Stocks, and ETFs.</p>
        </div>
    </div>

    <!-- Filters & Search -->
    <div
        class="flex flex-col md:flex-row gap-4 justify-between items-center bg-white dark:bg-surface p-4 rounded-xl border border-slate-200 dark:border-slate-700 shadow-sm">
        <div class="flex gap-2 p-1 bg-slate-100 dark:bg-background-dark rounded-lg">
            <button wire:click="$set('filter', 'all')"
                class="px-4 py-2 text-sm font-medium rounded-md transition-all {{ $filter === 'all' ? 'bg-white dark:bg-surface text-primary shadow-sm' : 'text-slate-500 dark:text-slate-400 hover:text-slate-700 dark:hover:text-slate-200' }}">All</button>
            <button wire:click="$set('filter', 'crypto')"
                class="px-4 py-2 text-sm font-medium rounded-md transition-all {{ $filter === 'crypto' ? 'bg-white dark:bg-surface text-primary shadow-sm' : 'text-slate-500 dark:text-slate-400 hover:text-slate-700 dark:hover:text-slate-200' }}">Crypto</button>
            <button wire:click="$set('filter', 'stock')"
                class="px-4 py-2 text-sm font-medium rounded-md transition-all {{ $filter === 'stock' ? 'bg-white dark:bg-surface text-primary shadow-sm' : 'text-slate-500 dark:text-slate-400 hover:text-slate-700 dark:hover:text-slate-200' }}">Stocks</button>
            <button wire:click="$set('filter', 'etf')"
                class="px-4 py-2 text-sm font-medium rounded-md transition-all {{ $filter === 'etf' ? 'bg-white dark:bg-surface text-primary shadow-sm' : 'text-slate-500 dark:text-slate-400 hover:text-slate-700 dark:hover:text-slate-200' }}">ETFs</button>
        </div>

        <div class="relative w-full md:w-64">
            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-slate-500 dark:text-slate-400">
                <span class="material-symbols-outlined text-xl">search</span>
            </span>
            <input wire:model.live="search" type="text" placeholder="Search assets..."
                class="pl-10 w-full rounded-lg border-slate-300 dark:border-slate-700 bg-white dark:bg-background-dark text-slate-900 dark:text-white focus:ring-primary focus:border-primary">
        </div>
    </div>

    <!-- Market Table -->
    <div
        class="bg-white dark:bg-surface rounded-xl border border-slate-200 dark:border-slate-700 overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-slate-50 dark:bg-background-dark/50">
                    <tr>
                        <th
                            class="px-6 py-4 text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">
                            Name</th>
                        <th
                            class="px-6 py-4 text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider text-right">
                            Price</th>
                        <th
                            class="px-6 py-4 text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider text-right">
                            24h Change</th>
                        <th
                            class="px-6 py-4 text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider text-right hidden sm:table-cell">
                            Market Cap</th>
                        <th
                            class="px-6 py-4 text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider text-right hidden md:table-cell">
                            Volume (24h)</th>
                        <th
                            class="px-6 py-4 text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider text-right">
                            Trade</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 dark:divide-slate-700">
                    @foreach($filteredAssets as $asset)
                        <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div
                                        class="w-10 h-10 rounded-full {{ $asset['type'] === 'crypto' ? 'bg-orange-500/10 text-orange-500' : ($asset['type'] === 'stock' ? 'bg-blue-500/10 text-blue-500' : 'bg-purple-500/10 text-purple-500') }} flex items-center justify-center font-bold">
                                        <span
                                            class="material-symbols-outlined">{{ $asset['type'] === 'crypto' ? 'currency_bitcoin' : ($asset['type'] === 'stock' ? 'show_chart' : 'pie_chart') }}</span>
                                    </div>
                                    <div>
                                        <p class="font-bold text-slate-900 dark:text-white">{{ $asset['name'] }}</p>
                                        <span
                                            class="text-xs uppercase px-2 py-0.5 rounded {{ $asset['type'] === 'crypto' ? 'bg-orange-100 dark:bg-orange-500/20 text-orange-600 dark:text-orange-400' : ($asset['type'] === 'stock' ? 'bg-blue-100 dark:bg-blue-500/20 text-blue-600 dark:text-blue-400' : 'bg-purple-100 dark:bg-purple-500/20 text-purple-600 dark:text-purple-400') }}">
                                            {{ $asset['type'] }}
                                        </span>
                                        <span class="text-xs text-slate-500 ml-1">{{ $asset['symbol'] }}</span>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-right font-mono font-medium text-slate-900 dark:text-white">
                                ${{ number_format($asset['price'], 2) }}
                            </td>
                            <td class="px-6 py-4 text-right">
                                <span
                                    class="inline-flex items-center px-2 py-1 rounded text-xs font-bold {{ $asset['change'] >= 0 ? 'bg-success/10 text-success' : 'bg-danger/10 text-danger' }}">
                                    {{ $asset['change'] >= 0 ? '+' : '' }}{{ $asset['change'] }}%
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right text-slate-500 dark:text-slate-400 hidden sm:table-cell">
                                ${{ $asset['marketCap'] }}
                            </td>
                            <td class="px-6 py-4 text-right text-slate-500 dark:text-slate-400 hidden md:table-cell">
                                ${{ $asset['volume'] }}
                            </td>
                            <td class="px-6 py-4 text-right">
                                <a href="{{ route('trade', ['asset' => $asset['symbol']]) }}"
                                    class="bg-primary hover:bg-primary/90 text-white px-3 py-1.5 rounded-lg text-xs font-bold transition-all">
                                    Trade
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>