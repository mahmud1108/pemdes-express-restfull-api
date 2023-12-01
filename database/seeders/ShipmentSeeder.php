<?php

namespace Database\Seeders;

use App\Models\Bumdes;
use App\Models\Courier;
use App\Models\Shipment;
use App\Models\Village;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Nette\Utils\Random;
use Tests\Feature\CourierTest;

class ShipmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = Carbon::now();
        $now = $now->format('Y-m-d H:i:s');
        $bumdes = Bumdes::where('bumdes_id', 'Bumdes_0')->first();
        $courier = Courier::query()->limit(1)->first();
        $courier_a = Courier::where('courier_id', '!=', 1)->first();
        $village = Village::query()->limit(1)->first();

        for ($i = 0; $i < 10; $i++) {
            Shipment::create([
                'no_receipts' => 'BX_' . $i,
                'senders_name' => 'test name',
                'senders_phone' => '123123',
                'senders_address' => 'test address',
                'weight' => '123',
                'total_cost' => '123123',
                'item_name' => 'test item',
                'destination_address' => 'test destination',
                'receivers_name' => 'test revceivers',
                'receivers_phone' => 'test',
                'delivery_status' => 'diproses',
                'payment_status' => 'telah dibayar',
                'village_destination' => $village->village_id,
                'current_bumdes' => $bumdes->bumdes_id,
                'date_address' => $now,
                'courier_id' => $courier_a->courier_id,
            ]);
        }

        for ($i = 0; $i < 10; $i++) {
            Shipment::create([
                'no_receipts' => 'BX_' . Random::generate(10, '0-9'),
                'senders_name' => 'test name',
                'senders_phone' => '123123',
                'senders_address' => 'test address',
                'weight' => '123',
                'total_cost' => '123123',
                'item_name' => 'test item',
                'destination_address' => 'test destination',
                'receivers_name' => 'test revceivers',
                'receivers_phone' => 'test',
                'delivery_status' => 'diproses',
                'payment_status' => 'telah dibayar',
                'village_destination' => $village->village_id,
                'current_bumdes' => $bumdes->bumdes_id,
                'date_address' => $now,
                'courier_id' => 1,
            ]);
        }
    }
}
