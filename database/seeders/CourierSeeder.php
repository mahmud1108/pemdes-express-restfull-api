<?php

namespace Database\Seeders;

use App\Models\Courier;
use Illuminate\Database\Seeder;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;

class CourierSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Courier::create([
            'courier_id' => Str::random(4),
            'courier_name' => 'test',
            'courier_phone' => 'test',
            'address' => 'test',
            'photo' => UploadedFile::fake()->create('asdf.jpg', 1240),
            'email' => 'test',
            'password' => 'test',
            'token' => 'kurir',
        ]);
    }
}
