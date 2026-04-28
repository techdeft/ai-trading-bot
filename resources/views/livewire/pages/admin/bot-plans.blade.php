<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Form Section -->
        @if($show_form)
            <div class="bg-surface overflow-hidden shadow-xl sm:rounded-lg p-6 mb-6">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-xl font-bold text-white">{{ $is_editing ? 'Edit Bot Plan' : 'Create New Bot Plan' }}
                    </h2>
                    <button wire:click="toggleForm" class="text-slate-400 hover:text-white transition-colors">
                        <span class="material-symbols-outlined">close</span>
                    </button>
                </div>

                <form wire:submit="{{ $is_editing ? 'update' : 'store' }}" class="space-y-6">
                    <div class="grid md:grid-cols-2 gap-6">
                        <!-- Name -->
                        <div>
                            <label class="block text-sm font-medium text-slate-400 mb-1">Plan Name</label>
                            <input wire:model="name" type="text"
                                class="w-full bg-background-dark border border-slate-700 rounded-lg text-white p-2.5 focus:border-primary focus:ring-1 focus:ring-primary outline-none transition-all">
                            @error('name') <span class="text-xs text-danger">{{ $message }}</span> @enderror
                        </div>

                        <!-- ROI -->
                        <div>
                            <label class="block text-sm font-medium text-slate-400 mb-1">ROI per Trade (%)</label>
                            <input wire:model="roi_per_trade" type="number" step="0.01"
                                class="w-full bg-background-dark border border-slate-700 rounded-lg text-white p-2.5 focus:border-primary focus:ring-1 focus:ring-primary outline-none transition-all">
                            @error('roi_per_trade') <span class="text-xs text-danger">{{ $message }}</span> @enderror
                        </div>

                        <!-- Min Amount -->
                        <div>
                            <label class="block text-sm font-medium text-slate-400 mb-1">Min Investment ($)</label>
                            <input wire:model="min_amount" type="number" step="0.01"
                                class="w-full bg-background-dark border border-slate-700 rounded-lg text-white p-2.5 focus:border-primary focus:ring-1 focus:ring-primary outline-none transition-all">
                            @error('min_amount') <span class="text-xs text-danger">{{ $message }}</span> @enderror
                        </div>

                        <!-- Max Amount -->
                        <div>
                            <label class="block text-sm font-medium text-slate-400 mb-1">Max Investment ($)</label>
                            <input wire:model="max_amount" type="number" step="0.01"
                                class="w-full bg-background-dark border border-slate-700 rounded-lg text-white p-2.5 focus:border-primary focus:ring-1 focus:ring-primary outline-none transition-all">
                            @error('max_amount') <span class="text-xs text-danger">{{ $message }}</span> @enderror
                        </div>

                        <!-- Duration -->
                        <div>
                            <label class="block text-sm font-medium text-slate-400 mb-1">Duration (Days)</label>
                            <input wire:model="duration_days" type="number"
                                class="w-full bg-background-dark border border-slate-700 rounded-lg text-white p-2.5 focus:border-primary focus:ring-1 focus:ring-primary outline-none transition-all">
                            @error('duration_days') <span class="text-xs text-danger">{{ $message }}</span> @enderror
                        </div>

                        <!-- Trades Count -->
                        <div>
                            <label class="block text-sm font-medium text-slate-400 mb-1">Total Trades</label>
                            <input wire:model="trades_count" type="number"
                                class="w-full bg-background-dark border border-slate-700 rounded-lg text-white p-2.5 focus:border-primary focus:ring-1 focus:ring-primary outline-none transition-all">
                            @error('trades_count') <span class="text-xs text-danger">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <!-- Description -->
                    <div>
                        <label class="block text-sm font-medium text-slate-400 mb-1">Description</label>
                        <textarea wire:model="description" rows="3"
                            class="w-full bg-background-dark border border-slate-700 rounded-lg text-white p-2.5 focus:border-primary focus:ring-1 focus:ring-primary outline-none transition-all"></textarea>
                        @error('description') <span class="text-xs text-danger">{{ $message }}</span> @enderror
                    </div>

                    <div class="flex justify-end gap-3">
                        <button type="button" wire:click="toggleForm"
                            class="px-4 py-2 bg-slate-700 hover:bg-slate-600 text-white rounded-lg transition-colors font-medium">
                            Cancel
                        </button>
                        <button type="submit"
                            class="px-4 py-2 bg-primary hover:bg-primary/90 text-white rounded-lg transition-colors font-bold shadow-lg shadow-primary/20">
                            {{ $is_editing ? 'Update Plan' : 'Create Plan' }}
                        </button>
                    </div>
                </form>
            </div>
        @endif

        <!-- List Section -->
        <div class="bg-surface overflow-hidden shadow-xl sm:rounded-lg p-6">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold text-white">Bot Plans</h2>
                @if(!$show_form)
                    <button wire:click="toggleForm"
                        class="bg-primary hover:bg-primary/90 text-white font-bold py-2.5 px-4 rounded-lg transition-colors flex items-center gap-2 shadow-lg shadow-primary/20">
                        <span class="material-symbols-outlined">add</span> Create New Plan
                    </button>
                @endif
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left text-slate-400">
                    <thead class="text-xs text-slate-500 uppercase bg-background-dark border-b border-border-dark">
                        <tr>
                            <th scope="col" class="px-6 py-3">Name</th>
                            <th scope="col" class="px-6 py-3">Investment Range</th>
                            <th scope="col" class="px-6 py-3">ROI / Duration</th>
                            <th scope="col" class="px-6 py-3">Trades</th>
                            <th scope="col" class="px-6 py-3 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($plans as $plan)
                            <tr class="border-b border-border-dark hover:bg-surface-accent/50 transition-colors">
                                <td class="px-6 py-4 font-medium text-white">
                                    {{ $plan->name }}
                                    <div class="text-xs text-slate-500 truncate max-w-xs">{{ $plan->description }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="text-slate-200">${{ number_format($plan->min_amount) }}</span>
                                    <span class="text-slate-600 mx-1">-</span>
                                    <span class="text-slate-200">${{ number_format($plan->max_amount) }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="font-bold text-success">{{ $plan->roi_per_trade }}% <span
                                            class="text-xs font-normal text-slate-500">per trade</span></div>
                                    <div class="text-xs text-slate-500">{{ $plan->duration_days }} days</div>
                                </td>
                                <td class="px-6 py-4">
                                    {{ $plan->trades_count }}
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <button wire:click="edit({{ $plan->id }})"
                                            class="p-2 text-slate-400 hover:text-primary transition-colors rounded-lg hover:bg-primary/10">
                                            <span class="material-symbols-outlined text-[20px]">edit</span>
                                        </button>
                                        <button wire:confirm="Are you sure you want to delete this plan?"
                                            wire:click="delete({{ $plan->id }})"
                                            class="p-2 text-slate-400 hover:text-danger transition-colors rounded-lg hover:bg-danger/10">
                                            <span class="material-symbols-outlined text-[20px]">delete</span>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-4 text-center text-slate-500">No bot plans found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>