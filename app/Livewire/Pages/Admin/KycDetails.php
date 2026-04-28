<?php

namespace App\Livewire\Pages\Admin;

use App\Models\KycVerification;
use Livewire\Component;

class KycDetails extends Component
{
    public KycVerification $kyc;
    public $rejection_reason;

    public function mount(KycVerification $kyc)
    {
        $this->kyc = $kyc;
    }

    public function approve()
    {
        $this->kyc->update(['status' => 'approved']);
        $this->kyc->user->update(['kyc_status' => 'approved']);

        session()->flash('message', 'KYC Approved Successfully.');
        return redirect()->route('admin.kyc.review');
    }

    public function reject()
    {
        $this->validate(['rejection_reason' => 'required|string|max:255']);

        $this->kyc->update([
            'status' => 'rejected',
            'rejection_reason' => $this->rejection_reason
        ]);
        $this->kyc->user->update(['kyc_status' => 'rejected']);

        session()->flash('message', 'KYC Rejected.');
        return redirect()->route('admin.kyc.review');
    }

    public function render()
    {
        return view('livewire.pages.admin.kyc-details')->layout('layouts.app');
    }
}
