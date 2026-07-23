<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ElectionSetting extends Model
{
    protected $fillable = [
        'title',
        'institution',
        'location',
        'election_date',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'election_date' => 'date',
        ];
    }
}