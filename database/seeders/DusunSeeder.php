<?php

namespace Database\Seeders;

use App\Models\Dusun;
use Illuminate\Database\Seeder;

class DusunSeeder extends Seeder
{
    public function run(): void
    {
        $dusuns = [
            'Krajan',
            'Senepo Lor',
            'Seneposari',
            'Sumbermanggis',
            'Sumberurip',
        ];

        foreach ($dusuns as $dusun) {
            Dusun::firstOrCreate([
                'name' => $dusun,
            ]);
        }
    }
}