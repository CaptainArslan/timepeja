<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Trip extends Model
{
    use HasFactory;
    use SoftDeletes;

    public const STATUS_PENDING = 'pending';
    public const STATUS_INPROGRESS = 'in-progress';
    public const STATUS_COMPLETED = 'completed';
    public const STATUS_DELAY = 'delay';

    public const IS_DELAYED = true;
    public const IS_NOT_DELAYED = false;


    /**
     * array for fillable
     *
     * @var array
     */
    protected $fillable = [
        'o_id',
        'u_id',
        'sch_id',
        'delay_reason',
        'delay_time',
        'status',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'o_id' => 'integer',
        'u_id' => 'integer',
        'sch_id' => 'integer',
    ];
}
