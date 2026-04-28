<?php

namespace App\Livewire\Pages;

use App\Models\KycVerification;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Auth;

class KycSubmission extends Component
{
    use WithFileUploads;

    public $first_name;
    public $last_name;
    public $date_of_birth;
    public $document_type = 'passport';
    public $document_number;
    public $image_front;
    public $image_back;

    protected $rules = [
        'first_name' => 'required|string|max:255',
        'last_name' => 'required|string|max:255',
        'date_of_birth' => 'required|date|before:today',
        'document_type' => 'required|in:passport,id_card,license',
        'document_number' => 'required|string|max:255',
        'image_front' => 'required|image|max:10240', // 10MB Max
        'image_back' => 'nullable|image|max:10240',
    ];

    public $currentStep = 1;

    public $kycStatus;

    public function mount()
    {
        $this->kycStatus = Auth::user()->kyc_status;
    }

    public function nextStep()
    {
        if ($this->currentStep === 1) {
            $this->validate([
                'first_name' => 'required|string|max:255',
                'last_name' => 'required|string|max:255',
                'date_of_birth' => 'required|date|before:today',
            ]);
        } elseif ($this->currentStep === 2) {
            $this->validate([
                'document_type' => 'required|in:passport,id_card,license',
                'document_number' => 'required|string|max:255',
            ]);
        }

        $this->currentStep++;
    }

    public function prevStep()
    {
        $this->currentStep--;
    }

    public function submit()
    {
        $this->validate();

        $imageFrontPath = $this->image_front->store('kyc-documents', 'public');
        $imageBackPath = $this->image_back ? $this->image_back->store('kyc-documents', 'public') : null;

        KycVerification::create([
            'user_id' => Auth::id(),
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'date_of_birth' => $this->date_of_birth,
            'document_type' => $this->document_type,
            'document_number' => $this->document_number,
            'image_front_path' => $imageFrontPath,
            'image_back_path' => $imageBackPath,
            'status' => 'pending',
        ]);

        Auth::user()->update(['kyc_status' => 'pending']);
        $this->kycStatus = 'pending';

        session()->flash('message', 'KYC documents submitted successfully. Verification pending.');
    }

    public function render()
    {
        return view('livewire.pages.kyc-submission')->layout('layouts.app');
    }
}
