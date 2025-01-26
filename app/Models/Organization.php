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
        'organization_type_id',
        'name',
        'branch_name',
        'branch_code',
        'email',
        'phone',
        'code',
        'state_id',
        'city_id',
        'address',
        'head_name',
        'head_email',
        'head_phone',
        'head_address',
        'status',
        'deactivate_code',
    ];

    protected $hidden = [
        'deactivate_code',
        'created_at',
        'updated_at',
        'deleted_at',
    ];


    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'state_id' => 'integer',
        'city_id' => 'integer',
        'organization_type_id' => 'integer',
    ];


    public function manager(): HasOne
    {
        return $this->hasOne(Manager::class);
    }

    public function organizationType(): BelongsTo
    {
        return $this->belongsTo(OrganizationType::class);
    }

    public function passengers(): HasMany
    {
        return $this->hasMany(Passenger::class, 'o_id', 'id');
    }

    public function drivers(): HasMany
    {
        return $this->hasMany(Driver::class);
    }

    public function vehicles(): HasMany
    {
        return $this->hasMany(Vehicle::class, 'o_id', 'id');
    }

    public function routes(): HasMany
    {
        return $this->hasMany(Route::class);
    }

    public function schedules(): HasMany
    {
        return $this->hasMany(Schedule::class, 'o_id', 'id');
    }

    public function requests(): HasMany
    {
        return $this->hasMany(Request::class);
    }

    public function locations(): HasMany
    {
        return $this->hasMany(Location::class);
    }



    // ----------------------------------------------------------------
    // ------------------ Accessors & Mutator -------------------------
    // ----------------------------------------------------------------
    public function setNameAttribute($value)
    {
        $this->attributes['name'] = ucwords(strtolower($value));
    }

    public function getNameAttribute($value)
    {
        return ucwords(strtolower($value));
    }

    public function setPhoneAttribute($value)
    {
        $this->attributes['phone'] = str_replace('-', '', $value);
    }

    public function getPhoneAttribute($value)
    {
        return substr($value, 0, 4) . '-' . substr($value, 4, 8);
    }

    public function setHeadPhoneAttribute($value)
    {
        $this->attributes['head_phone'] = str_replace('-', '', $value);
    }

    public function getHeadPhoneAttribute($value)
    {
        return substr($value, 0, 4) . '-' . substr($value, 4, 8);
        // return substr($value, 0, 4) . '-' . substr($value, 7);
    }

    public function setOrgHeadPhoneAttribute($value)
    {
        $this->attributes['head_phone'] = ucwords(strtolower($value));
    }

    public function getOrgHeadPhoneAttribute($value)
    {
        return ucwords(strtolower($this->attributes['head_phone']));
    }
}
