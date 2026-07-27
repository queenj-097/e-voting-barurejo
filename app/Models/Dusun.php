<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Dusun extends Model
{
    protected $fillable = [
        'name',
    ];

    public function voters(): HasMany
    {
        return $this->hasMany(Voter::class);
    }

    public function candidates(): BelongsToMany
    {
        return $this->belongsToMany(
            Candidate::class,
            'candidate_dusun'
        );
    }
}