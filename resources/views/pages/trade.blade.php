<?php

use Livewire\Volt\Component;
use Livewire\Attributes\Layout;
use App\Services\MarketDataService;
use App\Services\PortfolioService;
use Illuminate\Support\Facades\Auth;

new #[Layout('layouts.app')] class extends Component {
    public string $asset = 'BTC-USD'; // Default to Yahoo Finance symbol
    public string $displayAsset = 'BTC';
    public string $type = 'buy'; // buy, sell
    public string $orderType = 'market'; // market, limit
    public ?float $price = null;
    public float $amount = 0;
    public ?float $change = 0;

    public function mount(MarketDataService $marketData, $asset = 'BTC')
    {
        $this->displayAsset = strtoupper($asset);
        // Map common crypto symbols to Yahoo Finance tickers
        $tickerMap = [
            'BTC' => 'BTC-USD',
            'ETH' => 'ETH-USD',
            'SOL' => 'SOL-USD',
            'TSLA' => 'TSLA',
            'AAPL' => 'AAPL',
            'NVDA' => 'NVDA',
            'SPY' => 'SPY',
        ];

        $this->asset = $tickerMap[$this->displayAsset] ?? $this->displayAsset;

        try {
            $quote = $marketData->getQuote($this->asset);
            $this->price = $quote['price'] ?? 0;
            $this->change = $quote['change'] ?? 0;
        } catch (\Exception $e) {
            $this->price = 0; // Fallback or handle error
        }
    }

    public function submitOrder(PortfolioService $portfolioService)
    {
        $this->validate([
            'amount' => 'required|numeric|gt:0',
        ]);

        if (!$this->price || $this->price <= 0) {
            session()->flash('error', 'Invalid price data. Cannot execute trade.');
            return;
        }

        try {
            $portfolioService->executeTrade(
                Auth::user(),
                $this->asset,
                $this->type,
                $this->amount,
                $this->price
            );

            session()->flash('success', "Successfully {$this->type}ing {$this->amount} {$this->displayAsset}");
            $this->amount = 0;

            // Refresh sensitive data if needed, or redirect
        } catch (\Exception $e) {
            session()->flash('error', $e->getMessage());
        }
    }

    public function with(): array
    {
        return [
            'total' => $this->amount * ($this->price ?? 0),
        ];
    }
}; ?>

