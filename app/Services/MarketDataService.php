<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class MarketDataService
{
    // Fallback data in case APIs fail
    protected array $fallbackData = [
        'BTC-USD' => ['price' => 65000, 'name' => 'Bitcoin'],
        'ETH-USD' => ['price' => 3500, 'name' => 'Ethereum'],
        'SOL-USD' => ['price' => 145, 'name' => 'Solana'],
        'AAPL' => ['price' => 173.50, 'name' => 'Apple Inc.'],
        'TSLA' => ['price' => 175.25, 'name' => 'Tesla Inc.'],
        'NVDA' => ['price' => 880.12, 'name' => 'NVIDIA Corp'],
        'SPY' => ['price' => 512.30, 'name' => 'SPDR S&P 500'],
    ];

    /**
     * Get quote for an asset.
     */
    public function getQuote(string $symbol): ?array
    {
        return Cache::remember("quote_{$symbol}_v2", 60, function () use ($symbol) {
            $data = $this->fetchData($symbol);

            if (!$data) {
                // Return fallback data with slight random variation to simulate live feeling
                $fallback = $this->fallbackData[$symbol] ?? ['price' => 100, 'name' => $symbol];
                $variation = (rand(-50, 50) / 10000); // +/- 0.5%
                $price = $fallback['price'] * (1 + $variation);

                return [
                    'symbol' => $symbol,
                    'name' => $fallback['name'],
                    'price' => $price,
                    'change' => $variation * 100,
                    'volume' => 1000000,
                    'marketCap' => 1000000000,
                    'is_mock' => true
                ];
            }

            return $data;
        });
    }

    /**
     * Get price only.
     */
    public function getPrice(string $symbol): ?float
    {
        $quote = $this->getQuote($symbol);
        return $quote['price'] ?? null;
    }

    /**
     * Fetch data from APIs.
     */
    protected function fetchData(string $symbol): ?array
    {
        // 1. Try CoinGecko for Crypto
        if (str_contains($symbol, '-USD')) {
            $mapping = [
                'BTC-USD' => 'bitcoin',
                'ETH-USD' => 'ethereum',
                'SOL-USD' => 'solana',
            ];

            if (isset($mapping[$symbol])) {
                try {
                    $id = $mapping[$symbol];
                    $response = Http::get("https://api.coingecko.com/api/v3/simple/price?ids={$id}&vs_currencies=usd&include_24hr_change=true");

                    if ($response->successful()) {
                        $data = $response->json()[$id] ?? null;
                        if ($data) {
                            return [
                                'symbol' => $symbol,
                                'name' => ucfirst($id),
                                'price' => $data['usd'],
                                'change' => $data['usd_24h_change'] ?? 0,
                                'volume' => 0, // Not provided by simple endpoint
                                'marketCap' => 0,
                                'is_mock' => false
                            ];
                        }
                    }
                } catch (\Exception $e) {
                    Log::error("CoinGecko API Error: {$e->getMessage()}");
                }
            }
        }

        // 2. Try Yahoo Finance (Unofficial) for everything else
        try {
            // Using query2 with User-Agent to mimic browser
            $response = Http::withHeaders([
                'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36'
            ])->get("https://query2.finance.yahoo.com/v8/finance/chart/{$symbol}?interval=1d&range=1d");

            if ($response->successful()) {
                $result = $response->json()['chart']['result'][0] ?? null;
                if ($result) {
                    $meta = $result['meta'];
                    $price = $meta['regularMarketPrice'];
                    $prevClose = $meta['chartPreviousClose'];
                    $change = $prevClose > 0 ? (($price - $prevClose) / $prevClose) * 100 : 0;

                    return [
                        'symbol' => $symbol,
                        'name' => $meta['shortName'] ?? $symbol,
                        'price' => $price,
                        'change' => $change,
                        'volume' => $result['indicators']['quote'][0]['volume'][0] ?? 0,
                        'marketCap' => 0, // Not in chart endpoint usually
                        'is_mock' => false
                    ];
                }
            }
        } catch (\Exception $e) {
            Log::error("Yahoo Finance HTTP Error: {$e->getMessage()}");
        }

        return null;
    }

    public function getQuotes(array $symbols): array
    {
        $results = [];
        foreach ($symbols as $symbol) {
            $results[] = $this->getQuote($symbol);
        }
        return array_filter($results);
    }
}
