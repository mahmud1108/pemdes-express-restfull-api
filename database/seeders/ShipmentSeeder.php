<?php

namespace Database\Seeders;

use App\Models\Bumdes;
use App\Models\Courier;
use App\Models\Shipment;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Nette\Utils\Random;

class ShipmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = Carbon::now();
        $now = $now->format('Y-m-d H:i:s');
        $bumdes = Bumdes::query()->limit(1)->first();
        $courier = Courier::query()->limit(1)->first();

        for ($i = 0; $i < 20; $i++) {
            Shipment::create([
                'no_receipts' => Random::generate(5),
                'senders_name' => 'test name',
                'senders_phone' => '123123',
                'senders_address' => 'test address',
                'weight' => '123',
                'item_name' => 'test item',
                'destination_address' => 'test destination',
                'receivers_name' => 'test revceivers',
                'receivers_phone' => 'test',
                'delivery_status' => 'diproses',
                'payment_status' => 'telah dibayar',
                'bumdes_destination' => $bumdes->bumdes_id,
                'current_bumdes' => $bumdes->bumdes_id,
                'date_address' => $now,
                'courier_id' => $courier->courier_id,
            ]);
        }
    }
}
