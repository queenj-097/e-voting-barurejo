<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Ballot;

class Candidate extends Model
{
    protected $fillable = [
        'number',
        'name',
        'photo',
        'vision',
        'mission',
    ];

    public function ballots(): HasMany
    {
        return $this->hasMany(Ballot::class);
    }
}