<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Trip extends Model
{
    use HasFactory;
    use SoftDeletes;

    const STATUS_PENDING = 'pending';
    const STATUS_INPROGRESS = 'in-progress';
    const STATUS_COMPLETED = 'completed';
    const STATUS_DELAY = 'delay';

    const IS_DELAYED = 0;
    const IS_NOT_DELAYED = 1;


    protected $fillable = [
        'o_id',
        'u_id',
        'sch_id',
        'delay_reason',
        'delay_time',
        'status',
    ];
}
