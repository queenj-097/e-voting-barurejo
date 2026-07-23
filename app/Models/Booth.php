<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Booth extends Model
{
    protected $fillable = [
        'name',
        'status',
        'current_voter_id',
        'assigned_at',
        'voting_started_at',
    ];

    protected function casts(): array
    {
        return [
            'assigned_at' => 'datetime',
            'voting_started_at' => 'datetime',
        ];
    }

    public function currentVoter(): BelongsTo
    {
        return $this->belongsTo(
            Voter::class,
            'current_voter_id'
        );
    }

    public function isAvailable(): bool
    {
        return $this->status === 'idle'
            && $this->current_voter_id === null;
    }
}