<?php

namespace Database\Seeders;

use App\Models\SubDistrict;
use App\Models\Village;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class VillageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $sub_district = SubDistrict::query()->limit(1)->first();
        Village::create([
            'village_id' => 'V' . Str::random(4),
            'sub_district_id' => $sub_district->sub_district_id,
            'village_name' => 'kalibeber'
        ]);
    }
}
