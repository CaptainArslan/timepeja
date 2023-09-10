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
     * manager relation function with manager
     *
     * @return void
     */
    public function manager()
    {
        return $this->hasOne(Manager::class, 'o_id' );
    }

    /**
     * city relation with organization
     *
     * @return  [relation]  [this function will return city that belogs to organizations]
     */
    public function city()
    {
        return $this->belongsTo(City::class, 'c_id', 'id');
    }

    /**
     * state relation with organization
     *
     * @return  [type]  [this function will return state that belogs to organizations]
     */
    public function state()
    {
        return $this->belongsTo(State::class, 's_id', 'id');
    }

    /**
     * organization type relation with organization
     *
     * @return  [type]  [this function will return organization type that belogs to organizations]
     */
    public function organizationType()
    {
        return $this->belongsTo(OrganizationType::class, 'o_type_id', 'id');
    }

    /**
     * organization relation with passenger
     *
     * @return void
     */
    public function passengers()
    {
        return $this->hasMany(Passenger::class, 'o_id', 'id');
    }

    /**
     * organization relation with drivers
     *
     * @return void
     */
    public function drivers()
    {
        return $this->hasMany(Driver::class, 'o_id', 'id');
    }

    /**
     * organization relation with drivers
     *
     * @return void
     */
    public function vehicles()
    {
        return $this->hasMany(Vehicle::class, 'o_id', 'id');
    }

    /**
     * organization relation with drivers
     *
     * @return void
     */
    public function routes()
    {
        return $this->hasMany(Route::class, 'o_id', 'id');
    }

    /**
     * organization relation with drivers
     *
     * @return void
     */
    public function schedules()
    {
        return $this->hasMany(Schedule::class, 'o_id', 'id');
    }

    /**
     * organization relation with drivers
     *
     * @return void
     */
    public function requests()
    {
        return $this->hasMany(Request::class);
    }

    /**
     * organization relation with drivers
     *
     * @return void
     */
    public function locations()
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
