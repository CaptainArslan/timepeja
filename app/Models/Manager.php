<?php

namespace App\Models;

use Illuminate\Support\Carbon;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Manager extends Authenticatable implements JWTSubject
{
    use HasFactory;
    use SoftDeletes;
    use Notifiable;

    protected $table = 'managers';
    protected $appends = ['picture'];

    public const STATUS_ACTIVE = true;
    public const STATUS_INACTIVE = false;

    protected $fillable = [
        'id',
        'u_id',
        'o_id',
        'name',
        'email',
        'phone',
        'picture',
        'address'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'o_id' => 'integer',
        'u_id' => 'integer',
        'status' => 'boolean',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'otp',
        'deleted_at',
        'device_token',
    ];

    // Rest omitted for brevity
    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [
            'exp' => now()->addMonth(1)->timestamp, // Set token expiration to 7 days from now
        ];
    }


    // ----------------------------------------------------------------
    // ------------------ Relationships --------------------------------
    // ----------------------------------------------------------------
    
    public function organization()
    {
        return $this->belongsTo(Organization::class, 'o_id');
    }

    public function managerOrganization()
    {
        return $this->hasOne(Manager::class, 'id', 'o_id');
    }
    
    public function city()
    {
        return $this->hasOne(City::class, 'id', 'c_id');
    }

    public function state()
    {
        return $this->hasOne(State::class, 'id', 's_id');
    }

    // not sure about this relationship not removing yet
    public function organizationType()
    {
        return $this->hasOne(OrganizationType::class, 'o_type_id', 'id');
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
     * Manager Picture Accessor
     *
     * @return  [image with path]  [this function will return the manager image with full path]
     */
    public function getPictureAttribute($value)
    {
        if (filter_var($value, FILTER_VALIDATE_URL)) {
            return $value; // If it's a valid URL, return it directly
        } elseif ($value) {
            return asset('uploads/managers/profiles/' . $value); // If not a URL but has a value, return asset URL
        } else {
            return asset('uploads/managers/profiles/placeholder.jpg'); // If empty or not set, return placeholder URL
        }
    }

    /**
     * Get the front picture name of the vehicle.
     *
     * @param  string  $value
     * @return string|null
     */
    public function getPictureNameAttribute()
    {
        $url = $this->attributes['picture'] ?? null;

        // Extract the image name from the URL if it's present
        if ($url && filter_var($url, FILTER_VALIDATE_URL)) {
            $path = parse_url($url, PHP_URL_PATH);
            $name = basename($path);

            return $name;
        }

        // Return the simple name if it's already present
        return $this->attributes['picture'] ?? null;
        // return $this->attributes['picture'];
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
}
