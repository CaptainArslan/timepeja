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

    protected $fillable = [
        'id',
        'u_id',
        'o_id',
        'name',
        'email',
        'phone',
        'pic',
        'address',
        'created_at',
        'updated_at',
        'deleted_at'
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
        return [];
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

    /**
     * Manager Picture Accessor
     *
     * @return  [image with path]  [this function will return the manager image with full path]
     */
    public function getPictureAttribute()
    {
        return $this->attributes['picture'] ? asset('uploads/managers/' . $this->attributes['picture']) : null;
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
        return substr($value, 0, 4) . '-' . substr($value, 7);
    }
}
