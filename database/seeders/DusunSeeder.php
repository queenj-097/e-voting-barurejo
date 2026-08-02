<?php

namespace Database\Seeders;

use App\Models\Dusun;
use Illuminate\Database\Seeder;

class DusunSeeder extends Seeder
{
    public function run(): void
    {
        $dusuns = [
            [
                'name' => 'KRAJAN',
                'code' => 'KRJ',
            ],
            [
                'name' => 'SENEPOLOR',
                'code' => 'SPL',
            ],
            [
                'name' => 'SENEPOSARI',
                'code' => 'SPS',
            ],
            [
                'name' => 'SUMBERURIP',
                'code' => 'SUR',
            ],
            [
                'name' => 'SUMBERMANGGIS',
                'code' => 'SMG',
            ],
        ];

        foreach ($dusuns as $dusun) {
            Dusun::updateOrCreate(
                ['code' => $dusun['code']],
                $dusun
            );
        }
    }
}