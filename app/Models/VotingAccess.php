<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VotingAccess extends Model
{
    protected $fillable = [
        'voter_id',
        'code',
        'status',
        'expires_at',
        'used_at',
    ];

    protected function casts(): array
    {
        return [
            'expires_at' => 'datetime',
            'used_at' => 'datetime',
        ];
    }

    public function voter(): BelongsTo
    {
        return $this->belongsTo(Voter::class);
    }

    public function isUsable(): bool
    {
        return $this->status === 'waiting'
            && $this->used_at === null
            && $this->expires_at->isFuture();
    }
}