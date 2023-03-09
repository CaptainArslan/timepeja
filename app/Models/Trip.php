<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Trip extends Model
{
    use HasFactory;
    protected $fillable = [
        'o_id',
        'u_id',
        'sch_id',
        'delay_reason',
        'delay_time',
        'status',
    ];
}
