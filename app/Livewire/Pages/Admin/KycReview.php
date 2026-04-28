<?php

namespace App\Livewire\Pages\Admin;

use App\Models\KycVerification;
use Livewire\Component;
use Livewire\WithPagination;

class KycReview extends Component
{
    use WithPagination;

    public $status = 'pending';

    public function render()
    {
        $kycs = KycVerification::with('user')
            ->when($this->status !== 'all', function ($query) {
                $query->where('status', $this->status);
            })
            ->latest()
            ->paginate(10);

        return view('livewire.pages.admin.kyc-review', [
            'kycs' => $kycs
        ])->layout('layouts.app');
    }
}
