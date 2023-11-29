<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bumdes extends Model
{
    use HasFactory;

    protected $guarded = 'bumdes_id';

    protected $primaryKey = 'bumdes_id';

    public $incrementing = false;
}
