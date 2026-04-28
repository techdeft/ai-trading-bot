<?php

use Livewire\Volt\Component;
use App\Models\Withdrawal;
use App\Models\Transaction;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;

new #[Layout('layouts.app')] class extends Component {
    use WithPagination;

    public function with()
    {
        return [
            'withdrawals' => Withdrawal::with('user')->latest()->paginate(10),
            'pendingCount' => Withdrawal::where('status', 'pending')->count(),
        ];
    }

    public function approve(Withdrawal $withdrawal)
    {
        if ($withdrawal->status !== 'pending') {
            return;
        }

        $withdrawal->update(['status' => 'approved']);

        // Log transaction
        Transaction::create([
            'user_id' => $withdrawal->user_id,
            'type' => 'withdrawal',
            'asset_symbol' => $withdrawal->token,
            'amount' => $withdrawal->amount,
            'price' => 1,
            'total' => $withdrawal->amount,
            'status' => 'completed',
        ]);

        session()->flash('message', 'Withdrawal approved successfully.');
    }

    public function reject(Withdrawal $withdrawal)
    {
        if ($withdrawal->status !== 'pending') {
            return;
        }

        $withdrawal->update(['status' => 'rejected']);

        // Refund balance to user
        $withdrawal->user->increment('balance', $withdrawal->amount);

        // Log transaction for refund
        Transaction::create([
            'user_id' => $withdrawal->user_id,
            'type' => 'withdrawal_refund',
            'asset_symbol' => $withdrawal->token,
            'amount' => $withdrawal->amount,
            'price' => 1,
            'total' => $withdrawal->amount,
            'status' => 'completed',
        ]);

        session()->flash('message', 'Withdrawal rejected and funds refunded to user.');
    }
}; ?>

<div class="p-6 sm:p-8">
    <div class="max-w-7xl mx-auto space-y-8">
        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h1 class="text-3xl font-bold text-white tracking-tight">Withdrawal Requests</h1>
                <p class="text-slate-400 mt-1">Review and manage institutional withdrawal requests.</p>
            </div>
            
            <div class="flex items-center gap-4">
                <div class="px-4 py-2 bg-surface border border-slate-700 rounded-xl flex items-center gap-3">
                    <span class="w-2 h-2 rounded-full bg-yellow-500 animate-pulse"></span>
                    <span class="text-sm font-medium text-slate-300">{{ $pendingCount }} Pending Requests</span>
                </div>
            </div>
        </div>

        <!-- Withdrawals Table -->
        <div class="bg-surface border border-slate-800 rounded-2xl overflow-hidden shadow-2xl shadow-black/50">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-background-dark/50 border-b border-slate-800">
                            <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-widest">User</th>
                            <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-widest">Amount</th>
                            <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-widest">Asset</th>
                            <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-widest">Destination Address</th>
                            <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-widest">Status</th>
                            <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-widest">Requested</th>
                            <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-widest text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-800/50">
                        @forelse ($withdrawals as $withdrawal)
                            <tr class="group hover:bg-white/[0.02] transition-colors">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-full bg-primary/10 flex items-center justify-center text-primary font-bold">
                                            {{ substr($withdrawal->user->name, 0, 1) }}
                                        </div>
                                        <div>
                                            <div class="font-semibold text-white">{{ $withdrawal->user->name }}</div>
                                            <div class="text-xs text-slate-500">{{ $withdrawal->user->email }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 font-mono font-bold text-white">
                                    {{ number_format($withdrawal->amount, 2) }}
                                </td>
                                <td class="px-6 py-4">
                                    <div class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-slate-800 border border-slate-700">
                                        <span class="text-xs font-bold text-slate-300">{{ $withdrawal->token }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-2 group/addr">
                                        <code class="text-xs text-slate-400 bg-background-dark px-2 py-1 rounded border border-slate-800 font-mono truncate max-w-[200px]" title="{{ $withdrawal->wallet_address }}">
                                            {{ $withdrawal->wallet_address }}
                                        </code>
                                        <button onclick="navigator.clipboard.writeText('{{ $withdrawal->wallet_address }}')" class="opacity-0 group-hover/addr:opacity-100 p-1 text-slate-500 hover:text-primary transition-all">
                                            <span class="material-symbols-outlined text-sm">content_copy</span>
                                        </button>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    @php
                                        $statusClass = [
                                            'approved' => 'bg-success/10 text-success border-success/20',
                                            'rejected' => 'bg-danger/10 text-danger border-danger/20',
                                            'pending' => 'bg-yellow-500/10 text-yellow-500 border-yellow-500/20',
                                        ][$withdrawal->status] ?? 'bg-slate-500/10 text-slate-500 border-slate-500/20';
                                    @endphp
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold border {{ $statusClass }}">
                                        {{ ucfirst($withdrawal->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-xs text-slate-500">
                                    {{ $withdrawal->created_at->diffForHumans() }}
                                </td>
                                <td class="px-6 py-4 text-right">
                                    @if($withdrawal->status === 'pending')
                                        <div class="flex items-center justify-end gap-2">
                                            <button 
                                                wire:click="approve({{ $withdrawal->id }})"
                                                wire:confirm="Are you sure you want to approve this withdrawal? Ensure you have manually sent the funds to the provided address."
                                                class="h-8 w-8 flex items-center justify-center rounded-lg bg-success/20 text-success hover:bg-success hover:text-white transition-all shadow-lg shadow-success/10"
                                                title="Approve Withdrawal"
                                            >
                                                <span class="material-symbols-outlined text-base">check</span>
                                            </button>
                                            <button 
                                                wire:click="reject({{ $withdrawal->id }})"
                                                wire:confirm="Reject this withdrawal request? Funds will be returned to the user's balance."
                                                class="h-8 w-8 flex items-center justify-center rounded-lg bg-danger/20 text-danger hover:bg-danger hover:text-white transition-all shadow-lg shadow-danger/10"
                                                title="Reject Withdrawal"
                                            >
                                                <span class="material-symbols-outlined text-base">close</span>
                                            </button>
                                        </div>
                                    @else
                                        <span class="text-xs text-slate-600 italic">No actions</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center gap-3">
                                        <span class="material-symbols-outlined text-4xl text-slate-700 italic">payments</span>
                                        <p class="text-slate-500">No withdrawal requests found.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($withdrawals->hasPages())
                <div class="px-6 py-4 bg-background-dark/30 border-t border-slate-800">
                    {{ $withdrawals->links() }}
                </div>
            @endif
        </div>
    </div>
</div>