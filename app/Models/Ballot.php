<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Ballot extends Model
{
    protected $fillable = [
        'candidate_id',
        'token',
        'is_counted',
        'counted_at',
    ];

    protected function casts(): array
    {
        return [
            'is_counted' => 'boolean',
            'counted_at' => 'datetime',
        ];
    }

    public function candidate(): BelongsTo
    {
        return $this->belongsTo(Candidate::class);
    }
}