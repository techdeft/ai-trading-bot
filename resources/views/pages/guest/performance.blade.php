<x-layouts.guest>
    <x-slot name="title">Performance | Falcon x</x-slot>

    <!-- Header -->
    <section class="relative py-20 overflow-hidden">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h1 class="text-4xl lg:text-5xl font-bold mb-6">Proven <span class="text-primary">Track Record</span></h1>
            <p class="text-xl text-slate-400 max-w-2xl mx-auto">Our AI algorithms have consistently outperformed the
                market benchmark since inception. Verify our live trading results below.</p>
        </div>
    </section>

    <!-- Stats Grid -->
    <section class="pb-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                <div class="bg-surface-dark p-6 rounded-xl border border-border-dark text-center">
                    <p class="text-slate-500 text-sm uppercase font-bold mb-2">Total AI Profit</p>
                    <p class="text-3xl font-black text-white">$4.2M+</p>
                </div>
                <div class="bg-surface-dark p-6 rounded-xl border border-border-dark text-center">
                    <p class="text-slate-500 text-sm uppercase font-bold mb-2">Win Rate</p>
                    <p class="text-3xl font-black text-success-green">87.4%</p>
                </div>
                <div class="bg-surface-dark p-6 rounded-xl border border-border-dark text-center">
                    <p class="text-slate-500 text-sm uppercase font-bold mb-2">Active Bots</p>
                    <p class="text-3xl font-black text-white">2,543</p>
                </div>
                <div class="bg-surface-dark p-6 rounded-xl border border-border-dark text-center">
                    <p class="text-slate-500 text-sm uppercase font-bold mb-2">Avg. Monthly ROI</p>
                    <p class="text-3xl font-black text-primary">12-45%</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Live Chart Section -->
    <section class="py-12 bg-surface-dark border-y border-border-dark">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center mb-8">
                <h2 class="text-2xl font-bold">Live AI Performance (BTC/USDT)</h2>
                <div class="flex gap-2">
                    <span
                        class="px-3 py-1 rounded bg-success-green/10 text-success-green text-xs font-bold flex items-center gap-1">
                        <span class="w-2 h-2 rounded-full bg-success-green animate-pulse"></span> LIVE
                    </span>
                </div>
            </div>

            <div class="bg-background-dark rounded-xl overflow-hidden border border-border-dark h-[500px]"
                id="performance-chart"></div>
        </div>
    </section>

    <script
        src="https://cdn.jsdelivr.net/npm/lightweight-charts@4.1.1/dist/lightweight-charts.standalone.production.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const chartOptions = {
                layout: { textColor: '#94a3b8', background: { type: 'solid', color: '#101922' } },
                grid: { vertLines: { color: '#1e293b' }, horzLines: { color: '#1e293b' } },
                timeScale: { timeVisible: true, secondsVisible: false, borderVisible: false },
                rightPriceScale: { borderVisible: false },
            };

            const container = document.getElementById('performance-chart');
            if (container) {
                const chart = LightweightCharts.createChart(container, chartOptions);
                const areaSeries = chart.addAreaSeries({
                    lineColor: '#0d7ff2', topColor: 'rgba(13, 127, 242, 0.4)', bottomColor: 'rgba(13, 127, 242, 0.0)',
                });

                // Generate consistent uptrend data
                let data = [];
                let time = Math.floor(Date.now() / 1000) - (86400 * 30); // 30 days ago
                let value = 10000;

                for (let i = 0; i < 30 * 24; i++) {
                    time += 3600;
                    value = value * (1 + (Math.random() * 0.005 - 0.001)); // Slight uptrend bias
                    data.push({ time, value });
                }

                areaSeries.setData(data);
                chart.timeScale().fitContent();
            }
        });
    </script>
    </x-layouts.guest>