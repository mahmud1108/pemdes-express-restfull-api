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
            'courier_id' => 1,
            'courier_name' => 'test',
            'courier_phone' => 'test',
            'address' => 'test',
            'photo' => UploadedFile::fake()->create('asdf.jpg', 1240),
            'email' => 'test@gmail.com',
            'password' => 'test',
            'token' => 'kurir',
        ]);

        for ($i = 0; $i <= 1; $i++) {
            Courier::create([
                'courier_id' => Str::random(4),
                'courier_name' => 'test',
                'courier_phone' => 'test',
                'address' => 'test',
                'photo' => UploadedFile::fake()->create('asdf.jpg', 1240),
                'email' => 'test@gmail.com',
                'password' => 'test',
                'token' => 'kurir' . $i,
            ]);
        }
    }
}
