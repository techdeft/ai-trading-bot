<?php

use Livewire\Volt\Component;
use App\Models\BotPlan;
use App\Models\UserBot;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Auth;

new #[Layout('layouts.app')] class extends Component {
    public $selectedPlan = null;
    public $investmentAmount = 0;
    public $showSubscribeModal = false;

    public function with()
    {
        return [
            'plans' => BotPlan::all(),
            'activeBots' => Auth::user()->userBots()->with('botPlan')->latest()->get(),
        ];
    }

    public function selectPlan(BotPlan $plan)
    {
        $this->selectedPlan = $plan;
        $this->investmentAmount = $plan->min_amount;
        $this->showSubscribeModal = true;
    }

    public function subscribe()
    {
        $this->validate([
            'investmentAmount' => [
                'required',
                'numeric',
                'min:' . ($this->selectedPlan->min_amount ?? 0),
                function ($attribute, $value, $fail) {
                    if ($this->selectedPlan->max_amount && $value > $this->selectedPlan->max_amount) {
                        $fail("The $attribute must not be greater than " . $this->selectedPlan->max_amount);
                    }
                },
            ],
        ]);

        if (Auth::user()->balance < $this->investmentAmount) {
            $this->addError('investmentAmount', 'Insufficient Trading Balance.');
            return;
        }

        // Deduct balance
        Auth::user()->decrement('balance', $this->investmentAmount);

        // Create User Subscription
        UserBot::create([
            'user_id' => Auth::id(),
            'bot_plan_id' => $this->selectedPlan->id,
            'amount' => $this->investmentAmount,
            'start_date' => now(),
            'end_date' => now()->addDays($this->selectedPlan->duration_days),
            'status' => 'active',
        ]);

        session()->flash('message', 'Successfully subscribed to ' . $this->selectedPlan->name);
        $this->showSubscribeModal = false;
        $this->reset(['selectedPlan', 'investmentAmount']);
    }

    public function closeModal()
    {
        $this->showSubscribeModal = false;
        $this->reset(['selectedPlan', 'investmentAmount']);
    }
}; ?>

