<?php

namespace Tests\Feature\Admin;

use App\Models\Bumdes;
use App\Models\Shipment;
use App\Models\Village;
use Carbon\Carbon;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class ShipmentTest extends TestCase
{
    /**
     * A basic feature test example.
     */

    public function testCreateSuccess()
    {
        $this->seed(DatabaseSeeder::class);
        $bumdes = Bumdes::query()->limit(1)->first();
        $now = Carbon::now();
        $now = $now->format('Y-m-d H:i:s');
        $village = Village::query()->limit(1)->first();

        $result =   $this->post('/api/admin/shipment', [
            'senders_name' => 'shipment test',
            'senders_phone' => 'shipment test',
            'senders_address' => 'shipment test',
            'weight' => '1000',
            'item_name' => 'shipment test',
            'destination_address' => 'alamat lengkap',
            'receivers_name' => 'shipment test',
            'receivers_phone' => 'shipment test',
            'delivery_status' => 'diproses',
            'payment_status' => 'telah dibayar',
            'village_destination' => $village->village_id,
            'current_bumdes' => $bumdes->bumdes_id,
            'date_address' => $now,
        ], [
            'Authorization' => 'admin'
        ])->assertStatus(201)
            ->json();

        self::assertEquals(16, count($result['data']));
    }

    public function testCreateSuccessWeight()
    {
        $this->seed(DatabaseSeeder::class);
        $bumdes = Bumdes::query()->limit(1)->first();
        $now = Carbon::now();
        $now = $now->format('Y-m-d H:i:s');
        $village = Village::query()->limit(1)->first();

        $result =   $this->post('/api/admin/shipment', [
            'senders_name' => 'shipment test',
            'senders_phone' => 'shipment test',
            'senders_address' => 'shipment test',
            'weight' => '1200',
            'item_name' => 'shipment test',
            'destination_address' => 'alamat lengkap',
            'receivers_name' => 'shipment test',
            'receivers_phone' => 'shipment test',
            'delivery_status' => 'diproses',
            'payment_status' => 'telah dibayar',
            'village_destination' => $village->village_id,
            'current_bumdes' => $bumdes->bumdes_id,
            'date_address' => $now,
        ], [
            'Authorization' => 'admin'
        ])->assertStatus(201)
            ->json();

        self::assertEquals(16, count($result['data']));
    }
    public function testCreateErrorValidation()
    {
        $this->seed(DatabaseSeeder::class);
        $bumdes = Bumdes::query()->limit(1)->first();
        $now = Carbon::now();
        $now = $now->format('Y-m-d H:i:s');
        $village = Village::query()->limit(1)->first();

        $result =   $this->post('/api/admin/shipment', [
            'senders_name' => 'shipment test',
            'senders_phone' => 'shipment test',
            'senders_address' => 'shipment test',
            'weight' => '1000',
            'total_cost' => '123123',
            'item_name' => 'shipment test',
            'destination_address' => $bumdes->bumdes_id,
            'receivers_name' => 'shipment test',
            'receivers_phone' => 'shipment test',
            'delivery_status' => 'asdf',
            'payment_status' => 'telah dibayar',
            'village_destination' => $village->village_id,
            'current_bumdes' => $bumdes->bumdes_id,
            'date_address' => $now,
        ], [
            'Authorization' => 'admin'
        ])->assertStatus(400)
            ->assertJson([
                'errors' => [
                    'delivery_status' => [
                        'The selected delivery status is invalid.'
                    ]
                ]
            ]);
    }

    public function testGetAllShipmentSucceses()
    {
        $this->seed(DatabaseSeeder::class);
        $result = $this->get('/api/admin/shipment', [
            'Authorization' => 'admin'
        ])->assertStatus(200)->json();

        self::assertEquals(20, $result['meta']['total']);
        self::assertEquals(10, count($result['data']));
    }

    public function testGetShipmentByDeliveryStatus()
    {
        $this->seed(DatabaseSeeder::class);
        $result = $this->get('/api/admin/shipment?delivery=diproses', [
            'Authorization' => 'admin'
        ])->assertStatus(200)->json();

        self::assertEquals(20, $result['meta']['total']);
        self::assertEquals(10, count($result['data']));
    }

    public function testGetShipmentByDeliveryStatusAndPaymentStatus()
    {
        $this->seed(DatabaseSeeder::class);
        $result = $this->get('/api/admin/shipment?delivery=diproses&payment=telah dibayar', [
            'Authorization' => 'admin'
        ])->assertStatus(200)->json();

        self::assertEquals(20, $result['meta']['total']);
        self::assertEquals(10, count($result['data']));
    }

    public function testGetShipmentByPaymentStatus()
    {
        $this->seed(DatabaseSeeder::class);
        $result = $this->get('/api/admin/shipment?payment=telah dibayar', [
            'Authorization' => 'admin'
        ])->assertStatus(200)->json();

        self::assertEquals(20, $result['meta']['total']);
        self::assertEquals(10, count($result['data']));
    }

    public function testUpdateDeliveryStatus()
    {
        $this->seed(DatabaseSeeder::class);
        $shipment = Shipment::query()->limit(1)->first();
        $this->patch('/api/admin/shipment/delivery_status/' . $shipment->no_receipts, [
            'delivery_status' => 'diterima',
            'acknowledgment' => UploadedFile::fake()->create('asdfasdf.jpg'),
        ], [
            'Authorization' => 'admin'
        ])->assertStatus(200)->json();
    }

    public function testUpdateDeliveryStatusFailed()
    {
        $this->seed(DatabaseSeeder::class);
        $shipment = Shipment::query()->limit(1)->first();
        $this->patch('/api/admin/shipment/delivery_status/' . $shipment->no_receipts, [
            'delivery_status' => 'salah input',
            'acknowledgment' => UploadedFile::fake()->create('asdfasdf.jpg'),
        ], [
            'Authorization' => 'admin'
        ])->assertStatus(400)
            ->assertJson([
                'errors' => [
                    'delivery_status' => [
                        'The selected delivery status is invalid.'
                    ]
                ]
            ]);
    }

    public function testDeleteSuccess()
    {
        $this->seed(DatabaseSeeder::class);
        $shipment = Shipment::query()->limit(1)->first();

        $this->delete('/api/admin/shipment' . $shipment->no_receipts, headers: [
            'Authorization' => 'admin'
        ])->assertStatus(200)
            ->assertJson([
                'data' => true
            ]);
    }
}
