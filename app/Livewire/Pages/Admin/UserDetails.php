<?php

namespace App\Livewire\Pages\Admin;

use App\Models\User;
use App\Models\Deposit;
use App\Models\Withdrawal;
use App\Models\Transaction;
use App\Models\KycVerification;
use Livewire\Component;
use Illuminate\Support\Facades\DB;

class UserDetails extends Component
{
    public User $user;
    public $activeTab = 'overview';
    
    // Balance Adjustment
    public $amount = 0;
    public $description = '';
    public $type = 'credit'; // credit or debit

    public function mount(User $user)
    {
        $this->user = $user->load(['wallets', 'userBots.botPlan']);
    }

    public function setTab($tab)
    {
        $this->activeTab = $tab;
    }

    public function adjustBalance()
    {
        $this->validate([
            'amount' => 'required|numeric|min:0.01',
            'type' => 'required|in:credit,debit',
        ]);

        DB::transaction(function () {
            if ($this->type === 'credit') {
                $this->user->increment('balance', $this->amount);
            } else {
                $this->user->decrement('balance', $this->amount);
            }

            // Optional: Create a transaction record to log this manual adjustment
            Transaction::create([
                'user_id' => $this->user->id,
                'type' => $this->type === 'credit' ? 'manual_credit' : 'manual_debit',
                'asset_symbol' => 'USD',
                'amount' => $this->amount,
                'status' => 'completed',
            ]);
        });

        $this->reset(['amount', 'description']);
        $this->user->refresh();
        $this->dispatch('balance-updated');
    }

    public function updateKycStatus($status)
    {
        $this->user->update(['kyc_status' => $status]);
        $this->dispatch('status-updated');
    }

    public function toggleAdmin()
    {
        $this->user->update(['is_admin' => !$this->user->is_admin]);
        $this->dispatch('status-updated');
    }

    public function render()
    {
        $recentTransactions = Transaction::where('user_id', $this->user->id)
            ->latest()
            ->take(10)
            ->get();

        $deposits = Deposit::where('user_id', $this->user->id)
            ->latest()
            ->get();

        $withdrawals = Withdrawal::where('user_id', $this->user->id)
            ->latest()
            ->get();

        $kycVerifications = KycVerification::where('user_id', $this->user->id)
            ->latest()
            ->get();

        return view('livewire.pages.admin.user-details', [
            'recentTransactions' => $recentTransactions,
            'deposits' => $deposits,
            'withdrawals' => $withdrawals,
            'kycVerifications' => $kycVerifications,
        ])->layout('layouts.app');
    }
}
