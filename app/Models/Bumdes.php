<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Bumdes extends Authenticatable
{
    use HasFactory;

    protected $guarded = 'bumdes_id';

    protected $primaryKey = 'bumdes_id';

    public $incrementing = false;
}
