<?php

namespace App\Livewire\Pages\Admin;

use App\Models\BotPlan;
use Livewire\Component;

class BotPlans extends Component
{
    public $plans;
    public $name, $description, $min_amount, $max_amount, $roi_per_trade, $duration_days, $trades_count;
    public $is_editing = false;
    public $editing_plan_id;
    public $show_form = false;

    protected $rules = [
        'name' => 'required|string|max:255',
        'description' => 'nullable|string',
        'min_amount' => 'required|numeric|min:0',
        'max_amount' => 'required|numeric|gt:min_amount',
        'roi_per_trade' => 'required|numeric|min:0',
        'duration_days' => 'required|integer|min:1',
        'trades_count' => 'required|integer|min:1',
    ];

    public function mount()
    {
        $this->loadPlans();
    }

    public function loadPlans()
    {
        $this->plans = BotPlan::all();
    }

    public function toggleForm()
    {
        $this->show_form = !$this->show_form;
        $this->resetForm();
    }

    public function resetForm()
    {
        $this->reset(['name', 'description', 'min_amount', 'max_amount', 'roi_per_trade', 'duration_days', 'trades_count', 'is_editing', 'editing_plan_id']);
    }

    public function edit($id)
    {
        $plan = BotPlan::findOrFail($id);
        $this->editing_plan_id = $id;
        $this->name = $plan->name;
        $this->description = $plan->description;
        $this->min_amount = $plan->min_amount;
        $this->max_amount = $plan->max_amount;
        $this->roi_per_trade = $plan->roi_per_trade;
        $this->duration_days = $plan->duration_days;
        $this->trades_count = $plan->trades_count;

        $this->is_editing = true;
        $this->show_form = true;
    }

    public function store()
    {
        $this->validate();

        BotPlan::create([
            'name' => $this->name,
            'description' => $this->description,
            'min_amount' => $this->min_amount,
            'max_amount' => $this->max_amount,
            'roi_per_trade' => $this->roi_per_trade,
            'duration_days' => $this->duration_days,
            'trades_count' => $this->trades_count,
        ]);

        session()->flash('message', 'Bot Plan created successfully.');
        $this->toggleForm();
        $this->loadPlans();
    }

    public function update()
    {
        $this->validate();

        $plan = BotPlan::findOrFail($this->editing_plan_id);
        $plan->update([
            'name' => $this->name,
            'description' => $this->description,
            'min_amount' => $this->min_amount,
            'max_amount' => $this->max_amount,
            'roi_per_trade' => $this->roi_per_trade,
            'duration_days' => $this->duration_days,
            'trades_count' => $this->trades_count,
        ]);

        session()->flash('message', 'Bot Plan updated successfully.');
        $this->toggleForm();
        $this->loadPlans();
    }

    public function delete($id)
    {
        BotPlan::findOrFail($id)->delete();
        session()->flash('message', 'Bot Plan deleted successfully.');
        $this->loadPlans();
    }

    public function render()
    {
        return view('livewire.pages.admin.bot-plans')->layout('layouts.app');
    }
}
