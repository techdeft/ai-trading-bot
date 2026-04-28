<?php

use Livewire\Volt\Component;
use App\Models\Deposit;
use App\Models\Transaction;
use App\Models\User;
use Livewire\Attributes\Layout;
use Livewire\WithPagination;

new #[Layout('layouts.app')] class extends Component {
    use WithPagination;

    public function with()
    {
        return [
            'deposits' => Deposit::with(['user', 'wallet'])->latest()->paginate(10),
            'pendingCount' => Deposit::whereIn('status', ['pending', 'submitted'])->count(),
        ];
    }

    public function approve(Deposit $deposit)
    {
        if ($deposit->status !== 'pending' && $deposit->status !== 'submitted') {
            return;
        }

        $deposit->update([
            'status' => 'approved',
        ]);

        $deposit->user->increment('balance', $deposit->amount);

        // Log transaction
        Transaction::create([
            'user_id' => $deposit->user_id,
            'type' => 'deposit',
            'asset_symbol' => 'USDT', // Assuming USDT for now, could be improved to use wallet token
            'amount' => $deposit->amount,
            'price' => 1,
            'total' => $deposit->amount,
            'status' => 'completed',
        ]);

        session()->flash('message', 'Deposit approved and balance credited.');
    }

    public function reject(Deposit $deposit)
    {
        if ($deposit->status !== 'pending' && $deposit->status !== 'submitted') {
            return;
        }

        $deposit->update([
            'status' => 'rejected',
        ]);

        session()->flash('message', 'Deposit rejected.');
    }
}; ?>

<div class="p-6 sm:p-8">
    <div class="max-w-7xl mx-auto space-y-8">
        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h1 class="text-3xl font-bold text-white tracking-tight">Deposit Requests</h1>
                <p class="text-slate-400 mt-1">Monitor and verify institutional funding requests.</p>
            </div>
            
            <div class="flex items-center gap-4">
                <div class="px-4 py-2 bg-surface border border-slate-700 rounded-xl flex items-center gap-3">
                    <span class="w-2 h-2 rounded-full bg-primary animate-pulse"></span>
                    <span class="text-sm font-medium text-slate-300">{{ $pendingCount }} Action Required</span>
                </div>
            </div>
        </div>

        <!-- Deposits Table -->
        <div class="bg-surface border border-slate-800 rounded-2xl overflow-hidden shadow-2xl shadow-black/50">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-background-dark/50 border-b border-slate-800">
                            <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-widest">User</th>
                            <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-widest">Amount</th>
                            <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-widest">Network</th>
                            <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-widest">Transaction Hash</th>
                            <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-widest">Status</th>
                            <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-widest">Date</th>
                            <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-widest text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-800/50">
                        @forelse($deposits as $deposit)
                            <tr class="group hover:bg-white/[0.02] transition-colors">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-full bg-success/10 flex items-center justify-center text-success font-bold">
                                            {{ substr($deposit->user->name, 0, 1) }}
                                        </div>
                                        <div>
                                            <div class="font-semibold text-white">{{ $deposit->user->name }}</div>
                                            <div class="text-xs text-slate-500">{{ $deposit->user->email }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-lg font-mono font-bold text-success">
                                        +${{ number_format($deposit->amount, 2) }}
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex flex-col">
                                        <span class="text-sm font-bold text-slate-300">{{ $deposit->wallet->network ?? 'N/A' }}</span>
                                        <span class="text-[10px] text-slate-500 uppercase tracking-tighter">{{ $deposit->wallet->token ?? 'N/A' }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-2 group/hash">
                                        <code class="text-xs text-slate-400 bg-background-dark px-2 py-1 rounded border border-slate-800 font-mono" title="{{ $deposit->transaction_hash }}">
                                            @if($deposit->transaction_hash)
                                                {{ substr($deposit->transaction_hash, 0, 8) }}...{{ substr($deposit->transaction_hash, -8) }}
                                            @else
                                                <span class="text-slate-600 italic">No Hash</span>
                                            @endif
                                        </code>
                                        @if($deposit->transaction_hash)
                                            <button onclick="navigator.clipboard.writeText('{{ $deposit->transaction_hash }}')" class="opacity-0 group-hover/hash:opacity-100 p-1 text-slate-500 hover:text-primary transition-all">
                                                <span class="material-symbols-outlined text-sm">content_copy</span>
                                            </button>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    @php
                                        $statusClass = [
                                            'approved' => 'bg-success/10 text-success border-success/20',
                                            'rejected' => 'bg-danger/10 text-danger border-danger/20',
                                            'submitted' => 'bg-primary/10 text-primary border-primary/20',
                                            'pending' => 'bg-yellow-500/10 text-yellow-500 border-yellow-500/20',
                                        ][$deposit->status] ?? 'bg-slate-500/10 text-slate-500 border-slate-500/20';
                                    @endphp
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold border {{ $statusClass }}">
                                        {{ ucfirst($deposit->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-xs text-slate-500">
                                    {{ $deposit->created_at->format('M d, Y') }}
                                    <div class="text-[10px]">{{ $deposit->created_at->format('H:i') }}</div>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    @if($deposit->status === 'pending' || $deposit->status === 'submitted')
                                        <div class="flex items-center justify-end gap-2 text-sm font-medium">
                                            <button 
                                                wire:click="approve({{ $deposit->id }})"
                                                wire:confirm="Approve this deposit request and credit the user's balance?"
                                                class="px-3 py-1.5 rounded-lg bg-success text-white hover:bg-success/90 transition-all shadow-lg shadow-success/10 flex items-center gap-1"
                                            >
                                                <span class="material-symbols-outlined text-sm">check</span>
                                                Approve
                                            </button>
                                            <button 
                                                wire:click="reject({{ $deposit->id }})"
                                                wire:confirm="Reject this deposit request?"
                                                class="px-3 py-1.5 rounded-lg bg-danger/20 text-danger hover:bg-danger hover:text-white transition-all border border-danger/20 flex items-center gap-1"
                                            >
                                                <span class="material-symbols-outlined text-sm">close</span>
                                                Reject
                                            </button>
                                        </div>
                                    @else
                                        <div class="flex items-center justify-end gap-1.5 text-slate-600">
                                            <span class="material-symbols-outlined text-sm">lock</span>
                                            <span class="text-xs italic">Locked</span>
                                        </div>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-12 text-center text-slate-500">
                                    <div class="flex flex-col items-center gap-3 italic">
                                        <span class="material-symbols-outlined text-4xl opacity-20">account_balance</span>
                                        <p>No deposit requests available.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($deposits->hasPages())
                <div class="px-6 py-4 bg-background-dark/30 border-t border-slate-800 text-white">
                    {{ $deposits->links() }}
                </div>
            @endif
        </div>
    </div>
</div>