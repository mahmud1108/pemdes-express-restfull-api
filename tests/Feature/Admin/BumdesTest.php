<?php

namespace Tests\Feature\Admin;

use App\Models\Bumdes;
use App\Models\Shipment;
use App\Models\Village;
use Database\Seeders\DatabaseSeeder;
use Database\Seeders\MultiBumdesSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class BumdesTest extends TestCase
{
    /**
     * A basic feature test example.
     */

    public function testCreateBumdesSuccess()
    {
        $this->seed(DatabaseSeeder::class);
        $village = Village::query()->limit(1)->first();

        $this->post('/api/admin/bumdes', [
            'bumdes_name' => 'desa kalitengah',
            'bumdes_phone' => '12398783',
            'email' => 'bumdes@gmail.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            'village_id' => $village->village_id
        ], [
            'Authorization' => 'admin'
        ])->assertStatus(201)
            ->assertJson([
                'data' => [
                    'bumdes_name' => 'desa kalitengah',
                    'bumdes_phone' => '12398783',
                    'email' => 'bumdes@gmail.com',
                    'village_id' => $village->village_id
                ]
            ]);
    }

    public function testCreateBumdesValidationEmail()
    {
        $this->seed(DatabaseSeeder::class);
        $village = Village::query()->limit(1)->first();

        $this->post('/api/admin/bumdes', [
            'bumdes_name' => 'desa kalitengah',
            'bumdes_phone' => '12398783',
            'email' => 'bumdes',
            'password' => 'password',
            'village_id' => $village->village_id
        ], [
            'Authorization' => 'admin'
        ])->assertStatus(400)
            ->assertJson([
                'errors' => [
                    'email' => [
                        'The email field must be a valid email address.'
                    ]
                ]
            ]);
    }

    public function testCreateBumdesValidation()
    {
        $this->seed(DatabaseSeeder::class);
        $village = Village::query()->limit(1)->first();

        $this->post('/api/admin/bumdes', [
            'bumdes_name' => 'desa kalitengah',
            'bumdes_phone' => '1231231231',
            'email' => 'test@gmail.com1',
            'password' => 'password',
            'password_confirmation' => 'password',
            'village_id' => $village->village_id
        ], [
            'Authorization' => 'admin'
        ])->assertStatus(400)
            ->assertJson([
                'errors' => [
                    'email' => [
                        'The email has already been taken.'
                    ],
                    'bumdes_phone' => [
                        'The bumdes phone has already been taken.'
                    ]
                ]
            ]);
    }

    public function testBumdesDetailSuccess()
    {
        $this->seed(DatabaseSeeder::class);
        $bumdes = Bumdes::query()->limit(1)->first();

        $result = $this->get(
            '/api/admin/bumdes/' . $bumdes->bumdes_id,
            [
                'Authorization' => 'admin'
            ]
        )
            ->assertStatus(200)
            ->json();
        self::assertEquals(4, count($result['data']));
        self::assertEquals('desa test', $result['data']['bumdes_name']);
    }

    public function testBumdesDetailNotFound()
    {
        $this->seed(DatabaseSeeder::class);

        $this->get(
            '/api/admin/bumdes/0',
            [
                'Authorization' => 'admin'
            ]
        )
            ->assertStatus(404)
            ->assertJson([
                'errors' => [
                    "message" => [
                        'Not found'
                    ]
                ]
            ]);
    }

    public function testBumdesDetailUnauthorized()
    {
        $this->seed(DatabaseSeeder::class);
        $bumdes = Bumdes::query()->limit(1)->first();

        $this->get(
            '/api/admin/bumdes/' . $bumdes->bumdes_id,
            [
                'Authorization' => 'token salah'
            ]
        )
            ->assertStatus(401)
            ->assertJson([
                'errors' => [
                    "message" => [
                        'Unauthorized'
                    ]
                ]
            ]);
    }

    public function testBumdesDelete()
    {
        $this->seed(DatabaseSeeder::class);
        $bumdes = Bumdes::query()->limit(1)->first();

        $this->delete('/api/admin/bumdes/' . $bumdes->bumdes_id, headers: [
            'Authorization' => 'admin'
        ])->assertStatus(200)
            ->assertJson([
                'data' => true
            ]);
    }

    public function testBumdesDeleteNotFound()
    {
        $this->seed(DatabaseSeeder::class);

        $this->delete('/api/admin/bumdes/0', headers: [
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

    public function testBumdesUpdateSuccess()
    {
        $this->seed(DatabaseSeeder::class);
        $bumdes = Bumdes::query()->limit(1)->first();

        $this->patch(
            '/api/admin/bumdes/' . $bumdes->bumdes_id,
            [
                'password' => 'password',
                'password_confirmation' => 'password',
            ],
            [
                'Authorization' => 'admin'
            ]
        )->assertStatus(200);
        $new = Bumdes::query()->limit(1)->first();
        self::assertNotEquals($new->password, $bumdes->password);
    }

    public function testBumdesGetAll()
    {
        $this->seed(DatabaseSeeder::class);

        $result = $this->get('/api/admin/bumdes', [
            'Authorization' => 'admin'
        ])->assertStatus(200)->json();

        self::assertEquals(10, count($result['data']));
        self::assertEquals(20, $result['meta']['total']);
    }

    public function testSearchByName()
    {
        $this->seed(DatabaseSeeder::class);

        $result = $this->get('/api/admin/bumdes/search?name=test', headers: [
            'Authorization' => 'admin'
        ])->assertStatus(200)
            ->json();

        self::assertEquals(20, $result['meta']['total']);
        self::assertEquals(10, count($result['data']));
    }

    public function testSearchByNameAndPagination()
    {
        $this->seed(DatabaseSeeder::class);

        $result = $this->get('/api/admin/bumdes/search?name=test&per_page=5', headers: [
            'Authorization' => 'admin'
        ])->assertStatus(200)
            ->json();

        self::assertEquals(20, $result['meta']['total']);
        self::assertEquals(5, $result['meta']['per_page']);
        self::assertEquals(5, count($result['data']));
    }

    public function testListCurrentBumdes()
    {
        $this->seed(DatabaseSeeder::class);
        $bumdes = Bumdes::query()->limit(1)->first();

        $result = $this->get('/api/admin/bumdes/current/' . $bumdes->bumdes_id, [
            'Authorization' => 'admin'
        ])->assertStatus(200)->json();

        self::assertEquals(0, $result['meta']['total']);
        self::assertEquals(0, count($result['data']));
    }

    public function testBumdesAuthUpdateSuccess()
    {
        $this->seed(DatabaseSeeder::class);
        $bumdes = Bumdes::where('bumdes_id', 1)->first();

        $this->patch(
            '/api/bumdes/',
            [
                'password' => 'password',
                'password_confirmation' => 'password',
            ],
            [
                'Authorization' => 'bumdes'
            ]
        )->assertStatus(200);
        $new = Bumdes::where('bumdes_id', 1)->first();
        self::assertNotEquals($new->password, $bumdes->password);
    }

    public function testBumdesAuthUpdateErrorValidation()
    {
        $this->seed(DatabaseSeeder::class);

        $this->patch(
            '/api/bumdes/',
            [
                'password' => 'password',
            ],
            [
                'Authorization' => 'bumdes'
            ]
        )->assertStatus(400)
            ->assertJson([
                'errors' => [
                    'password' => [
                        'The password field confirmation does not match.'
                    ]
                ]
            ]);
    }

    public function testGetAllShipmentCurrentBumdes()
    {
        $this->seed(DatabaseSeeder::class);
        $result = $this->get('/api/bumdes/shipment', headers: [
            'Authorization' => 'bumdes'
        ])->assertStatus(200)
            ->json();

        self::assertEquals(0, count($result['data']));
    }

    public function testUpdateCurrentBumdesByBumdes()
    {
        $this->seed(DatabaseSeeder::class);
        $shipment = Shipment::query()->limit(1)->first();
        $this->patch('/api/bumdes/shipment/' . $shipment->no_receipts, headers: [
            'Authorization' => 'bumdes'
        ])->assertStatus(200)
            ->json();
        $new =  Shipment::query()->limit(1)->first();

        self::assertNotEquals($shipment->current_bumdes, $new->current_bumdes);
    }

    public function testBumdesLogout()
    {
        $this->seed(DatabaseSeeder::class);

        $this->delete('/api/bumdes/logout', headers: [
            'Authorization' => 'bumdes'
        ])->assertStatus(200)
            ->assertJson([
                'data' => true
            ]);
    }
}