<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">

        <!-- Active Bots Section -->
        @if($activeBots->isNotEmpty())
            <div
                class="bg-white dark:bg-surface overflow-hidden shadow-sm sm:rounded-xl border border-slate-200 dark:border-slate-700 p-6">
                <h2 class="text-xl font-bold text-slate-900 dark:text-white mb-4">Your Active Bots</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($activeBots as $bot)
                        <div
                            class="bg-slate-50 dark:bg-background-dark/50 rounded-lg p-4 border border-slate-200 dark:border-slate-700">
                            <div class="flex justify-between items-start mb-2">
                                <h3 class="font-bold text-slate-900 dark:text-white">{{ $bot->botPlan->name }}</h3>
                                <span class="px-2 py-1 text-xs font-bold rounded-full bg-success/10 text-success uppercase">
                                    {{ $bot->status }}
                                </span>
                            </div>
                            <div class="space-y-2 text-sm">
                                <div class="flex justify-between">
                                    <span class="text-slate-500 dark:text-slate-400">Invested:</span>
                                    <span
                                        class="font-bold text-slate-900 dark:text-white">${{ number_format($bot->amount, 2) }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-slate-500 dark:text-slate-400">Profit:</span>
                                    <span class="font-bold text-success">+${{ number_format($bot->total_profit, 2) }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-slate-500 dark:text-slate-400">Trades:</span>
                                    <span class="font-bold text-slate-900 dark:text-white">{{ $bot->trades_completed }} /
                                        {{ $bot->botPlan->trades_count }}</span>
                                </div>
                                <div class="w-full bg-slate-200 dark:bg-slate-700 rounded-full h-2.5 mt-2">
                                    <div class="bg-primary h-2.5 rounded-full"
                                        style="width: {{ ($bot->trades_completed / $bot->botPlan->trades_count) * 100 }}%">
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Available Plans Section -->
        <div>
            <h2 class="text-2xl font-bold text-slate-900 dark:text-white mb-6">Available AI Trading Plans</h2>

            @if (session()->has('message'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4"
                    role="alert">
                    <strong class="font-bold">Success!</strong>
                    <span class="block sm:inline">{{ session('message') }}</span>
                </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                @foreach($plans as $plan)
                    <div
                        class="bg-white dark:bg-surface overflow-hidden shadow-lg hover:shadow-xl transition-shadow sm:rounded-2xl border border-slate-200 dark:border-slate-700 relative flex flex-col">
                        <div class="p-6 flex-1">
                            <h3 class="text-xl font-bold text-slate-900 dark:text-white text-center mb-2">{{ $plan->name }}
                            </h3>
                            <p class="text-slate-500 dark:text-slate-400 text-center text-sm mb-6">{{ $plan->description }}
                            </p>

                            <div class="space-y-4">
                                <div
                                    class="flex justify-between items-center border-b border-slate-100 dark:border-slate-800 pb-2">
                                    <span class="text-slate-500 dark:text-slate-400">ROI / Trade</span>
                                    <span class="font-bold text-success">{{ $plan->roi_per_trade }}%</span>
                                </div>
                                <div
                                    class="flex justify-between items-center border-b border-slate-100 dark:border-slate-800 pb-2">
                                    <span class="text-slate-500 dark:text-slate-400">Total Trades</span>
                                    <span class="font-bold text-slate-900 dark:text-white">{{ $plan->trades_count }}</span>
                                </div>
                                <div
                                    class="flex justify-between items-center border-b border-slate-100 dark:border-slate-800 pb-2">
                                    <span class="text-slate-500 dark:text-slate-400">Duration</span>
                                    <span class="font-bold text-slate-900 dark:text-white">{{ $plan->duration_days }}
                                        Days</span>
                                </div>
                                <div
                                    class="flex justify-between items-center border-b border-slate-100 dark:border-slate-800 pb-2">
                                    <span class="text-slate-500 dark:text-slate-400">Min Investment</span>
                                    <span
                                        class="font-bold text-slate-900 dark:text-white">${{ number_format($plan->min_amount) }}</span>
                                </div>
                            </div>
                        </div>
                        <div
                            class="p-6 bg-slate-50 dark:bg-background-dark/50 border-t border-slate-200 dark:border-slate-700">
                            <button wire:click="selectPlan({{ $plan->id }})"
                                class="w-full py-3 px-4 bg-primary hover:bg-primary/90 text-white font-bold rounded-xl transition-all shadow-lg hover:shadow-primary/25">
                                Start Investing
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Subscribe Modal -->
    @if($showSubscribeModal && $selectedPlan)
        <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"
                    wire:click="closeModal"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                <div
                    class="inline-block align-bottom bg-white dark:bg-surface rounded-xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <div class="bg-white dark:bg-surface px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <h3 class="text-lg leading-6 font-bold text-slate-900 dark:text-white mb-4" id="modal-title">
                            Subscribe to {{ $selectedPlan->name }}
                        </h3>
                        <div class="space-y-4">
                            <div class="bg-indigo-50 dark:bg-primary/10 p-4 rounded-lg">
                                <p class="text-sm text-slate-700 dark:text-slate-300">
                                    Available Trading Balance: <span
                                        class="font-bold text-primary">${{ number_format(Auth::user()->balance ?? 0, 2) }}</span>
                                </p>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300">Investment
                                    Amount ($)</label>
                                <input type="number" wire:model="investmentAmount"
                                    class="mt-1 block w-full rounded-lg border-slate-300 dark:border-slate-600 bg-white dark:bg-background-dark text-slate-900 dark:text-white shadow-sm focus:border-primary focus:ring-primary text-lg font-bold">
                                @error('investmentAmount') <span class="text-danger text-xs">{{ $message }}</span> @enderror
                                <p class="text-xs text-slate-500 mt-1">
                                    Min: ${{ number_format($selectedPlan->min_amount) }} - Max:
                                    {{ $selectedPlan->max_amount ? '$' . number_format($selectedPlan->max_amount) : 'Unlimited' }}
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 dark:bg-background-dark/50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button wire:click="subscribe" type="button"
                            class="w-full inline-flex justify-center rounded-lg border border-transparent shadow-sm px-4 py-2 bg-primary text-base font-bold text-white hover:bg-primary/90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary sm:ml-3 sm:w-auto sm:text-sm">
                            Confirm Investment
                        </button>
                        <button wire:click="closeModal" type="button"
                            class="mt-3 w-full inline-flex justify-center rounded-lg border border-gray-300 dark:border-slate-600 shadow-sm px-4 py-2 bg-white dark:bg-surface text-base font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                            Cancel
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>