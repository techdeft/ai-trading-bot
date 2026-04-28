<?php

use Livewire\Volt\Component;
use App\Models\Wallet;
use App\Models\Withdrawal;
use Illuminate\Support\Facades\Auth;
use App\Services\BlockchainService;

use Livewire\Attributes\Layout;

use App\Models\Deposit;

new #[Layout('layouts.app')] class extends Component {
    public $withdrawAmount;
    public $withdrawAddress;
    public $withdrawToken = 'USDT';
    public $detectedBalance = null;

    // Deposit Properties
    public $selectedWallet = null;
    public $depositAmount;
    public $prooftransactionHash;

    public function with()
    {
        return [
            'wallets' => Auth::user()->wallets,
            'withdrawals' => Auth::user()->withdrawals()->latest()->get(),
            'deposits' => Auth::user()->deposits()->latest()->get(),
        ];
    }

    public function selectDepositWallet($walletId)
    {
        $this->selectedWallet = Auth::user()->wallets->find($walletId);
        $this->reset(['depositAmount', 'prooftransactionHash']);
    }

    public function submitDeposit()
    {
        $this->validate([
            'depositAmount' => 'required|numeric|min:0.00000001',
            'prooftransactionHash' => 'required|string|unique:deposits,transaction_hash|min:10',
        ]);

        if (!$this->selectedWallet) {
            $this->addError('prooftransactionHash', 'Please select a wallet first.');
            return;
        }

        // Create Deposit Record (Submitted)
        Deposit::create([
            'user_id' => Auth::id(),
            'wallet_id' => $this->selectedWallet->id,
            'amount' => $this->depositAmount,
            'transaction_hash' => $this->prooftransactionHash,
            'status' => 'submitted',
        ]);

        session()->flash('deposit_success', 'Deposit submitted! Please check the status in the table below once the transaction is confirmed on the blockchain.');
        $this->reset(['depositAmount', 'prooftransactionHash', 'selectedWallet']);
    }

    public function checkDepositStatus($depositId)
    {
        $blockchainService = app(BlockchainService::class);
        $deposit = Auth::user()->deposits()->where('id', $depositId)->first();

        if (!$deposit || $deposit->status !== 'submitted') {
            return;
        }

        // Verify on Blockchain
        $isValid = $blockchainService->verifyTransaction(
            $deposit->wallet->network,
            $deposit->transaction_hash,
            (float) $deposit->amount,
            $deposit->wallet->address
        );

        if ($isValid) {
            $deposit->update(['status' => 'approved']);
            Auth::user()->increment('balance', $deposit->amount);
            session()->flash('status_success', 'Deposit confirmed and balance credited!');
        } else {
            session()->flash('status_error', 'Transaction not yet confirmed. Please try again later.');
        }
    }

    public function checkBalance(BlockchainService $blockchainService, $walletId)
    {
        $wallet = Auth::user()->wallets->find($walletId);
        if ($wallet) {
            $this->detectedBalance = $blockchainService->getBalance($wallet->network, $wallet->address);
        }
    }

    public function requestWithdrawal()
    {
        $validated = $this->validate([
            'withdrawAmount' => 'required|numeric|min:0.00000001',
            'withdrawAddress' => 'required|string',
            'withdrawToken' => 'required|string',
        ]);

        if (Auth::user()->kyc_status !== 'approved') {
            $this->addError('withdrawAmount', 'Identity validation (KYC) is required for withdrawals.');
            return;
        }

        if (Auth::user()->balance < $this->withdrawAmount) {
            $this->addError('withdrawAmount', 'Insufficient balance.');
            return;
        }

        // Deduct balance immediately
        Auth::user()->decrement('balance', $this->withdrawAmount);

        Withdrawal::create([
            'user_id' => Auth::id(),
            'amount' => $this->withdrawAmount,
            'token' => $this->withdrawToken,
            'wallet_address' => $this->withdrawAddress,
            'status' => 'pending',
        ]);

        $this->reset(['withdrawAmount', 'withdrawAddress']);
        $this->dispatch('withdrawal-requested');
        $this->showWithdrawModal = false;
    }

    public $showWithdrawModal = false;

    public function openWithdrawModal()
    {
        $this->reset(['withdrawAmount', 'withdrawAddress', 'withdrawToken']);
        $this->showWithdrawModal = true;
    }

    public function closeWithdrawModal()
    {
        $this->showWithdrawModal = false;
    }
}; ?>

