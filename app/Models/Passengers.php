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

class Passengers extends Authenticatable implements JWTSubject
{
    use HasFactory;
    use SoftDeletes;
    use Notifiable;

    public const STATUS_ACTIVE = 1;
    public const STATUS_DEACTIVE = 0;

    protected $fillable = [
        'id',
        'name',
        'phone',
        'email',
        'email_verified_at',
        'password',
        'gender',
        'type',
        'unique_id',
        'gaurd_code',
        'register_type',
        'bio',
        'location',
        'google',
        'google_id',
        'facebook',
        'facebook_id',
        'twitter',
        'twitter_id',
        'image',
        'token',
        'otp',
        'house_no',
        'near_by',
        'c_id',
        'street_no',
        'town',
        'status',
        'remember_token',
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
        return [];
    }


    /**
     * manager relation function with manager
     *
     * @return void
     */
    public function manager()
    {
        return $this->belongsTo(Manager::class, 'id', 'o_id');
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
}
