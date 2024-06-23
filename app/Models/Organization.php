<?php

namespace App\Models;

use App\Models\City;
use App\Models\Route;
use App\Models\State;
use App\Models\Driver;
use App\Models\Manager;
use App\Models\Vehicle;
use App\Models\Schedule;
use App\Models\Passenger;
use App\Models\OrganizationType;
use App\Models\Request;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Organization extends Model
{
    use HasFactory;
    use SoftDeletes;

    public const STATUS_ACTIVE = 1;
    public const STATUS_DEACTIVE = 0;

    /**
     * array for fillable
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'branch_name',
        'email',
        'phone',
        's_id',
        'c_id',
        'o_type_id',
        'address',
        'head_name',
        'head_email',
        'head_phone',
        'head_address',
        'status'
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at',
        'deactivate_code',
    ];


    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'u_id' => 'integer',
        's_id' => 'integer',
        'c_id' => 'integer',
        'o_type_id' => 'integer',
    ];


    /**
     * @return HasOne
     */
    public function manager(): HasOne
    {
        return $this->hasOne(Manager::class, 'o_id');
    }


    /**
     * @return BelongsTo
     */
    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class, 'c_id', 'id');
    }

    /**
     * @return BelongsTo
     */
    public function state(): BelongsTo
    {
        return $this->belongsTo(State::class, 's_id', 'id');
    }


    /**
     * @return BelongsTo
     */
    public function organizationType(): BelongsTo
    {
        return $this->belongsTo(OrganizationType::class, 'o_type_id', 'id');
    }


    /**
     * @return HasMany
     */
    public function passengers(): HasMany
    {
        return $this->hasMany(Passenger::class, 'o_id', 'id');
    }

    /**
     * @return HasMany
     */
    public function drivers(): HasMany
    {
        return $this->hasMany(Driver::class, 'o_id', 'id');
    }


    /**
     * @return HasMany
     */
    public function vehicles(): HasMany
    {
        return $this->hasMany(Vehicle::class, 'o_id', 'id');
    }


    /**
     * @return HasMany
     */
    public function routes(): HasMany
    {
        return $this->hasMany(Route::class, 'o_id', 'id');
    }


    /**
     * @return HasMany
     */
    public function schedules(): HasMany
    {
        return $this->hasMany(Schedule::class, 'o_id', 'id');
    }


    /**
     * @return HasMany
     */
    public function requests(): HasMany
    {
        return $this->hasMany(Request::class);
    }


    /**
     * @return HasMany
     */
    public function locations(): HasMany
    {
        return $this->hasMany(Location::class);
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
     * Set the phone number attribute.
     *
     * @param  string  $value
     * @return void
     */
    public function setPhoneAttribute($value)
    {
        $this->attributes['phone'] = str_replace('-', '', $value);
    }

    /**
     * Get the phone number attribute.
     *
     * @param  string  $value
     * @return string
     */
    public function getPhoneAttribute($value)
    {
        return substr($value, 0, 4) . '-' . substr($value, 4, 8);
    }

    /**
     * Set the head phone attribute.
     *
     * @param  string  $value
     * @return void
     */
    public function setHeadPhoneAttribute($value)
    {
        $this->attributes['head_phone'] = str_replace('-', '', $value);
    }

    /**
     * Get the head phone attribute.
     *
     * @param  string  $value
     * @return string
     */
    public function getHeadPhoneAttribute($value)
    {
        return substr($value, 0, 4) . '-' . substr($value, 4, 8);
        // return substr($value, 0, 4) . '-' . substr($value, 7);
    }

    /**
     * Set the org head name attribute.
     *
     * @param  string  $value
     * @return void
     */
    public function setOrgHeadPhoneAttribute($value)
    {
        $this->attributes['head_phone'] = ucwords(strtolower($value));
    }

    /**
     * Get the org head name attribute.
     *
     * @param  string  $value
     * @return string
     */
    public function getOrgHeadPhoneAttribute($value)
    {
        return ucwords(strtolower($this->attributes['head_phone']));
    }
}
