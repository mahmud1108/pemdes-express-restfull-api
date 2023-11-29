<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CourierResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return
            [
                'courier_name' => $this->courier_name,
                'courier_phone' => $this->courier_phone,
                'address' => $this->address,
                'photo' => $this->photo,
                'email' => $this->email,
            ];
    }
}