<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

        <!-- Balance & Actions -->
        <div
            class="bg-white dark:bg-surface overflow-hidden shadow-sm sm:rounded-xl border border-slate-200 dark:border-slate-700 p-6 flex justify-between items-center">
            <div>
                <h3 class="text-lg font-medium text-slate-900 dark:text-white">Current Balance</h3>
                <div class="mt-2 text-3xl font-bold text-white">
                    ${{ number_format(auth()->user()->balance ?? 0, 2) }}
                </div>
            </div>
            <button wire:click="openWithdrawModal"
                class="px-4 py-2 bg-primary hover:bg-indigo-700 text-white rounded-lg font-bold shadow-sm transition-colors flex items-center gap-2">
                <span class="material-symbols-outlined">payments</span>
                Request Withdrawal
            </button>
        </div>

        <!-- Deposit Section -->
        <div
            class="bg-white dark:bg-surface overflow-hidden shadow-sm sm:rounded-xl border border-slate-200 dark:border-slate-700 p-6">
            <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-4">Deposit Funds</h3>

            @if($wallets->isEmpty())
                <div class="bg-yellow-50 dark:bg-yellow-500/10 border-l-4 border-yellow-400 p-4">
                    <div class="flex">
                        <div class="ml-3">
                            <p class="text-sm text-yellow-700 dark:text-yellow-500">
                                No deposit wallet assigned. Please contact support.
                            </p>
                        </div>
                    </div>
                </div>
            @else
                @if(!$selectedWallet)
                    <div class="space-y-4">
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300">Select Network /
                            Token</label>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3 mt-1">
                            @foreach($wallets as $wallet)
                                <button wire:click="selectDepositWallet({{ $wallet->id }})"
                                    class="flex justify-between items-center p-3 border border-slate-200 dark:border-slate-700 rounded-lg hover:bg-slate-50 dark:hover:bg-background-dark/50 transition-colors text-left w-full group">
                                    <span
                                        class="font-bold text-slate-700 dark:text-slate-300 group-hover:text-primary transition-colors">{{ $wallet->token }}
                                        ({{ $wallet->network }})</span>
                                    <span
                                        class="material-symbols-outlined text-slate-400 group-hover:text-primary">chevron_right</span>
                                </button>
                            @endforeach
                        </div>
                    </div>
                @else
                    <div class="space-y-6">
                        <button wire:click="$set('selectedWallet', null)"
                            class="text-sm text-slate-500 hover:text-slate-700 dark:text-slate-400 dark:hover:text-slate-200 flex items-center gap-1">
                            <span class="material-symbols-outlined text-sm">arrow_back</span> Back to Networks
                        </button>

                        <div
                            class="p-4 bg-slate-50 dark:bg-background-dark/50 rounded-lg border border-slate-200 dark:border-slate-700 max-w-2xl">
                            <div class="mb-4">
                                <label
                                    class="block text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1">Deposit
                                    Address ({{ $selectedWallet->network }})</label>
                                <div class="flex gap-2">
                                    <input type="text" readonly value="{{ $selectedWallet->address }}"
                                        class="block w-full text-sm text-slate-900 dark:text-white bg-white dark:bg-surface border border-slate-300 dark:border-slate-600 rounded-lg focus:ring-primary focus:border-primary">
                                    <button onclick="navigator.clipboard.writeText('{{ $selectedWallet->address }}')"
                                        class="px-3 py-2 bg-white dark:bg-surface border border-slate-300 dark:border-slate-600 rounded-lg text-slate-600 dark:text-slate-400 hover:text-primary hover:border-primary transition-colors">
                                        <span class="material-symbols-outlined text-sm">content_copy</span>
                                    </button>
                                </div>
                                <p class="mt-2 text-xs text-slate-500 dark:text-slate-400">
                                    Send only <strong>{{ $selectedWallet->token }}</strong> to this address.
                                </p>
                            </div>

                            <div class="border-t border-slate-200 dark:border-slate-700 pt-4">
                                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Enter Amount
                                    Sent</label>
                                <input type="number" wire:model="depositAmount" placeholder="0.00" step="0.01"
                                    class="block w-full rounded-lg border-slate-300 dark:border-slate-600 bg-white dark:bg-surface text-slate-900 dark:text-white shadow-sm focus:border-primary focus:ring-primary">
                                @error('depositAmount') <span class="text-danger text-xs">{{ $message }}</span> @enderror
                            </div>

                            <div class="mt-4">
                                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Transaction
                                    Hash (TXID)</label>
                                <input type="text" wire:model="prooftransactionHash"
                                    placeholder="paste transaction hash here..."
                                    class="block w-full rounded-lg border-slate-300 dark:border-slate-600 bg-white dark:bg-surface text-slate-900 dark:text-white shadow-sm focus:border-primary focus:ring-primary font-mono text-sm">
                                @error('prooftransactionHash') <span class="text-danger text-xs">{{ $message }}</span> @enderror
                            </div>

                            <div class="mt-6">
                                <button wire:click="submitDeposit" wire:loading.attr="disabled"
                                    class="w-full flex justify-center py-2 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-primary hover:bg-primary/90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary disabled:opacity-50 disabled:cursor-not-allowed">
                                    <span wire:loading.remove wire:target="submitDeposit">Submit Deposit & Wait for
                                        Confirmation</span>
                                    <span wire:loading wire:target="submitDeposit" class="flex items-center gap-2">
                                        <span class="material-symbols-outlined animate-spin text-sm">progress_activity</span>
                                        Submitting...
                                    </span>
                                </button>
                                <p class="mt-2 text-center text-xs text-slate-500 dark:text-slate-400">
                                    Your deposit will be verified on the blockchain. Funds are credited once confirmed.
                                </p>
                            </div>

                            @if(session()->has('deposit_success'))
                                <div
                                    class="mt-4 p-3 bg-success/10 border border-success/20 rounded-lg flex items-center gap-2 text-success text-sm font-bold">
                                    <span class="material-symbols-outlined text-sm">check_circle</span>
                                    {{ session('deposit_success') }}
                                </div>
                            @endif

                            @if(session()->has('deposit_error'))
                                <div
                                    class="mt-4 p-3 bg-danger/10 border border-danger/20 rounded-lg flex items-center gap-2 text-danger text-sm font-bold">
                                    <span class="material-symbols-outlined text-sm">error</span>
                                    {{ session('deposit_error') }}
                                </div>
                            @endif
                        </div>
                    </div>
                @endif
            @endif
        </div>

        <!-- History Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Deposit History -->
            <div
                class="bg-white dark:bg-surface overflow-hidden shadow-sm sm:rounded-xl border border-slate-200 dark:border-slate-700 p-6">
                <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-4">Deposit History</h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-200 dark:divide-slate-700">
                        <thead class="bg-slate-50 dark:bg-background-dark/50">
                            <tr>
                                <th
                                    class="px-6 py-3 text-left text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">
                                    Date</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">
                                    Amount</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">
                                    Status</th>
                                <th
                                    class="px-6 py-3 text-right text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">
                                    Action</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-surface divide-y divide-slate-200 dark:divide-slate-700">
                            @forelse($deposits as $deposit)
                                                    <tr wire:key="deposit-{{ $deposit->id }}"
                                                        class="hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors">
                                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500 dark:text-slate-400">
                                                            {{ $deposit->created_at->format('M d') }}
                                                        </td>
                                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-success">
                                                            +${{ number_format($deposit->amount, 2) }}
                                                        </td>
                                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                                            <span class="px-2 inline-flex text-xs leading-5 font-bold rounded-full 
                                                                                                                                                                                                                                                                                            {{ $deposit->status === 'approved' ? 'bg-success/10 text-success' :
                                ($deposit->status === 'rejected' ? 'bg-danger/10 text-danger' :
                                    'bg-yellow-100 text-yellow-800') }}">
                                                                {{ ucfirst($deposit->status) }}
                                                            </span>
                                                        </td>
                                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm">
                                                            @if($deposit->status === 'submitted')
                                                                <button wire:click="checkDepositStatus({{ $deposit->id }})"
                                                                    wire:loading.attr="disabled"
                                                                    class="text-xs bg-indigo-50 text-indigo-700 hover:bg-indigo-100 px-3 py-1 rounded-full border border-indigo-200 transition-colors font-medium disabled:opacity-50">
                                                                    <span wire:loading.remove
                                                                        wire:target="checkDepositStatus({{ $deposit->id }})">Check Status</span>
                                                                    <span wire:loading
                                                                        wire:target="checkDepositStatus({{ $deposit->id }})">Checking...</span>
                                                                </button>
                                                            @else
                                                                <span class="text-slate-400 text-xs">-</span>
                                                            @endif
                                                        </td>
                                                    </tr>
                            @empty
                                <tr>
                                    <td colspan="3"
                                        class="px-6 py-4 text-center text-sm text-slate-500 dark:text-slate-400">No
                                        deposits.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Withdrawal History -->
            <div
                class="bg-white dark:bg-surface overflow-hidden shadow-sm sm:rounded-xl border border-slate-200 dark:border-slate-700 p-6">
                <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-4">Withdrawal History</h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-200 dark:divide-slate-700">
                        <thead class="bg-slate-50 dark:bg-background-dark/50">
                            <tr>
                                <th
                                    class="px-6 py-3 text-left text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">
                                    Date</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">
                                    Amount</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">
                                    Status</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-surface divide-y divide-slate-200 dark:divide-slate-700">
                            @foreach($withdrawals as $withdrawal)
                                <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500 dark:text-slate-400">
                                        {{ $withdrawal->created_at->format('M d') }}
                                    </td>
                                    <td
                                        class="px-6 py-4 whitespace-nowrap text-sm font-bold text-slate-900 dark:text-white">
                                        {{ $withdrawal->amount }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        <span
                                            class="px-2 inline-flex text-xs leading-5 font-bold rounded-full bg-{{ $withdrawal->status === 'approved' ? 'success' : ($withdrawal->status === 'rejected' ? 'danger' : 'yellow') }}/10 text-{{ $withdrawal->status === 'approved' ? 'success' : ($withdrawal->status === 'rejected' ? 'danger' : 'yellow-600') }}">
                                            {{ ucfirst($withdrawal->status) }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Withdrawal Modal -->
    @if($showWithdrawModal)
        <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-gray-500 dark:bg-gray-900 bg-opacity-75 transition-opacity" aria-hidden="true"
                    wire:click="closeWithdrawModal"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                <div
                    class="inline-block align-bottom bg-white dark:bg-surface rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <div class="bg-white dark:bg-surface px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <h3 class="text-lg leading-6 font-medium text-slate-900 dark:text-white mb-4" id="modal-title">
                            Request Withdrawal</h3>
                        <form wire:submit="requestWithdrawal">
                            <div class="space-y-4">
                                <div>
                                    <label
                                        class="block text-sm font-medium text-slate-700 dark:text-slate-300">Amount</label>
                                    <input type="number" step="any" wire:model="withdrawAmount"
                                        class="mt-1 block w-full rounded-lg border-slate-300 dark:border-slate-600 bg-white dark:bg-background-dark text-slate-900 dark:text-white shadow-sm focus:border-primary focus:ring-primary">
                                    @error('withdrawAmount') <span class="text-danger text-xs">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div>
                                    <label
                                        class="block text-sm font-medium text-slate-700 dark:text-slate-300">Token</label>
                                    <select wire:model="withdrawToken"
                                        class="mt-1 block w-full rounded-lg border-slate-300 dark:border-slate-600 bg-white dark:bg-background-dark text-slate-900 dark:text-white shadow-sm focus:border-primary focus:ring-primary">
                                        <option value="USDT">USDT</option>
                                        <option value="BTC">BTC</option>
                                    </select>
                                    @error('withdrawToken') <span class="text-danger text-xs">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300">Destination
                                        Address</label>
                                    <input type="text" wire:model="withdrawAddress"
                                        class="mt-1 block w-full rounded-lg border-slate-300 dark:border-slate-600 bg-white dark:bg-background-dark text-slate-900 dark:text-white shadow-sm focus:border-primary focus:ring-primary">
                                    @error('withdrawAddress') <span class="text-danger text-xs">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="mt-5 sm:mt-6 sm:grid sm:grid-cols-2 sm:gap-3 sm:grid-flow-row-dense">
                                <button type="submit"
                                    class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:col-start-2 sm:text-sm">
                                    Submit Request
                                </button>
                                <button type="button" wire:click="closeWithdrawModal"
                                    class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none sm:mt-0 sm:col-start-1 sm:text-sm">
                                    Cancel
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>