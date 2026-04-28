<?php

use Livewire\Volt\Component;
use App\Models\Wallet;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;

new #[Layout('layouts.app')] class extends Component {
    use WithPagination;

    public $search = '';
    public $filterToken = '';
    public $filterNetwork = '';

    public $address;
    public $network = 'TRC20';
    public $token = 'USDT';

    public function with()
    {
        $stats = [
            'total' => Wallet::count(),
            'assigned' => Wallet::whereNotNull('user_id')->count(),
            'available' => Wallet::whereNull('user_id')->count(),
        ];

        $wallets = Wallet::with('user')
            ->when($this->search, fn($q) => $q->where('address', 'like', '%' . $this->search . '%'))
            ->when($this->filterToken, fn($q) => $q->where('token', $this->filterToken))
            ->when($this->filterNetwork, fn($q) => $q->where('network', $this->filterNetwork))
            ->latest()
            ->paginate(15);

        return [
            'wallets' => $wallets,
            'stats' => $stats,
        ];
    }

    public function save()
    {
        $validated = $this->validate([
            'address' => 'required|string|unique:wallets,address',
            'network' => 'required|string',
            'token' => 'required|string',
        ]);

        Wallet::create($validated);

        $this->reset(['address', 'network', 'token']);
        $this->dispatch('wallet-saved');
    }

    public function delete(Wallet $wallet)
    {
        if ($wallet->user_id) {
            $this->dispatch('error', message: 'Cannot delete a wallet that is currently assigned to a user.');
            return;
        }
        $wallet->delete();
    }
}; ?>

