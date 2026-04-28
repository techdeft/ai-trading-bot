<?php

use Livewire\Volt\Component;
use App\Models\BotPlan;
use Livewire\Attributes\Layout;
use Livewire\WithPagination;

new #[Layout('layouts.app')] class extends Component {
    use WithPagination;

    public $name, $description, $min_amount, $max_amount, $trades_count, $roi_per_trade, $duration_days;
    public $planId;
    public $isEditing = false;
    public $showModal = false;

    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'min_amount' => 'required|numeric|min:0',
            'max_amount' => 'nullable|numeric|gt:min_amount',
            'trades_count' => 'required|integer|min:1',
            'roi_per_trade' => 'required|numeric|min:0.01',
            'duration_days' => 'required|integer|min:1',
        ];
    }

    public function with()
    {
        return [
            'plans' => BotPlan::paginate(10),
        ];
    }

    public function create()
    {
        $this->reset(['name', 'description', 'min_amount', 'max_amount', 'trades_count', 'roi_per_trade', 'duration_days', 'planId', 'isEditing']);
        $this->showModal = true;
    }

    public function edit(BotPlan $plan)
    {
        $this->planId = $plan->id;
        $this->name = $plan->name;
        $this->description = $plan->description;
        $this->min_amount = $plan->min_amount;
        $this->max_amount = $plan->max_amount;
        $this->trades_count = $plan->trades_count;
        $this->roi_per_trade = $plan->roi_per_trade;
        $this->duration_days = $plan->duration_days;
        $this->isEditing = true;
        $this->showModal = true;
    }

    public function save()
    {
        $validated = $this->validate();

        if ($this->isEditing) {
            $plan = BotPlan::find($this->planId);
            $plan->update($validated);
            session()->flash('message', 'Bot Plan updated successfully.');
        } else {
            BotPlan::create($validated);
            session()->flash('message', 'Bot Plan created successfully.');
        }

        $this->showModal = false;
        $this->reset(['name', 'description', 'min_amount', 'max_amount', 'trades_count', 'roi_per_trade', 'duration_days', 'planId', 'isEditing']);
    }

    public function delete($id)
    {
        BotPlan::find($id)->delete();
        session()->flash('message', 'Bot Plan deleted successfully.');
    }
    
    public function closeModal()
    {
        $this->showModal = false;
    }
}; ?>

<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-surface overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-slate-900 dark:text-white border-b border-slate-200 dark:border-slate-700 flex justify-between items-center">
                <h2 class="text-2xl font-semibold">Bot Plans</h2>
                <button wire:click="create" class="bg-primary hover:bg-primary/90 text-white font-bold py-2 px-4 rounded">
                    Create New Plan
                </button>
            </div>

            <div class="p-6">
                @if (session()->has('message'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                        <strong class="font-bold">Success!</strong>
                        <span class="block sm:inline">{{ session('message') }}</span>
                    </div>
                @endif

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-200 dark:divide-slate-700">
                        <thead class="bg-slate-50 dark:bg-background-dark/50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Name</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Min - Max</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Trades</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">ROI / Trade</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Duration</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-surface divide-y divide-slate-200 dark:divide-slate-700">
                            @foreach($plans as $plan)
                                <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-slate-900 dark:text-white">{{ $plan->name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500 dark:text-slate-400">
                                        ${{ number_format($plan->min_amount) }} - {{ $plan->max_amount ? '$' . number_format($plan->max_amount) : 'Unlimited' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500 dark:text-slate-400">{{ $plan->trades_count }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-success font-bold">{{ $plan->roi_per_trade }}%</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500 dark:text-slate-400">{{ $plan->duration_days }} Days</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <button wire:click="edit({{ $plan->id }})" class="text-primary hover:text-primary/80 mr-3">Edit</button>
                                        <button wire:click="delete({{ $plan->id }})" wire:confirm="Are you sure you want to delete this plan?" class="text-danger hover:text-danger/80">Delete</button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">
                    {{ $plans->links() }}
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    @if($showModal)
        <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" wire:click="closeModal"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                <div class="inline-block align-bottom bg-white dark:bg-surface rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <div class="bg-white dark:bg-surface px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <h3 class="text-lg leading-6 font-medium text-slate-900 dark:text-white" id="modal-title">
                            {{ $isEditing ? 'Edit Plan' : 'Create New Plan' }}
                        </h3>
                        <div class="mt-4 space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300">Name</label>
                                <input type="text" wire:model="name" class="mt-1 block w-full rounded-md border-slate-300 dark:border-slate-600 bg-white dark:bg-background-dark text-slate-900 dark:text-white shadow-sm focus:border-primary focus:ring-primary">
                                @error('name') <span class="text-danger text-xs">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300">Description</label>
                                <textarea wire:model="description" class="mt-1 block w-full rounded-md border-slate-300 dark:border-slate-600 bg-white dark:bg-background-dark text-slate-900 dark:text-white shadow-sm focus:border-primary focus:ring-primary"></textarea>
                                @error('description') <span class="text-danger text-xs">{{ $message }}</span> @enderror
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300">Min Amount ($)</label>
                                    <input type="number" wire:model="min_amount" class="mt-1 block w-full rounded-md border-slate-300 dark:border-slate-600 bg-white dark:bg-background-dark text-slate-900 dark:text-white shadow-sm focus:border-primary focus:ring-primary">
                                    @error('min_amount') <span class="text-danger text-xs">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300">Max Amount ($)</label>
                                    <input type="number" wire:model="max_amount" placeholder="Leave empty for unlimited" class="mt-1 block w-full rounded-md border-slate-300 dark:border-slate-600 bg-white dark:bg-background-dark text-slate-900 dark:text-white shadow-sm focus:border-primary focus:ring-primary">
                                    @error('max_amount') <span class="text-danger text-xs">{{ $message }}</span> @enderror
                                </div>
                            </div>
                            <div class="grid grid-cols-3 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300">Trades</label>
                                    <input type="number" wire:model="trades_count" class="mt-1 block w-full rounded-md border-slate-300 dark:border-slate-600 bg-white dark:bg-background-dark text-slate-900 dark:text-white shadow-sm focus:border-primary focus:ring-primary">
                                    @error('trades_count') <span class="text-danger text-xs">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300">ROI / Trade (%)</label>
                                    <input type="number" step="0.01" wire:model="roi_per_trade" class="mt-1 block w-full rounded-md border-slate-300 dark:border-slate-600 bg-white dark:bg-background-dark text-slate-900 dark:text-white shadow-sm focus:border-primary focus:ring-primary">
                                    @error('roi_per_trade') <span class="text-danger text-xs">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300">Days</label>
                                    <input type="number" wire:model="duration_days" class="mt-1 block w-full rounded-md border-slate-300 dark:border-slate-600 bg-white dark:bg-background-dark text-slate-900 dark:text-white shadow-sm focus:border-primary focus:ring-primary">
                                    @error('duration_days') <span class="text-danger text-xs">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 dark:bg-background-dark/50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button wire:click="save" type="button" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-primary text-base font-medium text-white hover:bg-primary/90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary sm:ml-3 sm:w-auto sm:text-sm">
                            {{ $isEditing ? 'Update Plan' : 'Create Plan' }}
                        </button>
                        <button wire:click="closeModal" type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 dark:border-slate-600 shadow-sm px-4 py-2 bg-white dark:bg-surface text-base font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                            Cancel
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
