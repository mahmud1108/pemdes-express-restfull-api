<?php

namespace Tests\Feature;

use App\Http\Resources\CourierResource;
use App\Models\Admin;
use App\Models\Courier;
use Database\Seeders\AdminSeeder;
use Database\Seeders\CourierSeeder;
use Database\Seeders\DatabaseSeeder;
use GuzzleHttp\Psr7\Query;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Queue\Jobs\DatabaseJob;
use Tests\TestCase;

class CourierTest extends TestCase
{
    /**
     * A basic feature test example.
     */

    public function testCreateSuccess()
    {
        $this->seed(DatabaseSeeder::class);
        $this->post('/api/admin/courier', [
            'courier_name' => 'required',
            'courier_phone' => 'required',
            'address' => 'required',
            'photo' => UploadedFile::fake()->create('file.jpg', 1024),
            'email' => 'email@gmail.com',
            'password' => 'required'
        ], [
            'Authorization' => 'admin'
        ])->assertStatus(201)
            ->json();
    }

    public function testCreateErrorValidation()
    {
        $this->seed(DatabaseSeeder::class);
        $this->post(
            '/api/admin/courier',
            [
                'courier_name' => 'required',
                'courier_phone' => 'required',
                'address' => 'required',
                'photo' => UploadedFile::fake()->create('file.jpg', 1024),
                'email' => 'email',
                'password' => 'required'
            ],
            [
                'Authorization' => 'admin'
            ]
        )->assertStatus(400)
            ->assertJson([
                'errors' => [
                    'email' => [
                        'The email field must be a valid email address.'
                    ]
                ]
            ]);
    }

    public function testDeleteCourier()
    {
        $this->seed(DatabaseSeeder::class);
        $courier = Courier::query()->limit(1)->first();

        $this->delete('/api/admin/courier/' . $courier->courier_id, [], [
            'Authorization' => 'admin'
        ])->assertStatus(200)
            ->assertJson([
                'data' => true
            ]);
    }

    public function testDeleteNotFound()
    {
        $this->seed(DatabaseSeeder::class);
        $courier = Courier::query()->limit(1)->first();

        $this->delete('/api/admin/courier/' . $courier->courier_id . 'asdf', headers: [
            'Authorization' => 'admin'
        ])->assertStatus(404)
            ->assertJson([
                'errors' => [
                    'message' => [
                        'Not found'
                    ]
                ]
            ]);
    }

    public function testCurierSearch()
    {
        $this->seed(DatabaseSeeder::class);
        $courier = Courier::query()->limit(1)->first();

        $res = $this->get('/api/admin/courier/' . $courier->courier_id, headers: [
            'Authorization' => 'admin'
        ])->assertStatus(200);
    }

    public function testCourierUpdateNameSuccess()
    {
        $this->seed(DatabaseSeeder::class);
        $courier = Courier::query()->limit(1)->first();

        $this->patch(
            '/api/admin/courier/' . $courier->courier_id,
            [
                'courier_name' => 'asdfasdfasdf'
            ],
            [
                'Authorization' => 'admin'
            ]
        )->assertStatus(200);
        $new = Courier::query()->limit(1)->first();

        self::assertNotEquals($courier->courier_name, $new->courier_name);
    }

    public function testCourierUpdatePhotoSuccess()
    {
        $this->seed(DatabaseSeeder::class);
        $courier = Courier::query()->limit(1)->first();

        $this->patch(
            '/api/admin/courier/' . $courier->courier_id,
            [
                'photo' => UploadedFile::fake()->create('asdfasdf.jpg', 1234)
            ],
            [
                'Authorization' => 'admin'
            ]
        )->assertStatus(200);
        $new = Courier::query()->limit(1)->first();

        self::assertNotEquals($courier->photo, $new->photo);
    }

    public function testUpdatePhotoErrorValidation()
    {
        $this->seed(DatabaseSeeder::class);
        $courier = Courier::query()->limit(1)->first();

        $this->patch(
            'api/admin/courier/' . $courier->courier_id,
            [
                'photo' => UploadedFile::fake()->create('asdf.pdf', 123)
            ],
            [
                'Authorization' => 'admin'
            ]
        )->assertStatus(400)
            ->assertJson([
                'errors' => [
                    'photo' => [
                        'The photo field must be a file of type: jpg, png, jpeg.'
                    ]
                ]
            ]);
    }
}
