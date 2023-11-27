<?php

namespace Database\Seeders;

use App\Models\SubDistrict;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;

class SubDistrictSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        SubDistrict::create([
            'sub_district_id' => 'SD' . Str::random(4),
            'sub_district_name' => 'mojotengah'
        ]);
    }
}
