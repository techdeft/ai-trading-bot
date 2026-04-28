<div class="py-12">
    <style>
        @keyframes fade-in-up {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-fade-in-up {
            animation: fade-in-up 0.5s ease-out forwards;
        }
    </style>
    <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
        <!-- Progress Stepper -->
        @if($kycStatus !== 'pending' && $kycStatus !== 'approved')
        <div class="mb-8">
            <div class="flex items-center justify-between relative z-0">
                <div class="absolute left-0 top-1/2 -translate-y-1/2 w-full h-0.5 bg-slate-800 rounded-full -z-10">
                </div>
                <div class="absolute left-0 top-1/2 -translate-y-1/2 h-0.5 bg-primary rounded-full transition-all duration-500 -z-10 shadow-[0_0_10px_rgba(13,127,242,0.5)]"
                    style="width: {{ ($currentStep - 1) * 50 }}%"></div>

                <!-- Step 1 Indicator -->
                <div class="flex flex-col items-center gap-1.5 bg-background-dark px-2">
                    <div
                        class="w-8 h-8 rounded-full flex items-center justify-center font-bold text-xs transition-all duration-300 border {{ $currentStep >= 1 ? 'border-primary bg-primary text-white shadow-[0_0_10px_rgba(13,127,242,0.4)]' : 'border-slate-700 bg-surface-dark text-slate-500' }}">
                        @if($currentStep > 1)
                            <span class="material-symbols-outlined text-sm">check</span>
                        @else
                            1
                        @endif
                    </div>
                    <span
                        class="text-[10px] font-medium uppercase tracking-wider {{ $currentStep >= 1 ? 'text-primary' : 'text-slate-500' }}">Personal</span>
                </div>

                <!-- Step 2 Indicator -->
                <div class="flex flex-col items-center gap-1.5 bg-background-dark px-2">
                    <div
                        class="w-8 h-8 rounded-full flex items-center justify-center font-bold text-xs transition-all duration-300 border {{ $currentStep >= 2 ? 'border-primary bg-primary text-white shadow-[0_0_10px_rgba(13,127,242,0.4)]' : 'border-slate-700 bg-surface-dark text-slate-500' }}">
                        @if($currentStep > 2)
                            <span class="material-symbols-outlined text-sm">check</span>
                        @else
                            2
                        @endif
                    </div>
                    <span
                        class="text-[10px] font-medium uppercase tracking-wider {{ $currentStep >= 2 ? 'text-primary' : 'text-slate-500' }}">Document</span>
                </div>

                <!-- Step 3 Indicator -->
                <div class="flex flex-col items-center gap-1.5 bg-background-dark px-2">
                    <div
                        class="w-8 h-8 rounded-full flex items-center justify-center font-bold text-xs transition-all duration-300 border {{ $currentStep >= 3 ? 'border-primary bg-primary text-white shadow-[0_0_10px_rgba(13,127,242,0.4)]' : 'border-slate-700 bg-surface-dark text-slate-500' }}">
                        3
                    </div>
                    <span
                        class="text-[10px] font-medium uppercase tracking-wider {{ $currentStep >= 3 ? 'text-primary' : 'text-slate-500' }}">Verify</span>
                </div>
            </div>
        </div>
        @endif

        <div
            class="bg-surface lg:bg-surface-dark/50 backdrop-blur-xl overflow-hidden shadow-2xl sm:rounded-xl relative">
            <!-- Decorative Glow -->
            <div class="absolute top-0 right-0 w-64 h-64 bg-primary/5 rounded-full blur-3xl -z-10 pointer-events-none">
            </div>

            <div class="p-6 lg:p-8">
                <div class="mb-6 text-center">
                    <h2 class="text-xl font-bold text-white mb-1">Identity Verification</h2>
                    <p class="text-slate-400 text-sm">Step {{ $currentStep }} of 3:
                        {{ $currentStep === 1 ? 'Tell us about yourself' : ($currentStep === 2 ? 'Select your document' : 'Upload proof') }}
                    </p>
                </div>

                @if($kycStatus === 'pending')
                    <div class="text-center py-12 animate-fade-in-up">
                        <div class="w-24 h-24 bg-yellow-500/10 rounded-full flex items-center justify-center mx-auto mb-6">
                            <span class="material-symbols-outlined text-5xl text-yellow-500">pending_actions</span>
                        </div>
                        <h2 class="text-3xl font-bold text-white mb-4">Verification Pending</h2>
                        <p class="text-slate-400 max-w-lg mx-auto text-sm leading-relaxed">
                            Your documents have been submitted and are currently under review. <br>
                            This process usually takes 24-48 hours. You will be notified once completed.
                        </p>
                        <div class="mt-8">
                            <a href="{{ route('dashboard') }}"
                                class="inline-flex items-center gap-2 text-primary hover:text-white transition-colors font-medium">
                                <span class="material-symbols-outlined">arrow_back</span> Return to Dashboard
                            </a>
                        </div>
                    </div>
                @elseif($kycStatus === 'approved')
                    <div class="text-center py-12 animate-fade-in-up">
                        <div
                            class="w-24 h-24 bg-success-green/10 rounded-full flex items-center justify-center mx-auto mb-6">
                            <span class="material-symbols-outlined text-5xl text-success-green">verified_user</span>
                        </div>
                        <h2 class="text-3xl font-bold text-white mb-4">Identity Verified</h2>
                        <p class="text-slate-400 max-w-lg mx-auto text-sm leading-relaxed">
                            Congratulations! Your identity has been successfully verified. <br>
                            You now have full access to all platform features, including withdrawals.
                        </p>
                        <div class="mt-8">
                            <button disabled
                                class="bg-success-green/20 text-success-green px-8 py-3 rounded-xl font-bold cursor-default flex items-center gap-2 mx-auto">
                                <span class="material-symbols-outlined">check_circle</span> Verified
                            </button>
                        </div>
                    </div>
                @else
                    <form wire:submit.prevent="submit">

                        <!-- Step 1: Personal Details -->
                        @if($currentStep === 1)
                            <div class="space-y-5 animate-fade-in-up">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                    <div class="group">
                                        <label for="first_name"
                                            class="block text-xs font-semibold text-slate-300 mb-1.5 transition-colors group-focus-within:text-primary">First
                                            Name</label>
                                        <input wire:model="first_name" type="text"
                                            class="w-full bg-background-dark/50 border border-border-dark rounded-lg px-3 py-2.5 text-sm text-white focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition-all shadow-inner">
                                        @error('first_name') <span
                                        class="text-error-red text-[10px] mt-1 block">{{ $message }}</span> @enderror
                                    </div>

                                    <div class="group">
                                        <label for="last_name"
                                            class="block text-xs font-semibold text-slate-300 mb-1.5 transition-colors group-focus-within:text-primary">Last
                                            Name</label>
                                        <input wire:model="last_name" type="text"
                                            class="w-full bg-background-dark/50 border border-border-dark rounded-lg px-3 py-2.5 text-sm text-white focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition-all shadow-inner">
                                        @error('last_name') <span
                                        class="text-error-red text-[10px] mt-1 block">{{ $message }}</span> @enderror
                                    </div>
                                </div>

                                <div class="group">
                                    <label for="date_of_birth"
                                        class="block text-xs font-semibold text-slate-300 mb-1.5 transition-colors group-focus-within:text-primary">Date
                                        of Birth</label>
                                    <input wire:model="date_of_birth" type="date"
                                        class="w-full bg-background-dark/50 border border-border-dark rounded-lg px-3 py-2.5 text-sm text-white focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition-all shadow-inner [color-scheme:dark]">
                                    @error('date_of_birth') <span
                                    class="text-error-red text-[10px] mt-1 block">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        @endif

                        <!-- Step 2: Document Details -->
                        @if($currentStep === 2)
                            <div class="space-y-5 animate-fade-in-up">
                                <div class="group">
                                    <label for="document_type"
                                        class="block text-xs font-semibold text-slate-300 mb-2 transition-colors group-focus-within:text-primary">Document
                                        Type</label>
                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                                        <label class="cursor-pointer">
                                            <input type="radio" wire:model="document_type" value="passport"
                                                class="peer sr-only">
                                            <div
                                                class="p-3 rounded-lg border border-border-dark bg-background-dark/50 peer-checked:border-primary peer-checked:bg-primary/10 peer-checked:text-primary transition-all flex flex-col items-center gap-1.5 hover:border-slate-600">
                                                <span class="material-symbols-outlined text-xl">flight_takeoff</span>
                                                <span class="text-xs font-bold">Passport</span>
                                            </div>
                                        </label>
                                        <label class="cursor-pointer">
                                            <input type="radio" wire:model="document_type" value="id_card" class="peer sr-only">
                                            <div
                                                class="p-3 rounded-lg border border-border-dark bg-background-dark/50 peer-checked:border-primary peer-checked:bg-primary/10 peer-checked:text-primary transition-all flex flex-col items-center gap-1.5 hover:border-slate-600">
                                                <span class="material-symbols-outlined text-xl">badge</span>
                                                <span class="text-xs font-bold">National ID</span>
                                            </div>
                                        </label>
                                        <label class="cursor-pointer">
                                            <input type="radio" wire:model="document_type" value="license" class="peer sr-only">
                                            <div
                                                class="p-3 rounded-lg border border-border-dark bg-background-dark/50 peer-checked:border-primary peer-checked:bg-primary/10 peer-checked:text-primary transition-all flex flex-col items-center gap-1.5 hover:border-slate-600">
                                                <span class="material-symbols-outlined text-xl">directions_car</span>
                                                <span class="text-xs font-bold">License</span>
                                            </div>
                                        </label>
                                    </div>
                                </div>

                                <div class="group">
                                    <label for="document_number"
                                        class="block text-xs font-semibold text-slate-300 mb-1.5 transition-colors group-focus-within:text-primary">Document
                                        Number</label>
                                    <input wire:model="document_number" type="text"
                                        class="w-full bg-background-dark/50 border border-border-dark rounded-lg px-3 py-2.5 text-sm text-white focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition-all shadow-inner"
                                        placeholder="Enter ID Number">
                                    @error('document_number') <span
                                    class="text-error-red text-[10px] mt-1 block">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        @endif

                        <!-- Step 3: Uploads -->
                        @if($currentStep === 3)
                            <div class="space-y-6 animate-fade-in-up">
                                <div class="bg-primary/5 border border-primary/20 rounded-lg p-3 flex items-start gap-3">
                                    <span class="material-symbols-outlined text-primary text-lg shrink-0 mt-0.5">verified_user</span>
                                    <div class="text-xs text-slate-300">
                                        <p class="font-bold text-white mb-0.5">Secure Transmission</p>
                                        Your documents are encrypted and stored offline.
                                    </div>
                                </div>

                                <div class="grid md:grid-cols-2 gap-4">
                                    <!-- Front Side -->
                                    <div>
                                        <label class="block text-xs font-semibold text-slate-300 mb-1.5">Front Side</label>
                                        <div class="relative group">
                                            <label
                                                class="flex flex-col items-center justify-center w-full h-32 border-2 border-dashed border-border-dark rounded-lg cursor-pointer bg-background-dark/30 hover:bg-background-dark/50 hover:border-primary/50 transition-all overflow-hidden group">
                                                
                                                @if($image_front)
                                                    <img src="{{ $image_front->temporaryUrl() }}" class="w-full h-full object-cover">
                                                @else
                                                    <div class="flex flex-col items-center justify-center pt-5 pb-6 text-slate-500 group-hover:text-primary transition-colors">
                                                        <span class="material-symbols-outlined text-3xl mb-2">cloud_upload</span>
                                                        <p class="text-xs font-bold">Upload front</p>
                                                    </div>
                                                @endif
                                                
                                                <input type="file" wire:model="image_front" class="hidden" accept="image/*" />
                                            </label>   
                                            <div wire:loading wire:target="image_front" class="absolute inset-0 bg-background-dark/80 flex items-center justify-center rounded-lg backdrop-blur-sm">
                                                <div class="flex flex-col items-center">
                                                    <div class="w-6 h-6 border-2 border-primary border-t-transparent rounded-full animate-spin mb-1"></div>
                                                    <span class="text-primary text-[10px] font-bold">Uploading...</span>
                                                </div>
                                            </div>
                                        </div>
                                        @error('image_front') <span class="text-error-red text-[10px] mt-1 block">{{ $message }}</span> @enderror
                                    </div>

                                    <!-- Back Side -->
                                    @if($document_type !== 'passport')
                                    <div>
                                        <label class="block text-xs font-semibold text-slate-300 mb-1.5">Back Side</label>
                                        <div class="relative group">
                                            <label
                                                class="flex flex-col items-center justify-center w-full h-32 border-2 border-dashed border-border-dark rounded-lg cursor-pointer bg-background-dark/30 hover:bg-background-dark/50 hover:border-primary/50 transition-all overflow-hidden group">
                                                
                                                @if($image_back)
                                                    <img src="{{ $image_back->temporaryUrl() }}" class="w-full h-full object-cover">
                                                @else
                                                    <div class="flex flex-col items-center justify-center pt-5 pb-6 text-slate-500 group-hover:text-primary transition-colors">
                                                        <span class="material-symbols-outlined text-3xl mb-2">cloud_upload</span>
                                                        <p class="text-xs font-bold">Upload back</p>
                                                    </div>
                                                @endif
                                                
                                                <input type="file" wire:model="image_back" class="hidden" accept="image/*" />
                                            </label>   
                                            <div wire:loading wire:target="image_back" class="absolute inset-0 bg-background-dark/80 flex items-center justify-center rounded-lg backdrop-blur-sm">
                                                <div class="flex flex-col items-center">
                                                    <div class="w-6 h-6 border-2 border-primary border-t-transparent rounded-full animate-spin mb-1"></div>
                                                    <span class="text-primary text-[10px] font-bold">Uploading...</span>
                                                </div>
                                            </div>
                                        </div>
                                        @error('image_back') <span class="text-error-red text-[10px] mt-1 block">{{ $message }}</span> @enderror
                                    </div>
                                    @endif
                                </div>
                            </div>
                        @endif

                        <!-- Actions -->
                        <div class="pt-6 flex justify-between items-center mt-2">
                            @if($currentStep > 1)
                                <button type="button" wire:click="prevStep" class="text-slate-400 hover:text-white text-sm font-semibold transition-colors flex items-center gap-1.5">
                                    <span class="material-symbols-outlined text-lg">arrow_back</span> Back
                                </button>
                            @else
                                <div></div>
                            @endif

                            @if($currentStep < 3)
                                <button type="button" wire:click="nextStep" class="bg-primary hover:bg-primary/90 text-white px-6 py-2.5 rounded-lg text-sm font-bold transition-all shadow-lg hover:shadow-primary/20 flex items-center gap-1.5">
                                    Next Step <span class="material-symbols-outlined text-lg">arrow_forward</span>
                                </button>
                            @else
                                <button type="submit" class="bg-gradient-to-r from-primary to-blue-500 hover:from-primary/90 hover:to-blue-500/90 text-white px-8 py-2.5 rounded-lg text-sm font-bold transition-all shadow-lg hover:shadow-primary/30 flex items-center gap-2" wire:loading.attr="disabled">
                                    <span wire:loading.remove>Submit Documents</span>
                                    <span wire:loading class="flex items-center gap-2">
                                        <span class="w-3 h-3 border-2 border-white border-t-transparent rounded-full animate-spin"></span>
                                        Processing...
                                    </span>
                                </button>
                            @endif
                        </div>
                    </form>
                @endif
            </div>
        </div>
    </div>
</div>