<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BotTradeLog extends Model
{
    protected $fillable = [
        'user_bot_id',
        'profit',
        'roi_percentage',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'user_bot_id' => 'integer',
            'profit' => 'decimal:2',
            'roi_percentage' => 'decimal:2',
        ];
    }

    public function userBot()
    {
        return $this->belongsTo(UserBot::class);
    }
}
