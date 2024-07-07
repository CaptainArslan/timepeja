<?php

namespace App\Models;

use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Builder;


class Schedule extends Model
{
    use HasFactory;
    use SoftDeletes;

    public const STATUS_PUBLISHED = 'published';
    public const STATUS_DRAFT = 'draft';
    public const TRIP_STATUS_UPCOMING = 'upcoming';
    public const TRIP_STATUS_INPROGRESS = 'in-progress';
    public const TRIP_STATUS_COMPLETED = 'completed';
    public const TRIP_STATUS_DELAYED = 'delayed';
    public const TRIP_NOTDELAYED = false;
    public const TRIP_ISDELAYED = true;
    public const SCHEDULE_TIME = 15;


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
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'o_id' => 'integer',
        'u_id' => 'integer',
        'route_id' => 'integer',
        'v_id' => 'integer',
        'd_id' => 'integer',
        'id_delay' => 'boolean',
        'created_at' => 'datetime:Y-m-d H:i:s',
        'date' => 'string'
        // 'trip_status' => 'boolean'
    ];



    // ----------------------------------------------------------------
    // -------------------------- Relations ---------------------------
    // ----------------------------------------------------------------

    /**
     * relation with organization
     *
     * @return  [type]  return relation
     */
    public function organizations()
    {
        return $this->belongsTo(Organization::class, 'o_id', 'id');
    }

    /**
     * relation with route
     *
     * @return  [type]  return relation
     */
    public function routes()
    {
        return $this->belongsTo(Route::class, 'route_id', 'id');
    }

    /**
     * relation with route
     *
     * @return  [type]  return relation
     */
    public function route()
    {
        return $this->belongsTo(Route::class, 'route_id', 'id');
    }
    /**
     * RELATION WITH VEHICLE
     *
     * @return  [type]  return relation
     */
    public function vehicles()
    {
        return $this->belongsTo(Vehicle::class, 'v_id', 'id');
    }

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class, 'v_id', 'id');
    }

    public function drivers()
    {
        return $this->belongsTo(Driver::class, 'd_id', 'id');
    }

    public function driver()
    {
        return $this->belongsTo(Driver::class, 'd_id', 'id');
    }

    /**
     * relation with user
     *
     * @return  [type]  [return description]
     */

    public function users()
    {
        return $this->belongsTo(User::class, 'u_id', 'id');
    }


    // ----------------------------------------------------------------
    // ------------------ Accessors & Mutator -------------------------
    // ----------------------------------------------------------------

    public function getTimeAttribute($value)
    {
        return Carbon::parse($value)->format('h:i A');
    }


    public function getCreatedAtAttribute($value)
    {
        return Carbon::parse($value)->format('Y-m-d');
    }

    public function getUpdatedAtAttribute($value)
    {
        return Carbon::parse($value)->format('Y-m-d');
    }

    // ----------------------------------------------------------------
    // -------------------------- Scopes ------------------------------
    // ----------------------------------------------------------------


    public function scopeIsNotNotified(Builder $query): Builder
    {
        return  $query->where('is_notified', 0);
    }

    public function scopeIsNotified(Builder $query): Builder
    {
        return  $query->where('is_notified', 1);
    }
}
