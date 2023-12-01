<?php

namespace Tests\Feature;

use App\Models\Courier;
use App\Models\Shipment;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Http\UploadedFile;
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
            'password' => 'required',
            'password_confirmation' => 'required'
        ], [
            'Authorization' => 'admin'
        ])->assertStatus(201)
            ->json();
    }

    public function testRegisterSuccess()
    {
        $this->post('/api/courier/register', [
            'courier_name' => 'required',
            'courier_phone' => 'required',
            'address' => 'required',
            'photo' => UploadedFile::fake()->create('file.jpg', 1024),
            'email' => 'email@gmail.com',
            'password' => 'required',
            'password_confirmation' => 'required'
        ], [
            'Authorization' => 'admin'
        ])->assertStatus(201)
            ->json();
    }

    public function testCreateErrorUnique()
    {
        $this->seed(DatabaseSeeder::class);
        $this->post(
            '/api/admin/courier',
            [
                'courier_name' => 'required',
                'courier_phone' => 'required',
                'address' => 'required',
                'photo' => UploadedFile::fake()->create('file.jpg', 1024),
                'email' => 'test@gmail.com',
                'password' => 'required'
            ],
            [
                'Authorization' => 'admin'
            ]
        )->assertStatus(400)
            ->assertJson([
                'errors' => [
                    'email' => [
                        'The email has already been taken.'
                    ]
                ]
            ]);
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

    public function testCurierDetail()
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

    public function testUpdateEmailErrorUnique()
    {
        $this->seed(DatabaseSeeder::class);
        $courier = Courier::query()->limit(1)->first();

        $this->patch(
            'api/admin/courier/' . $courier->courier_id,
            [
                'email' => 'test@gmail.com'
            ],
            [
                'Authorization' => 'admin'
            ]
        )->assertStatus(400)
            ->assertJson([
                'errors' => [
                    'email' => [
                        'The email has already been taken.'
                    ]
                ]
            ]);
    }

    public function testUpdatePhoneErrorUnique()
    {
        $this->seed(DatabaseSeeder::class);
        $courier = Courier::query()->limit(1)->first();

        $this->patch(
            'api/admin/courier/' . $courier->courier_id,
            [
                'courier_phone' => 'test'
            ],
            [
                'Authorization' => 'admin'
            ]
        )->assertStatus(400)
            ->assertJson([
                'errors' => [
                    'courier_phone' => [
                        'The courier phone has already been taken.'
                    ]
                ]
            ]);
    }

    public function testCourierShipementList()
    {
        $this->seed(DatabaseSeeder::class);
        $courier = Courier::query()->limit(1)->first();

        $result = $this->get('/api/admin/courier/list/' . $courier->courier_id, [
            'Authorization' => 'admin'
        ])->assertStatus(200)
            ->json();

        self::assertEquals(10, $result['meta']['total']);
        self::assertEquals(10, count($result['data']));
    }

    public function testSearchSuccess()
    {
        $this->seed(DatabaseSeeder::class);

        $result = $this->get('/api/admin/courier?name=test', headers: [
            'Authorization' => 'admin'
        ])->assertStatus(200)->json();

        self::assertEquals(3, count($result['data']));
    }

    public function testCourierLoginSuccess()
    {
        $this->seed(DatabaseSeeder::class);
        $courier = Courier::where('email', 'courier@gmail.com')->first();

        $this->post('/api/courier', [
            'email' => 'courier@gmail.com',
            'password' => 'courier'
        ])->assertStatus(200)
            ->assertJson([
                'data' => [
                    'courier_name' => 'test',
                    'courier_phone' => 'test',
                    'address' => 'test',
                    'photo' => $courier->photo,
                    'email' => 'courier@gmail.com'
                ]
            ]);
    }

    public function testCourierLoginWrongPassword()
    {
        $this->seed(DatabaseSeeder::class);

        $this->post('/api/courier', [
            'email' => 'asdf@gmail.com',
            'password' => 'asdfasdf'
        ])->assertStatus(400)
            ->assertJson([
                'errors' => [
                    'message' => [
                        'email or password wrong'
                    ]
                ]
            ]);
    }

    public function testCourierUpdateAuthNameSuccess()
    {
        $this->seed(DatabaseSeeder::class);
        $courier = Courier::where('email', 'courier@gmail.com')->first();

        $this->patch(
            '/api/courier/',
            [
                'courier_name' => 'asdfasdfasdf'
            ],
            [
                'Authorization' => 'kurir'
            ]
        )->assertStatus(200);
        $new = Courier::where('email', 'courier@gmail.com')->first();

        self::assertNotEquals($courier->courier_name, $new->courier_name);
    }

    public function testCourierUpdateAuthPhotoSuccess()
    {
        $this->seed(DatabaseSeeder::class);
        $courier = Courier::where('email', 'courier@gmail.com')->first();

        $this->patch(
            '/api/courier/',
            [
                'photo' => UploadedFile::fake()->create('asdfasdf.jpg')
            ],
            [
                'Authorization' => 'kurir'
            ]
        )->assertStatus(200);
        $new = Courier::where('email', 'courier@gmail.com')->first();

        self::assertNotEquals($courier->photo, $new->photo);
    }

    public function testGetAuthShipment()
    {
        $this->seed(DatabaseSeeder::class);

        $result = $this->get('/api/courier/shipment', headers: [
            'Authorization' => 'kurir'
        ])->assertStatus(200)->json();

        self::assertEquals(10, $result['meta']['total']);
        self::assertEquals(10, count($result['data']));
    }

    public function testUpdateAuthShipment()
    {
        $this->seed(DatabaseSeeder::class);
        $shipment = Shipment::where('courier_id', 1)->first();

        $this->post('/api/courier/shipment/' . $shipment->no_receipts, [
            'delivery_status' => 'diterima',
            'acknowledgment' => UploadedFile::fake()->create('asdfasdf.jpg')
        ], [
            'Authorization' => 'kurir'
        ])->assertStatus(200);
    }

    public function testUpdateAuthShipmentNotFound()
    {
        $this->seed(DatabaseSeeder::class);

        $this->post('/api/courier/shipment/0', [
            'delivery_status' => 'diterima',
            'acknowledgment' => UploadedFile::fake()->create('asdfasdf.jpg')
        ], [
            'Authorization' => 'kurir'
        ])->assertStatus(404)
            ->assertJson([
                'errors' => [
                    'message' => [
                        'Not found'
                    ]
                ]
            ]);
    }

    public function testUpdateAuthShipmentUnauthorizedNotFound()
    {
        $this->seed(DatabaseSeeder::class);
        $shipment  = Shipment::where('courier_id', '!=', 1)->first();

        $this->post('/api/courier/shipment/' . $shipment->no_receipts, [
            'delivery_status' => 'diterima',
            'acknowledgment' => UploadedFile::fake()->create('asdfasdf.jpg')
        ], [
            'Authorization' => 'kurir'
        ])->assertStatus(404)
            ->assertJson([
                'errors' => [
                    'message' => [
                        'Not found'
                    ]
                ]
            ]);
    }

    public function testUpdateAuthShipmentUnauthorized()
    {
        $this->seed(DatabaseSeeder::class);
        $shipment = Shipment::where('courier_id', 1)->first();

        $this->post('/api/courier/shipment/' . $shipment->no_receipts, [
            'delivery_status' => 'diterima',
            'acknowledgment' => UploadedFile::fake()->create('asdfasdf.jpg')
        ], [
            'Authorization' => 'kurir11'
        ])->assertStatus(401)
            ->assertJson([
                'errors' => [
                    'message' => [
                        'Unauthorized'
                    ]
                ]
            ]);
    }

    public function testLogoutCourier()
    {
        $this->seed(DatabaseSeeder::class);
        $this->delete('/api/courier/logout', headers: [
            'Authorization' => 'kurir'
        ])->assertStatus(200)
            ->assertJson([
                'data' => true
            ]);
    }
}
