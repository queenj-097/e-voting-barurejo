<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Voter extends Model
{
    protected $fillable = [
        'voter_code',
        'nik',
        'name',
        'gender',
        'dusun_id',
        'rw',
        'rt',
        'election_group_id',
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

    public function dusun(): BelongsTo
    {
        return $this->belongsTo(Dusun::class);
    }

    public function electionGroup(): BelongsTo
    {
        return $this->belongsTo(ElectionGroup::class);
    }

    public function votingAccesses(): HasMany
    {
        return $this->hasMany(VotingAccess::class);
    }
}