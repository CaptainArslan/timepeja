<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Manager extends Authenticatable implements JWTSubject
{
    use HasFactory;
    use SoftDeletes;

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

    public function organizations()
    {
        return $this->hasOne(Organization::class, 'o_id', 'id');
    }

    public function managerOrganization()
    {
        return $this->hasOne(Manager::class, 'id', 'o_id');
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
}