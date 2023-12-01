<?php

namespace Database\Seeders;

use App\Helper\FileHelper;
use App\Models\Courier;
use Illuminate\Database\Seeder;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
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
            'photo' => FileHelper::instance()->upload(UploadedFile::fake()->create('asdf.jpg', 1240), 'courier'),
            'email' => 'courier@gmail.com',
            'password' => Hash::make('courier'),
            'token' => 'kurir',
        ]);

        for ($i = 0; $i <= 1; $i++) {
            Courier::create([
                'courier_id' => Str::random(4),
                'courier_name' => 'test',
                'courier_phone' => 'test',
                'address' => 'test',
                'photo' => FileHelper::instance()->upload(UploadedFile::fake()->create('asdf.jpg', 1240), 'courier'),
                'email' => 'test@gmail.com',
                'password' => Hash::make('test'),
                'token' => 'kurir' . $i,
            ]);
        }
    }
}
