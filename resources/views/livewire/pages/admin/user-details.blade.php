<div class="p-6 lg:p-8 space-y-8 max-w-7xl mx-auto">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div class="flex items-center gap-6">
            <div
                class="w-20 h-20 rounded-2xl bg-gradient-to-br from-primary to-indigo-600 flex items-center justify-center text-3xl font-black text-white shadow-xl shadow-primary/20">
                {{ $user->initials() }}
            </div>
            <div>
                <h1 class="text-3xl font-black text-slate-900 dark:text-white mb-1">{{ $user->name }}</h1>
                <div class="flex flex-wrap items-center gap-3">
                    <span class="text-slate-500 dark:text-slate-400 font-medium flex items-center gap-1.5">
                        <span class="material-symbols-outlined text-lg">mail</span>
                        {{ $user->email }}
                    </span>
                    <span class="w-1 h-1 rounded-full bg-slate-300 dark:bg-slate-700"></span>
                    <span class="text-slate-500 dark:text-slate-400 font-medium flex items-center gap-1.5">
                        <span class="material-symbols-outlined text-lg">calendar_today</span>
                        Joined {{ $user->created_at->format('M d, Y') }}
                    </span>
                </div>
            </div>
        </div>

        <div class="flex flex-wrap items-center gap-3">
            <button wire:click="toggleAdmin"
                class="px-4 py-2.5 rounded-xl text-sm font-bold transition-all border {{ $user->is_admin ? 'border-amber-500/20 bg-amber-500/10 text-amber-600' : 'border-slate-200 dark:border-slate-800 text-slate-600 hover:bg-slate-50' }}">
                {{ $user->is_admin ? 'Revoke Admin' : 'Make Admin' }}
            </button>
            <div class="h-8 w-px bg-slate-200 dark:bg-slate-800 hidden md:block"></div>
            <a href="{{ route('admin.users') }}"
                class="px-4 py-2.5 rounded-xl text-sm font-bold border border-slate-200 dark:border-slate-800 text-slate-600 hover:bg-slate-50 transition-all flex items-center gap-2">
                <span class="material-symbols-outlined text-lg">arrow_back</span>
                Back to List
            </a>
        </div>
    </div>

    <!-- Quick Stats & Balance Action -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Main Balance Card -->
        <div class="lg:col-span-2 grid grid-cols-1 md:grid-cols-2 gap-6">
            <div
                class="bg-slate-900 dark:bg-surface rounded-3xl p-8 text-white relative overflow-hidden shadow-2xl shadow-slate-900/10 border border-slate-800">
                <div class="relative z-10">
                    <p class="text-slate-400 text-sm font-bold uppercase tracking-wider mb-2">Total Combined Balance</p>
                    <h2 class="text-4xl font-black mb-6">${{ number_format($user->balance, 2) }}</h2>
                    <div class="flex items-center gap-4">
                        <div class="bg-white/10 px-4 py-2 rounded-xl border border-white/5">
                            <p class="text-[10px] text-slate-400 uppercase font-black tracking-widest mb-0.5">Cash</p>
                            <p class="text-lg font-bold">${{ number_format($user->balance, 2) }}</p>
                        </div>
                        <div class="bg-white/10 px-4 py-2 rounded-xl border border-white/5">
                            <p class="text-[10px] text-slate-400 uppercase font-black tracking-widest mb-0.5">Wallets</p>
                            <p class="text-lg font-bold">{{ $user->wallets->count() }} Addresses</p>
                        </div>
                    </div>
                </div>
                <div class="absolute -right-16 -bottom-16 w-64 h-64 bg-primary/20 rounded-full blur-3xl text-primary"></div>
            </div>

            <div class="bg-white dark:bg-surface rounded-3xl p-8 border border-slate-200 dark:border-slate-800 shadow-sm">
                <h3 class="text-slate-900 dark:text-white font-black text-lg mb-6 flex items-center gap-2">
                    <span class="material-symbols-outlined text-primary">verified_user</span>
                    Security & KYC
                </h3>
                <div class="space-y-6">
                    <div class="flex items-center justify-between">
                        <span class="text-slate-500 font-medium">KYC Status</span>
                        <span
                            class="px-3 py-1 rounded-full text-xs font-black uppercase tracking-widest
                            {{ $user->kyc_status === 'approved' ? 'bg-emerald-500/10 text-emerald-600' : ($user->kyc_status === 'pending' ? 'bg-amber-500/10 text-amber-600' : 'bg-rose-500/10 text-rose-600') }}">
                            {{ ucfirst($user->kyc_status ?? 'Unverified') }}
                        </span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-slate-500 font-medium">Two-Factor Auth</span>
                        <span
                            class="px-3 py-1 rounded-full text-xs font-black uppercase tracking-widest {{ $user->hasEnabledTwoFactorAuthentication() ? 'bg-emerald-500/10 text-emerald-600' : 'bg-slate-100 text-slate-500' }}">
                            {{ $user->hasEnabledTwoFactorAuthentication() ? 'Enabled' : 'Disabled' }}
                        </span>
                    </div>
                    <div class="pt-4 flex gap-2">
                        <button wire:click="updateKycStatus('approved')"
                            class="flex-1 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold py-2 rounded-lg transition-colors">Approve
                            KYC</button>
                        <button wire:click="updateKycStatus('rejected')"
                            class="flex-1 bg-rose-600 hover:bg-rose-700 text-white text-xs font-bold py-2 rounded-lg transition-colors">Reject</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Balance Adjustment Panel -->
        <div class="bg-white dark:bg-surface rounded-3xl p-8 border border-slate-200 dark:border-slate-800 shadow-sm">
            <h3 class="text-slate-900 dark:text-white font-black text-lg mb-6">Manage Balance</h3>
            <form wire:submit="adjustBalance" class="space-y-4">
                <div class="flex bg-slate-100 dark:bg-slate-900 p-1 rounded-xl">
                    <button type="button" wire:click="$set('type', 'credit')"
                        class="flex-1 py-2 text-xs font-black rounded-lg transition-all {{ $type === 'credit' ? 'bg-emerald-600 text-white shadow-lg' : 'text-slate-500' }}">CREDIT</button>
                    <button type="button" wire:click="$set('type', 'debit')"
                        class="flex-1 py-2 text-xs font-black rounded-lg transition-all {{ $type === 'debit' ? 'bg-rose-600 text-white shadow-lg' : 'text-slate-500' }}">DEBIT</button>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Amount (USD)</label>
                    <div class="relative">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 font-bold">$</span>
                        <input type="number" step="0.01" wire:model="amount"
                            class="w-full bg-slate-100 dark:bg-slate-900 border-none rounded-xl h-12 pl-8 pr-4 text-slate-900 dark:text-white font-bold focus:ring-2 focus:ring-primary/50">
                    </div>
                    @error('amount') <span class="text-rose-500 text-[10px] font-bold mt-1 block">{{ $message }}</span> @enderror
                </div>
                <button type="submit"
                    class="w-full bg-primary hover:bg-primary/90 text-white font-black py-3 rounded-xl shadow-lg shadow-primary/20 transition-all active:scale-[0.98]">
                    Confirm Adjustment
                </button>
            </form>
            <x-action-message class="mt-4 text-center text-emerald-600 font-bold" on="balance-updated">
                {{ __('Balance adjusted successfully.') }}
            </x-action-message>
        </div>
    </div>

    <!-- Details Tab Content -->
    <div class="space-y-6">
        <!-- Tab Navigation -->
        <div class="flex items-center gap-1 bg-slate-100 dark:bg-slate-900 p-1.5 rounded-2xl w-fit">
            <button wire:click="setTab('overview')"
                class="px-6 py-2.5 rounded-xl text-sm font-black transition-all {{ $activeTab === 'overview' ? 'bg-white dark:bg-surface text-primary shadow-sm' : 'text-slate-500 hover:text-slate-700 dark:hover:text-slate-300' }}">
                Overview
            </button>
            <button wire:click="setTab('wallets')"
                class="px-6 py-2.5 rounded-xl text-sm font-black transition-all {{ $activeTab === 'wallets' ? 'bg-white dark:bg-surface text-primary shadow-sm' : 'text-slate-500 hover:text-slate-700 dark:hover:text-slate-300' }}">
                Wallets
            </button>
            <button wire:click="setTab('bots')"
                class="px-6 py-2.5 rounded-xl text-sm font-black transition-all {{ $activeTab === 'bots' ? 'bg-white dark:bg-surface text-primary shadow-sm' : 'text-slate-500 hover:text-slate-700 dark:hover:text-slate-300' }}">
                Activated Bots
            </button>
            <button wire:click="setTab('kyc')"
                class="px-6 py-2.5 rounded-xl text-sm font-black transition-all {{ $activeTab === 'kyc' ? 'bg-white dark:bg-surface text-primary shadow-sm' : 'text-slate-500 hover:text-slate-700 dark:hover:text-slate-300' }}">
                KYC Docs
            </button>
        </div>

        <!-- Tab Panes -->
        <div class="bg-white dark:bg-surface rounded-3xl p-8 border border-slate-200 dark:border-slate-800 min-h-[400px]">
            @if($activeTab === 'overview')
                <!-- Transaction History -->
                <div class="space-y-6">
                    <h3 class="text-xl font-black text-slate-900 dark:text-white">Recent Financial Activity</h3>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left">
                            <thead>
                                <tr class="text-[10px] text-slate-400 font-black uppercase tracking-[0.2em] border-b border-slate-100 dark:border-slate-800">
                                    <th class="pb-4 pr-4">Type</th>
                                    <th class="pb-4 pr-4">Asset</th>
                                    <th class="pb-4 pr-4">Amount</th>
                                    <th class="pb-4 pr-4 text-right">Date</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                                @forelse($recentTransactions as $tx)
                                    <tr class="group hover:bg-slate-50/50 dark:hover:bg-slate-900/50 transition-colors">
                                        <td class="py-4 pr-4">
                                            <div class="flex items-center gap-3">
                                                <div class="w-8 h-8 rounded-lg flex items-center justify-center
                                                    {{ str_contains($tx->type, 'credit') || str_contains($tx->type, 'deposit') ? 'bg-emerald-100 text-emerald-600' : 'bg-rose-100 text-rose-600' }}">
                                                    <span class="material-symbols-outlined text-lg">
                                                        {{ str_contains($tx->type, 'credit') || str_contains($tx->type, 'deposit') ? 'add_circle' : 'do_not_disturb_on' }}
                                                    </span>
                                                </div>
                                                <span class="text-sm font-bold text-slate-900 dark:text-white uppercase">{{ str_replace('_', ' ', $tx->type) }}</span>
                                            </div>
                                        </td>
                                        <td class="py-4 pr-4 text-sm font-medium text-slate-500">{{ $tx->asset_symbol }}</td>
                                        <td class="py-4 pr-4 text-sm font-black text-slate-900 dark:text-white">${{ number_format($tx->amount, 2) }}</td>
                                        <td class="py-4 pr-4 text-right text-xs text-slate-400">{{ $tx->created_at->format('M d, H:i') }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="py-12 text-center text-slate-400 text-sm italic">No recent financial logs found for this user.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            @elseif($activeTab === 'wallets')
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @forelse($user->wallets as $wallet)
                        <div class="bg-slate-50 dark:bg-slate-900 p-6 rounded-2xl border border-slate-100 dark:border-slate-800">
                            <div class="flex items-center justify-between mb-4">
                                <span class="bg-indigo-600 text-white text-[10px] font-black px-2 py-1 rounded shadow-lg shadow-indigo-600/20">{{ $wallet->network }}</span>
                                <span class="text-slate-400 text-xs font-black">{{ $wallet->token }}</span>
                            </div>
                            <p class="text-[10px] text-slate-400 uppercase font-black mb-1">Wallet Address</p>
                            <p class="text-xs font-mono break-all text-slate-600 dark:text-slate-400">{{ $wallet->address }}</p>
                        </div>
                    @empty
                        <div class="col-span-full py-12 text-center text-slate-400 text-sm italic">No wallets assigned to this user.</div>
                    @endforelse
                </div>
            @elseif($activeTab === 'bots')
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @forelse($user->userBots as $bot)
                        <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 p-6 rounded-2xl flex items-center gap-6">
                            <div class="w-16 h-16 rounded-2xl bg-primary/10 flex items-center justify-center">
                                <span class="material-symbols-outlined text-3xl text-primary">smart_toy</span>
                            </div>
                            <div>
                                <h4 class="font-black text-slate-900 dark:text-white">{{ $bot->botPlan->name }}</h4>
                                <p class="text-xs text-slate-500">Investment: ${{ number_format($bot->amount, 2) }}</p>
                                <p class="text-[10px] font-black text-emerald-600 uppercase mt-1">Status: Active</p>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-full py-12 text-center text-slate-400 text-sm italic">User has not activated any bot plans yet.</div>
                    @endforelse
                </div>
            @elseif($activeTab === 'kyc')
                <div class="space-y-8">
                    @forelse($kycVerifications as $kyc)
                        <div class="border-b border-slate-100 dark:border-slate-800 pb-8 last:border-0 last:pb-0">
                            <div class="flex flex-col lg:flex-row gap-8">
                                <div class="w-full lg:w-1/3 aspect-video rounded-2xl overflow-hidden bg-slate-100 border border-slate-200">
                                    <img src="{{ asset('storage/'.$kyc->id_front) }}" class="w-full h-full object-cover">
                                    <p class="p-2 text-[10px] text-center font-black uppercase text-slate-400 bg-white">ID Front</p>
                                </div>
                                <div class="w-full lg:w-1/3 aspect-video rounded-2xl overflow-hidden bg-slate-100 border border-slate-200">
                                    <img src="{{ asset('storage/'.$kyc->id_back) }}" class="w-full h-full object-cover">
                                    <p class="p-2 text-[10px] text-center font-black uppercase text-slate-400 bg-white">ID Back</p>
                                </div>
                                <div class="flex-1 space-y-4">
                                    <div>
                                        <p class="text-[10px] text-slate-400 font-black uppercase mb-1">Method</p>
                                        <p class="text-sm font-bold text-slate-900 dark:text-white">{{ ucfirst($kyc->type) }}</p>
                                    </div>
                                    <div>
                                        <p class="text-[10px] text-slate-400 font-black uppercase mb-1">Submitted</p>
                                        <p class="text-sm font-bold text-slate-900 dark:text-white">{{ $kyc->created_at->diffForHumans() }}</p>
                                    </div>
                                    <a href="{{ route('admin.kyc.details', $kyc) }}" class="inline-flex items-center gap-2 text-xs font-black text-primary hover:underline">
                                        Review Submission Details
                                        <span class="material-symbols-outlined text-lg">open_in_new</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="py-12 text-center text-slate-400 text-sm italic">No KYC documents uploaded by this user.</div>
                    @endforelse
                </div>
            @endif
        </div>
    </div>
</div>
