<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Candidate extends Model
{
    protected $fillable = [
        'number',
        'election_group_id',
        'name',
        'photo',
        'vision',
        'mission',
    ];

    public function electionGroup(): BelongsTo
    {
        return $this->belongsTo(ElectionGroup::class);
    }

    public function ballots(): HasMany
    {
        return $this->hasMany(Ballot::class);
    }

    public function dusuns(): BelongsToMany
    {
        return $this->belongsToMany(
            Dusun::class,
            'candidate_dusun'
        );
    }
}