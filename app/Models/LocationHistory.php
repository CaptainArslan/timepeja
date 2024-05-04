<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LocationHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'u_id', 'o_id', 'name', 'p_id', 'v_id', 'd_id', 'type', 'latitude', 'longitude'
    ];
}
