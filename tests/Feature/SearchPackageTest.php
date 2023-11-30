<?php

namespace Tests\Feature;

use App\Models\Shipment;
use App\Models\Village;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

use function PHPSTORM_META\map;

class SearchPackageTest extends TestCase
{
    /**
     * A basic feature test example.
     */

    public function testSearchPackageByNoReceipts()
    {
        $this->seed(DatabaseSeeder::class);
        $shipment = Shipment::query()->limit(1)->first();

        $this->get('/api/check_receipts/' . $shipment->no_receipts)
            ->assertStatus(200)->json();
    }

    public function testSearchPackageByUnitTestNotFound()
    {
        $this->seed(DatabaseSeeder::class);
        $this->get('/api/check_receipts/999')
            ->assertStatus(404)
            ->assertJson([
                'errors' => [
                    'message' => [
                        'Not found'
                    ]
                ]
            ]);
    }

    public function testCheckCostSuccessUp()
    {
        $this->seed(DatabaseSeeder::class);
        $village = Village::query()->limit(1)->first();
        $this->get('/api/check_cost/' . $village->village_id . '?weight=1100')
            ->assertStatus(200)
            ->assertJson([
                'data' => [
                    'weight' => 1100,
                    'cost' => 20000,
                    'destination' => $village->village_name
                ]
            ]);
    }

    public function testCheckCostSuccessUnder()
    {
        $this->seed(DatabaseSeeder::class);
        $village = Village::query()->limit(1)->first();
        $this->get('/api/check_cost/' . $village->village_id . '?weight=900')
            ->assertStatus(200)
            ->assertJson([
                'data' => [
                    'weight' => 900,
                    'cost' => 10000,
                    'destination' => $village->village_name
                ]
            ]);
    }

    public function testCheckCostIdNotFound()
    {
        $this->seed(DatabaseSeeder::class);
        $village = Village::query()->limit(1)->first();

        $this->get('/api/check_cost/' . $village->village_id  . 'asd' . '?weight=900')
            ->assertStatus(404)
            ->assertJson([
                'errors' => [
                    'Not found'
                ]
            ]);
    }

    public function testCompleteShipment()
    {
        $this->seed(DatabaseSeeder::class);
        $shipment = Shipment::query()->limit(1)->first();

        $this->patch('/api/admin/shipment/delivery_status/' . $shipment->no_receipts, [
            'delivery_status' => 'diterima',
            'acknowledgment' => UploadedFile::fake()->create('adsfasdf.jpg', 123)
        ], [
            'Authorization' => 'admin'
        ])->assertStatus(200)->json();

        $result = $this->get('/api/check_receipts/' . $shipment->no_receipts)
            ->assertStatus(200)->json();

        self::assertNotNull($result['data']['acknowledgment']);
        self::assertEquals('diterima', $result['data']['delivery_status']);
    }
}
