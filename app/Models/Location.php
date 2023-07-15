<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    use HasFactory;

    protected $fillable = [
        'id', 'u_id', 'o_id', 'name', 'p_id', 'v_id', 'd_id', 'type', 'latitude', 'longitude',
    ];
}
