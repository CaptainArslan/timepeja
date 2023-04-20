<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Route extends Model
{
    use HasFactory;
    use SoftDeletes;

    public const STATUS_ACTIVE = true;
    public const STATUS_INACTIVE = false;

    /**
     * array for fillable
     *
     * @var array
     */
    protected $fillable = [
        'o_id',
        'u_id',
        'name',
        'number',
        'from',
        'from_longitude',
        'from_latitude',
        'to',
        'to_longitude',
        'to_latitude',
        'status'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'o_id' => 'integer',
        'u_id' => 'integer',
        'number' => 'integer',
    ];

    /**
     * organization relation with routes
     *
     * @return  [type]  [return description]
     */
    public function organizations()
    {
        return $this->belongsTo(Organization::class, 'o_id', 'id');
    }
}
