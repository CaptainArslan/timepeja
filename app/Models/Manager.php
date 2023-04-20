<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Manager extends Authenticatable implements JWTSubject
{
    use HasFactory;
    use SoftDeletes;
    use Notifiable;

    protected $table = 'managers';
    protected $appends = ['picture'];

    public const STATUS_ACTIVE = false;
    public const STATUS_INACTIVE = true;

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
        'otp'
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

    /**
     * Send the password reset notification.
     *
     * @param  string  $token
     * @return void
     */
    public function organization()
    {
        return $this->belongsTo(Organization::class, 'o_id', 'id');
    }

    /**
     * [managerOrganization description]
     *
     * @return  [type]  [return description]
     */
    public function managerOrganization()
    {
        return $this->hasOne(Manager::class, 'id', 'o_id');
    }

    /**
     * [managerUser description]
     *
     * @return  [type]  [return description]
     *
     */
    public function organizationType()
    {
        return $this->hasOne(Organization::class, 'id', 'o_type_id');
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
    public function getPictureAttribute()
    {
        return $this->attributes['picture'] ? asset('uploads/managers/profiles/' . $this->attributes['picture']) : null;
    }

    /**
     * Get the front picture name of the vehicle.
     *
     * @param  string  $value
     * @return string|null
     */
    public function getPictureNameAttribute()
    {
        return $this->attributes['picture'];
    }
}
