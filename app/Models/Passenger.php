<?php

namespace App\Models;

use App\Models\City;
use App\Models\State;
use App\Models\Manager;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Passenger extends Authenticatable implements JWTSubject
{
    use HasFactory;
    use SoftDeletes;
    use Notifiable;

    protected $table = 'passengers';

    public const STATUS_ACTIVE = true;
    public const STATUS_DEACTIVE = false;
    public const PASSENGER_LIMIT_PER_PAGE = false;

    /**
     * array for fillable
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'phone',
        'email',
        'email_verified_at',
        'password',
        'gender',
        'unique_id',
        'gaurd_code',
        'bio',
        'location',
        'lattutude',
        'longitude',
        'google',
        'google_id',
        'facebook',
        'facebook_id',
        'twitter',
        'twitter_id',
        'image',
        'otp',
        'status',
        'remember_token',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
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
    ];

    // ----------------------------------------------------------------
    // ------------------ Jwt Auth  -----------------------------------
    // ----------------------------------------------------------------

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
            'exp' => now()->addMonth(1)->timestamp, // Set token expiration to 30 days from now
        ];
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
     * Get the front picture of the cnic.
     *
     * @param  string  $value
     * @return string|null
     */
    public function getImageAttribute()
    {
        return $this->attributes['image'] ? asset('uploads/passengers/profile/' . $this->attributes['image']) : asset('uploads/placeholder.jpg');
    }

    /**
     * Get the front picture name of the vehicle.
     *
     * @param  string  $value
     * @return string|null
     */
    public function getImageNameAttribute()
    {
        return $this->attributes['image'];
    }




    // ----------------------------------------------------------------
    // -------------------------- Relations ---------------------------
    // ----------------------------------------------------------------

    /**
     * relation of passenger and requests
     *
     * @return void
     */
    public function requests()
    {
        return $this->hasMany(Request::class);
    }
}
