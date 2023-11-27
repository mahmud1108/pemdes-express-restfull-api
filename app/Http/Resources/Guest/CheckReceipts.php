<?php

namespace App\Http\Resources\Guest;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CheckReceipts extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'no_receipts' => $this->no_receipts,
            'receivers_name' => $this->rreceivers_name,
            'receivers_phone' => $this->receivers_phone,
            'date_address' => $this->date_address,
            'current_bumdes' => $this->current_bumdes
        ];
    }
}
