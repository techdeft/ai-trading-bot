<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Wallet extends Model
{
    protected $fillable = [
        'address',
        'network',
        'token',
        'user_id',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function scopeUnassigned($query)
    {
        return $query->whereNull('user_id');
    }

    public function scopeToken($query, $token)
    {
        return $query->where('token', $token);
    }
}
