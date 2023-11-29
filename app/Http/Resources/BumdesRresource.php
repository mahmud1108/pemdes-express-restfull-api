<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BumdesRresource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'bumdes_name' => $this->bumdes_name,
            'bumdes_phone' => $this->bumdes_phone,
            'email' => $this->email,
            'village_id' => $this->village_id
        ];
    }
}
