<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserBot extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'bot_plan_id',
        'amount',
        'trades_completed',
        'total_profit',
        'start_date',
        'end_date',
        'status',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'start_date' => 'datetime',
            'end_date' => 'datetime',
            'amount' => 'decimal:2',
            'trades_completed' => 'integer',
            'total_profit' => 'decimal:2',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function botPlan()
    {
        return $this->belongsTo(BotPlan::class);
    }

    public function tradeLogs()
    {
        return $this->hasMany(BotTradeLog::class);
    }
}
