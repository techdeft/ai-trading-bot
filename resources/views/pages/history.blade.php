<?php

use Livewire\Volt\Component;
use Livewire\Attributes\Layout;
use Livewire\WithPagination;
use App\Models\Transaction;
use App\Models\BotTradeLog;
use Illuminate\Support\Facades\Auth;

new #[Layout('layouts.app')] class extends Component {
    use WithPagination;

    public function with(): array
    {
        return [
            'botLogs' => BotTradeLog::whereHas('userBot', function ($query) {
                $query->where('user_id', Auth::id());
            })
                ->with(['userBot.botPlan'])
                ->latest()
                ->paginate(15),
        ];
    }
}; ?>

<div class="p-6 w-full mx-auto space-y-6">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 dark:text-white">Bot Activity Log</h1>
            <p class="text-slate-500 dark:text-slate-400">Real-time performance feed from your active AI trading bots.
            </p>
        </div>
    </div>

    <div class="bg-white dark:bg-surface rounded-xl border border-slate-200 dark:border-slate-700 overflow-hidden shadow-sm animate-fade-in-up">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-slate-50 dark:bg-background-dark/50 border-b border-slate-200 dark:border-slate-700">
                    <tr>
                        <th
                            class="px-6 py-4 text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">
                            Date</th>
                        <th
                            class="px-6 py-4 text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">
                            Bot Plan</th>
                        <th
                            class="px-6 py-4 text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider text-right">
                            ROI</th>
                        <th
                            class="px-6 py-4 text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider text-right">
                            Profit</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 dark:divide-slate-700 text-sm">
                    @forelse($botLogs as $log)
                        <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors">
                            <td class="px-6 py-4 text-slate-500 dark:text-slate-400 whitespace-nowrap">
                                {{ $log->created_at->format('M d, Y H:i:s') }}
                            </td>
                            <td class="px-6 py-4 font-medium text-slate-900 dark:text-white">
                                {{ $log->userBot->botPlan->name }} <span
                                    class="text-xs text-slate-500 ml-1">#{{ $log->userBot->id }}</span>
                            </td>
                            <td class="px-6 py-4 text-right font-mono text-success">
                                +{{ number_format($log->roi_percentage, 2) }}%
                            </td>
                            <td class="px-6 py-4 text-right font-bold font-mono text-success">
                                +${{ number_format($log->profit, 4) }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-12 text-center text-slate-500 dark:text-slate-400">
                                <div class="flex flex-col items-center justify-center">
                                    <div class="w-16 h-16 bg-slate-100 dark:bg-slate-800 rounded-full flex items-center justify-center mb-4">
                                        <span class="material-symbols-outlined text-3xl text-slate-400">smart_toy</span>
                                    </div>
                                    <h3 class="text-lg font-medium text-slate-900 dark:text-white mb-1">No activity yet</h3>
                                    <p class="text-sm text-slate-500 dark:text-slate-400 max-w-sm text-center">
                                        Once your bots start trading, their performance logs will appear here automatically.
                                    </p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 border-t border-slate-200 dark:border-slate-700">
            {{ $botLogs->links() }}
        </div>
    </div>
</div>