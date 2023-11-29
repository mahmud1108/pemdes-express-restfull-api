<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Courier extends Model
{
    use HasFactory;

    protected $guarded = 'courier_id';

    protected $primaryKey = 'courier_id';

    public $incrementing = false;
}
