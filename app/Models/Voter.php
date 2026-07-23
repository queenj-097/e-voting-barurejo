<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Voter extends Model
{
    protected $fillable = [
        'dpt_number',
        'nik',
        'name',
        'address',
        'has_voted',
        'voted_at',
    ];

    protected function casts(): array
    {
        return [
            'has_voted' => 'boolean',
            'voted_at' => 'datetime',
        ];
    }
    public function votingAccesses(): HasMany
    {
        return $this->hasMany(VotingAccess::class);
    }
}