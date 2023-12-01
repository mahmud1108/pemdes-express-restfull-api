<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Courier extends Authenticatable
{
    use HasFactory;

    protected $guarded = 'courier_id';

    protected $primaryKey = 'courier_id';

    public $incrementing = false;

    public function shipment()
    {
        $this->hasMany(Shipment::class, 'courier_id', 'courier_id');
    }
}