<div class="p-6 lg:p-8 space-y-8 max-w-7xl mx-auto">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div>
            <h1 class="text-3xl font-black text-slate-900 dark:text-white mb-2">Wallet Management</h1>
            <p class="text-slate-500 dark:text-slate-400 font-medium">Configure and monitor the pool of deposit addresses for users.</p>
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white dark:bg-surface p-6 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-sm relative overflow-hidden group">
            <div class="relative z-10">
                <p class="text-[10px] text-slate-400 font-black uppercase tracking-widest mb-1">Total Pool Size</p>
                <h3 class="text-3xl font-black text-slate-900 dark:text-white">{{ $stats['total'] }}</h3>
            </div>
            <span class="material-symbols-outlined absolute -right-4 -bottom-4 text-7xl text-slate-100 dark:text-slate-800/50 group-hover:scale-110 transition-transform">account_balance_wallet</span>
        </div>
        <div class="bg-white dark:bg-surface p-6 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-sm relative overflow-hidden group">
            <div class="relative z-10">
                <p class="text-[10px] text-slate-400 font-black uppercase tracking-widest mb-1">Assigned to Users</p>
                <h3 class="text-3xl font-black text-emerald-600">{{ $stats['assigned'] }}</h3>
            </div>
            <span class="material-symbols-outlined absolute -right-4 -bottom-4 text-7xl text-emerald-500/5 group-hover:scale-110 transition-transform font-light">person</span>
        </div>
        <div class="bg-white dark:bg-surface p-6 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-sm relative overflow-hidden group">
            <div class="relative z-10">
                <p class="text-[10px] text-slate-400 font-black uppercase tracking-widest mb-1">Available for New Users</p>
                <h3 class="text-3xl font-black text-amber-500">{{ $stats['available'] }}</h3>
            </div>
            <span class="material-symbols-outlined absolute -right-4 -bottom-4 text-7xl text-amber-500/5 group-hover:scale-110 transition-transform">add_circle</span>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
        <!-- Add Wallet Card -->
        <div class="bg-white dark:bg-surface p-6 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-sm space-y-6 sticky top-24">
            <h3 class="text-lg font-black text-slate-900 dark:text-white flex items-center gap-2">
                <span class="material-symbols-outlined text-primary">add_box</span>
                Add New Wallet
            </h3>
            <form wire:submit="save" class="space-y-4">
                <div>
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Wallet Address</label>
                    <input type="text" wire:model="address" placeholder="Enter Public Key / Address"
                        class="w-full bg-slate-50 dark:bg-slate-900 border-slate-200 dark:border-slate-800 rounded-xl h-12 px-4 text-sm font-bold text-slate-900 dark:text-white focus:ring-2 focus:ring-primary/50">
                    @error('address') <span class="text-rose-500 text-[10px] font-bold mt-1 block">{{ $message }}</span> @enderror
                </div>
                
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Network</label>
                        <select wire:model="network"
                            class="w-full bg-slate-50 dark:bg-slate-900 border-slate-200 dark:border-slate-800 rounded-xl h-12 px-4 text-sm font-bold text-slate-900 dark:text-white focus:ring-2 focus:ring-primary/50">
                            <option value="TRC20">TRC20</option>
                            <option value="ERC20">ERC20</option>
                            <option value="BTC">BTC</option>
                            <option value="BSC">BSC (BEP20)</option>
                        </select>
                        @error('network') <span class="text-rose-500 text-[10px] font-bold mt-1 block">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Token</label>
                        <select wire:model="token"
                            class="w-full bg-slate-50 dark:bg-slate-900 border-slate-200 dark:border-slate-800 rounded-xl h-12 px-4 text-sm font-bold text-slate-900 dark:text-white focus:ring-2 focus:ring-primary/50">
                            <option value="USDT">USDT</option>
                            <option value="BTC">BTC</option>
                            <option value="ETH">ETH</option>
                            <option value="BNB">BNB</option>
                        </select>
                        @error('token') <span class="text-rose-500 text-[10px] font-bold mt-1 block">{{ $message }}</span> @enderror
                    </div>
                </div>

                <button type="submit"
                    class="w-full bg-primary hover:bg-primary/90 text-white font-black py-3 rounded-xl shadow-lg shadow-primary/20 transition-all active:scale-[0.98]">
                    Save Wallet
                </button>
            </form>
            <x-action-message class="text-center text-emerald-600 font-bold" on="wallet-saved">
                {{ __('Wallet added to pool.') }}
            </x-action-message>
        </div>

        <!-- Wallet Table Card -->
        <div class="lg:col-span-2 bg-white dark:bg-surface rounded-3xl border border-slate-200 dark:border-slate-800 shadow-sm overflow-hidden flex flex-col min-h-[600px]">
            <!-- Filters -->
            <div class="p-6 border-b border-slate-100 dark:border-slate-800 flex flex-wrap gap-4 items-center justify-between">
                <div class="relative flex-1 min-w-[200px]">
                    <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-slate-400">search</span>
                    <input type="text" wire:model.live.debounce.300ms="search" placeholder="Search address..."
                        class="w-full bg-slate-50 dark:bg-slate-900 border-none rounded-xl h-11 pl-12 pr-4 text-sm font-medium text-slate-900 dark:text-white focus:ring-2 focus:ring-primary/50">
                </div>
                <div class="flex gap-2">
                    <select wire:model.live="filterToken" class="bg-slate-50 dark:bg-slate-900 border-none rounded-xl h-11 px-4 text-xs font-bold text-slate-500">
                        <option value="">All Tokens</option>
                        <option value="USDT">USDT</option>
                        <option value="BTC">BTC</option>
                    </select>
                    <select wire:model.live="filterNetwork" class="bg-slate-50 dark:bg-slate-900 border-none rounded-xl h-11 px-4 text-xs font-bold text-slate-500">
                        <option value="">All Networks</option>
                        <option value="TRC20">TRC20</option>
                        <option value="ERC20">ERC20</option>
                        <option value="BTC">BTC</option>
                    </select>
                </div>
            </div>

            <!-- Table -->
            <div class="flex-1 overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="text-[10px] text-slate-400 font-black uppercase tracking-[0.2em] border-b border-slate-100 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-900/50">
                            <th class="px-6 py-4">Address</th>
                            <th class="px-6 py-4">Token/Network</th>
                            <th class="px-6 py-4">Status</th>
                            <th class="px-6 py-4 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                        @forelse($wallets as $wallet)
                            <tr class="group transition-colors hover:bg-slate-50/50 dark:hover:bg-slate-900/50">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-lg bg-slate-100 dark:bg-slate-800 flex items-center justify-center text-slate-400 group-hover:text-primary transition-colors">
                                            <span class="material-symbols-outlined text-lg">content_copy</span>
                                        </div>
                                        <span class="text-xs font-mono font-bold text-slate-600 dark:text-slate-400 cursor-pointer hover:text-primary break-all max-w-[150px] lg:max-w-none" title="Click to copy">
                                            {{ $wallet->address }}
                                        </span>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex flex-col">
                                        <span class="text-sm font-black text-slate-900 dark:text-white">{{ $wallet->token }}</span>
                                        <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">{{ $wallet->network }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    @if($wallet->user)
                                        <div class="flex items-center gap-2">
                                            <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                                            <div class="flex flex-col">
                                                <span class="text-xs font-bold text-slate-900 dark:text-white">{{ $wallet->user->name }}</span>
                                                <span class="text-[10px] text-slate-400 uppercase font-bold">Assigned</span>
                                            </div>
                                        </div>
                                    @else
                                        <div class="flex items-center gap-2">
                                            <span class="w-2 h-2 rounded-full bg-amber-500"></span>
                                            <span class="text-[10px] font-bold text-amber-500 uppercase tracking-wider">Available Pool</span>
                                        </div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <button wire:click="delete({{ $wallet->id }})" 
                                        wire:confirm="Are you sure you want to remove this wallet address from the pool?"
                                        class="p-2 rounded-lg text-rose-500 hover:bg-rose-500/10 transition-colors opacity-0 group-hover:opacity-100">
                                        <span class="material-symbols-outlined">delete</span>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="py-24 text-center">
                                    <div class="flex flex-col items-center gap-3">
                                        <span class="material-symbols-outlined text-5xl text-slate-200 dark:text-slate-800">block</span>
                                        <p class="text-slate-400 text-sm font-medium">No wallet addresses found in the pool.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="p-6 border-t border-slate-100 dark:border-slate-800">
                {{ $wallets->links() }}
            </div>
        </div>
    </div>
</div>