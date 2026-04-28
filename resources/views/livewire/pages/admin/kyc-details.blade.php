<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="flex justify-between items-center mb-8">
            <a href="{{ route('admin.kyc.review') }}"
                class="inline-flex items-center gap-2 text-slate-400 hover:text-white transition-colors">
                <span class="material-symbols-outlined text-sm">arrow_back</span> Back to List
            </a>
            <div class="flex gap-3">
                @if($kyc->status === 'pending')
                    <div x-data="{ open: false }" class="relative">
                        <button @click="open = !open"
                            class="bg-danger/10 hover:bg-danger/20 text-danger border border-danger/20 px-4 py-2 rounded-lg font-semibold text-sm transition-all flex items-center gap-2">
                            <span class="material-symbols-outlined text-lg">block</span>
                            Reject
                        </button>
                        <div x-show="open" @click.away="open = false"
                            class="absolute right-0 mt-2 w-80 bg-surface border border-slate-700/50 rounded-xl shadow-2xl p-4 z-50">
                            <label class="block text-xs font-bold text-slate-400 mb-2">Reason for Rejection</label>
                            <textarea wire:model="rejection_reason"
                                class="w-full bg-background-dark border border-slate-700 rounded-lg text-white text-sm p-3 mb-3 focus:border-primary focus:ring-1 focus:ring-primary outline-none transition-all"
                                rows="3" placeholder="Enter reason..."></textarea>
                            <button wire:click="reject"
                                class="w-full bg-danger hover:bg-danger/90 text-white px-4 py-2 rounded-lg text-sm font-bold transition-colors">
                                Confirm Rejection
                            </button>
                        </div>
                    </div>

                    <button wire:click="approve"
                        class="bg-success hover:bg-success/90 text-white px-4 py-2 rounded-lg font-semibold text-sm transition-all flex items-center gap-2 shadow-lg shadow-success/20">
                        <span class="material-symbols-outlined text-lg">check_circle</span>
                        Approve
                    </button>
                @endif
            </div>
        </div>

        <div class="grid lg:grid-cols-3 gap-8">
            <!-- Sidebar / User Info -->
            <div class="lg:col-span-1 space-y-6">
                <!-- User Card -->
                <div class="bg-surface overflow-hidden shadow-xl sm:rounded-xl p-6 relative">
                    <div class="absolute top-0 right-0 p-4">
                        <span
                            class="px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider
                            {{ $kyc->status === 'approved' ? 'bg-success/10 text-success border border-success/20' :
    ($kyc->status === 'rejected' ? 'bg-danger/10 text-danger border border-danger/20' : 'bg-warning/10 text-warning border border-warning/20') }}">
                            {{ $kyc->status }}
                        </span>
                    </div>

                    <div class="flex flex-col items-center text-center mt-4">
                        <div
                            class="w-24 h-24 rounded-full bg-surface-accent flex items-center justify-center text-3xl font-bold text-slate-400 mb-4 border-4 border-background-dark">
                            {{ $kyc->user->initials() }}
                        </div>
                        <h2 class="text-xl font-bold text-white">{{ $kyc->first_name }} {{ $kyc->last_name }}</h2>
                        <p class="text-slate-400 text-sm mt-1">{{ $kyc->user->email }}</p>

                        <div class="w-full mt-6 pt-6 border-t border-slate-700/50 grid grid-cols-2 gap-4">
                            <div>
                                <p class="text-xs text-slate-500 uppercase">Joined</p>
                                <p class="text-sm font-semibold text-slate-200">
                                    {{ $kyc->user->created_at->format('M Y') }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-slate-500 uppercase">Balance</p>
                                <p class="text-sm font-semibold text-slate-200">
                                    ${{ number_format($kyc->user->balance, 2) }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Submission Details -->
                <div class="bg-surface overflow-hidden shadow-xl sm:rounded-xl p-6">
                    <h3 class="text-sm font-bold text-slate-400 uppercase tracking-wider mb-4">Submission Details</h3>
                    <div class="space-y-4">
                        <div class="flex justify-between items-center p-3 rounded-lg bg-background-dark/50">
                            <span class="text-sm text-slate-500">Submitted</span>
                            <span
                                class="text-sm font-medium text-slate-200">{{ $kyc->created_at->format('M d, Y H:i') }}</span>
                        </div>
                        <div class="flex justify-between items-center p-3 rounded-lg bg-background-dark/50">
                            <span class="text-sm text-slate-500">Last Update</span>
                            <span
                                class="text-sm font-medium text-slate-200">{{ $kyc->updated_at->diffForHumans() }}</span>
                        </div>
                        @if($kyc->rejection_reason)
                            <div class="p-4 rounded-lg bg-danger/5 border border-danger/10 mt-4">
                                <span class="text-xs text-danger font-bold uppercase block mb-1">Rejection Reason</span>
                                <p class="text-sm text-danger/80">{{ $kyc->rejection_reason }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Personal Information -->
                <div class="bg-surface overflow-hidden shadow-xl sm:rounded-xl p-6">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="p-2 bg-primary/10 rounded-lg text-primary">
                            <span class="material-symbols-outlined">person</span>
                        </div>
                        <h3 class="text-lg font-bold text-white">Personal Information</h3>
                    </div>

                    <div class="grid sm:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-xs text-slate-500 uppercase font-semibold mb-1">Full Name</label>
                            <div class="text-base text-slate-200 font-medium">{{ $kyc->first_name }}
                                {{ $kyc->last_name }}</div>
                        </div>
                        <div>
                            <label class="block text-xs text-slate-500 uppercase font-semibold mb-1">Date of
                                Birth</label>
                            <div class="text-base text-slate-200 font-medium">
                                {{ $kyc->date_of_birth->format('F d, Y') }}</div>
                        </div>
                        <div>
                            <label class="block text-xs text-slate-500 uppercase font-semibold mb-1">Document
                                Type</label>
                            <div class="text-base text-slate-200 font-medium uppercase tracking-wide">
                                {{ str_replace('_', ' ', $kyc->document_type) }}</div>
                        </div>
                        <div>
                            <label class="block text-xs text-slate-500 uppercase font-semibold mb-1">Document
                                Number</label>
                            <div class="text-base text-slate-200 font-medium font-mono">{{ $kyc->document_number }}
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Documents -->
                <div class="bg-surface overflow-hidden shadow-xl sm:rounded-xl p-6">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="p-2 bg-primary/10 rounded-lg text-primary">
                            <span class="material-symbols-outlined">description</span>
                        </div>
                        <h3 class="text-lg font-bold text-white">Document Images</h3>
                    </div>

                    <div class="grid gap-6">
                        <div class="space-y-2">
                            <div class="flex justify-between items-center">
                                <p class="text-sm font-medium text-slate-400">Front Side</p>
                                <a href="{{ asset('storage/' . $kyc->image_front_path) }}" target="_blank"
                                    class="text-xs text-primary hover:underline flex items-center gap-1">
                                    View Full Size <span
                                        class="material-symbols-outlined text-[14px]">open_in_new</span>
                                </a>
                            </div>
                            <div
                                class="rounded-xl overflow-hidden border border-slate-700/50 bg-background-dark/50 p-2">
                                <img src="{{ asset('storage/' . $kyc->image_front_path) }}"
                                    class="w-full rounded-lg hover:opacity-90 transition-opacity cursor-pointer">
                            </div>
                        </div>

                        @if($kyc->image_back_path)
                            <div class="space-y-2">
                                <div class="flex justify-between items-center">
                                    <p class="text-sm font-medium text-slate-400">Back Side</p>
                                    <a href="{{ asset('storage/' . $kyc->image_back_path) }}" target="_blank"
                                        class="text-xs text-primary hover:underline flex items-center gap-1">
                                        View Full Size <span
                                            class="material-symbols-outlined text-[14px]">open_in_new</span>
                                    </a>
                                </div>
                                <div
                                    class="rounded-xl overflow-hidden border border-slate-700/50 bg-background-dark/50 p-2">
                                    <img src="{{ asset('storage/' . $kyc->image_back_path) }}"
                                        class="w-full rounded-lg hover:opacity-90 transition-opacity cursor-pointer">
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>