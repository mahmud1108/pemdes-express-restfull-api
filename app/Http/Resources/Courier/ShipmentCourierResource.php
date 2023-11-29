<?php

namespace App\Http\Resources\Courier;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ShipmentCourierResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'data' => [
                'no_receipts' => $this->no_receipts,
                'senders_name' => $this->senders_name,
                'senders_phone' => $this->senders_phone,
                'senders_address' => $this->senders_address,
                'item_name' => $this->item_name,
                'weight' => $this->weight,
                'destination_address' => $this->destination_address,
                'receivers_name' => $this->receivers_name,
                'receivers_phone' => $this->receivers_phone,
                'delivery_status' => $this->delivery_status,
                'payment_status' => $this->payment_status,
                'bumdes_destination' => $this->bumdes_destination,
                'current_bumdes' => $this->current_bumdes,
                'date_address' => $this->date_address,
                'courier_id' => $this->courier_id,
                'acknowledgment' => $this->acknowledgment,
            ]
        ];
    }
}
