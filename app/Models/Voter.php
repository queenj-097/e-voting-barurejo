<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Dusun;

class Voter extends Model
{
    protected $fillable = [
        'dpt_number',
        'nik',
        'name',
        'dusun_id',
        'address',
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

    public function electionGroup(): BelongsTo
    {
        return $this->belongsTo(ElectionGroup::class);
    }

    public function votingAccesses(): HasMany
    {
        return $this->hasMany(VotingAccess::class);
    }

    public function dusun()
    {
        return $this->belongsTo(Dusun::class);
    }
}