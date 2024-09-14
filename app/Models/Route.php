<?php

namespace App\Models;

use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Route extends Model
{
    use HasFactory;
    use SoftDeletes;

    public const STATUS_ACTIVE = true;
    public const STATUS_INACTIVE = false;

    // for pagination
    public const ROUTE_LIMIT_PER_PAGE = 10;

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
        'status',
        'way_points',
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
        'status' => 'boolean',
        'from_longitude' => 'float',
        'from_latitude' => 'float',
        'to_longitude' => 'float',
        'to_latitude' => 'float'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'deleted_at'
    ];


    /**
     * organization relation with routes
     *
     * @return  [type]  [return description]
     */
    public function organization()
    {
        return $this->belongsTo(Organization::class, 'o_id', 'id');
    }

    public function passengers()
    {
        return $this->belongsToMany(Passenger::class, 'passenger_route');
    }

    // /**
    //  * organization relation with routes
    //  *
    //  * @return  [type]  [return description]
    //  */
    // public function organizations()
    // {
    //     return $this->belongsTo(Organization::class, 'o_id', 'id');
    // }



    // ----------------------------------------------------------------
    // ------------------ Accessors & Mutator -------------------------
    // ----------------------------------------------------------------

    /**
     * Set the name attribute.
     *
     * @param  string  $value
     * @return void
     */
    public function setNameAttribute($value)
    {
        $this->attributes['name'] = ucwords(strtolower($value));
    }

    /**
     * Get the name attribute.
     *
     * @param  string  $value
     * @return string
     */
    public function getNameAttribute($value)
    {
        return ucwords(strtolower($value));
    }

    /**
     * Set the from attribute.
     *
     * @param  string  $value
     * @return void
     */
    public function setFromAttribute($value)
    {
        $this->attributes['from'] = ucwords(strtolower($value));
    }

    /**
     * Get the from attribute.
     *
     * @param  string  $value
     * @return string
     */
    public function getFromAttribute($value)
    {
        return ucwords(strtolower($value));
    }

    /**
     * Set the to attribute.
     *
     * @param  string  $value
     * @return void
     */
    public function setToAttribute($value)
    {
        $this->attributes['to'] = ucwords(strtolower($value));
    }

    /**
     * Get the to attribute.
     *
     * @param  string  $value
     * @return string
     */
    public function getToAttribute($value)
    {
        return ucwords(strtolower($value));
    }

    /**
     * Get the created_at.
     *
     * @param  string  $value
     * @return string|null
     */
    public function getCreatedAtAttribute($value)
    {
        return Carbon::parse($value)->format('Y-m-d');
    }

    /**
     * Get the updated_at.
     *
     * @param  string  $value
     * @return string|null
     */
    public function getUpdatedAtAttribute($value)
    {
        return Carbon::parse($value)->format('Y-m-d');
    }


    public function getWayPointsAttribute($value)
    {
        return $value ? json_decode($value, true) : null;
    }



    public function scopeByOrganization($query, $organization_id)
    {
        return $query->when($organization_id, function ($query) use ($organization_id) {
            return $query->where('o_id', $organization_id);
        }, function ($query) {
            return $query;
        });
    }
}
