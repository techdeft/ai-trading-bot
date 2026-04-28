<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BotPlan extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'min_amount',
        'max_amount',
        'trades_count',
        'roi_per_trade',
        'duration_days',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'min_amount' => 'decimal:2',
            'max_amount' => 'decimal:2',
            'trades_count' => 'integer',
            'roi_per_trade' => 'decimal:2',
            'duration_days' => 'integer',
        ];
    }

    public function userBots()
    {
        return $this->hasMany(UserBot::class);
    }
}
