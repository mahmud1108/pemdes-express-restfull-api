<?php

namespace Database\Seeders;

use App\Models\Bumdes;
use App\Models\Village;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Nette\Utils\Random;

class BumdesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $village = Village::query()->limit(1)->first();

        for ($i = 0; $i < 20; $i++) {
            Bumdes::create([
                'bumdes_id' => 'Bumdes_' . Random::generate(charlist: '0-9'),
                'bumdes_name' => 'desa test',
                'bumdes_phone' => '123123123' . $i,
                'email' => 'test@gmail.com' . $i,
                'password' => 'test',
                'village_id' => $village->village_id,
                'token' => 'bumdes' . $i
            ]);
        }
    }
}
