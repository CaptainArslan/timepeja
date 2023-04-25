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
        'status' => 'boolean'
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

}