<div class="p-6 w-full mx-auto grid grid-cols-1 lg:grid-cols-3 gap-6 h-auto lg:h-[600px] overflow-y-scroll">
    @if (session()->has('success'))
        <div class="lg:col-span-3 p-4 mb-4 text-sm text-green-800 rounded-lg bg-green-50 dark:bg-gray-800 dark:text-green-400"
            role="alert">
            <span class="font-medium">Success!</span> {{ session('success') }}
        </div>
    @endif
    @if (session()->has('error'))
        <div class="lg:col-span-3 p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50 dark:bg-gray-800 dark:text-red-400"
            role="alert">
            <span class="font-medium">Error!</span> {{ session('error') }}
        </div>
    @endif

    <!-- Chart Area -->
    <div class="lg:col-span-2 flex flex-col gap-6">
        <!-- Asset Header -->
        <div
            class="bg-white dark:bg-surface rounded-xl border border-slate-200 dark:border-slate-700 p-6 flex justify-between items-center">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-full bg-orange-500/10 flex items-center justify-center text-orange-500">
                    <span class="material-symbols-outlined text-2xl">currency_bitcoin</span>
                </div>
                <div>
                    <h1 class="text-2xl font-bold text-slate-900 dark:text-white">{{ $displayAsset }}/USD</h1>
                    <p class="text-slate-500 dark:text-slate-400">Bitcoin</p>
                </div>
            </div>
            <div class="text-right">
                <h2 class="text-2xl font-mono font-bold text-slate-900 dark:text-white">${{ number_format($price, 2) }}
                </h2>
                <span
                    class="inline-flex items-center gap-1 text-sm font-bold {{ $change >= 0 ? 'text-success' : 'text-danger' }}">
                    <span
                        class="material-symbols-outlined text-base">{{ $change >= 0 ? 'trending_up' : 'trending_down' }}</span>
                    {{ $change >= 0 ? '+' : '' }}{{ number_format($change, 2) }}%
                </span>
            </div>
        </div>

        <!-- Chart Placeholder -->
        <!-- TradingView Widget BEGIN -->
        <div class="flex-1 bg-white dark:bg-surface rounded-xl border border-slate-200 dark:border-slate-700 overflow-hidden relative z-0"
            wire:ignore>
            <div id="tradingview_chart" class="w-full h-full"></div>
            <script type="text/javascript" src="https://s3.tradingview.com/tv.js"></script>
            <script type="text/javascript">
                new TradingView.widget({
                    "autosize": true,
                    "symbol": "{{ $asset == 'BTC-USD' ? 'COINBASE:BTCUSD' : ($asset == 'ETH-USD' ? 'COINBASE:ETHUSD' : ($asset == 'SOL-USD' ? 'COINBASE:SOLUSD' : 'NASDAQ:' . $asset)) }}",
                    "interval": "D",
                    "timezone": "Etc/UTC",
                    "theme": "dark",
                    "style": "1",
                    "locale": "en",
                    "enable_publishing": false,
                    "allow_symbol_change": true,
                    "container_id": "tradingview_chart"
                });
            </script>
        </div>
        <!-- TradingView Widget END -->
    </div>

    <!-- Order Form & Order Book -->
    <div class="flex flex-col gap-6">
        <!-- Order Form -->
        <div class="bg-white dark:bg-surface rounded-xl border border-slate-200 dark:border-slate-700 p-6">
            <div class="flex mb-6 bg-slate-100 dark:bg-background-dark rounded-lg p-1">
                <button wire:click="$set('type', 'buy')"
                    class="flex-1 py-2 text-sm font-bold rounded-md transition-all {{ $type === 'buy' ? 'bg-success text-white shadow-lg' : 'text-slate-500 dark:text-slate-400 hover:text-slate-700 dark:hover:text-slate-200' }}">Buy</button>
                <button wire:click="$set('type', 'sell')"
                    class="flex-1 py-2 text-sm font-bold rounded-md transition-all {{ $type === 'sell' ? 'bg-danger text-white shadow-lg' : 'text-slate-500 dark:text-slate-400 hover:text-slate-700 dark:hover:text-slate-200' }}">Sell</button>
            </div>

            <form wire:submit="submitOrder" class="space-y-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase mb-1">Order
                        Type</label>
                    <select wire:model.live="orderType"
                        class="w-full bg-slate-50 dark:bg-background-dark border border-slate-200 dark:border-slate-700 rounded-lg px-3 py-2 text-sm focus:ring-primary focus:border-primary">
                        <option value="market">Market Order</option>
                        <option value="limit">Limit Order</option>
                    </select>
                </div>

                @if($orderType === 'limit')
                    <div>
                        <label class="block text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase mb-1">Price
                            (USD)</label>
                        <input wire:model="price" type="number" step="0.01"
                            class="w-full bg-slate-50 dark:bg-background-dark border border-slate-200 dark:border-slate-700 rounded-lg px-3 py-2 text-right font-mono text-sm focus:ring-primary focus:border-primary">
                    </div>
                @endif

                <div>
                    <label class="block text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase mb-1">Amount
                        ({{ $asset }})</label>
                    <div class="relative">
                        <input wire:model.live="amount" type="number" step="0.000001" placeholder="0.00"
                            class="w-full bg-slate-50 dark:bg-background-dark border border-slate-200 dark:border-slate-700 rounded-lg pl-3 pr-12 py-2 text-right font-mono text-sm focus:ring-primary focus:border-primary">
                        <span
                            class="absolute right-3 top-2.5 text-xs font-bold text-slate-500">{{ $displayAsset }}</span>
                    </div>
                </div>

                <div class="pt-4 border-t border-slate-200 dark:border-slate-700 flex justify-between items-center">
                    <span class="text-sm font-medium text-slate-500">Total Value</span>
                    <span
                        class="text-lg font-bold text-slate-900 dark:text-white">${{ number_format($total, 2) }}</span>
                </div>

                <button type="submit"
                    class="w-full py-3 rounded-lg font-bold text-white transition-all transform active:scale-95 shadow-lg {{ $type === 'buy' ? 'bg-success hover:bg-success/90 shadow-success/20' : 'bg-danger hover:bg-danger/90 shadow-danger/20' }}">
                    {{ $type === 'buy' ? 'Buy ' . $displayAsset : 'Sell ' . $displayAsset }}
                </button>
            </form>
        </div>

        <!-- Order Book Simulation -->
        <div
            class="flex-1 bg-white dark:bg-surface rounded-xl border border-slate-200 dark:border-slate-700 overflow-hidden flex flex-col">
            <div class="p-4 border-b border-slate-200 dark:border-slate-700">
                <h3 class="font-bold text-sm">Order Book</h3>
            </div>
            <div class="flex-1 overflow-y-auto font-mono text-xs">
                <table class="w-full text-right">
                    <thead class="text-slate-500 dark:text-slate-400 bg-slate-50 dark:bg-background-dark/50">
                        <tr>
                            <th class="px-4 py-2">Price</th>
                            <th class="px-4 py-2">Amount</th>
                            <th class="px-4 py-2">Total</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                        <!-- Sells -->
                        @for ($i = 0; $i < 5; $i++)
                            <tr class="text-danger hover:bg-danger/5">
                                <td class="px-4 py-1.5">{{ number_format($price + (10 * ($i + 1)), 2) }}</td>
                                <td class="px-4 py-1.5">{{ number_format(rand(10, 500) / 1000, 4) }}</td>
                                <td class="px-4 py-1.5 text-slate-500 dark:text-slate-400">
                                    {{ number_format(($price + (10 * ($i + 1))) * (rand(10, 500) / 1000), 2) }}
                                </td>
                            </tr>
                        @endfor

                        <!-- Spread -->
                        <tr class="bg-slate-50 dark:bg-background-dark">
                            <td colspan="3"
                                class="px-4 py-2 text-center text-slate-500 font-bold border-y border-slate-200 dark:border-slate-700">
                                {{ number_format($price, 2) }} <span class="text-xs font-normal opacity-75">Last
                                    Price</span>
                            </td>
                        </tr>

                        <!-- Buys -->
                        @for ($i = 0; $i < 5; $i++)
                            <tr class="text-success hover:bg-success/5">
                                <td class="px-4 py-1.5">{{ number_format($price - (10 * ($i + 1)), 2) }}</td>
                                <td class="px-4 py-1.5">{{ number_format(rand(10, 500) / 1000, 4) }}</td>
                                <td class="px-4 py-1.5 text-slate-500 dark:text-slate-400">
                                    {{ number_format(($price - (10 * ($i + 1))) * (rand(10, 500) / 1000), 2) }}
                                </td>
                            </tr>
                        @endfor
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>