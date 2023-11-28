<?php

namespace Database\Seeders;

use App\Models\Bumdes;
use App\Models\Village;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class BumdesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $village = Village::query()->limit(1)->first();
        Bumdes::create([
            'bumdes_id' => 'B' . Str::random(4),
            'bumdes_name' => 'desa test',
            'bumdes_phone' => '123123123',
            'email' => 'test',
            'password' => 'test',
            'village_id' => $village->village_id,
            'token' => 'bumdes'
        ]);
    }
}
