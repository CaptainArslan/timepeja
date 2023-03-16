<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Schedule extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'schedules';

    protected $fillable = [
        'o_id',
        'u_id',
        'route_id',
        'v_id',
        'd_id',
        'date',
        'time',
        'status'
    ];

    /**
     * [organizations description]
     *
     * @return  [type]  [return description]
     */
    public function organizations()
    {
        return $this->belongsTo(Organization::class, 'o_id', 'id');
    }

    /**
     * [routes description]
     *
     * @return  [type]  [return description]
     */
    public function routes()
    {
        return $this->belongsTo(Route::class, 'route_id', 'id');
    }

    /**
     * [vehicles description]
     *
     * @return  [type]  [return description]
     */
    public function vehicles()
    {
        return $this->belongsTo(Vehicle::class, 'v_id', 'id');
    }

    /**
     * [drivers description]
     *
     * @return  [type]  [return description]
     */
    public function drivers()
    {
        return $this->belongsTo(Driver::class, 'd_id', 'id');
    }

    /**
     * [users description]
     *
     * @return  [type]  [return description]
     */
    public function users()
    {
        return $this->belongsTo(User::class, 'u_id', 'id');
    }
}
