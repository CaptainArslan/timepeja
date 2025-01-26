<?php

namespace App\Models;

use App\Models\City;
use App\Models\State;
use App\Models\Manager;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

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

    // ------------------------ Relationships ----------------------------
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [
            'exp' => now()->addMonth(1)->timestamp, // Set token expiration to 30 days from now
        ];
    }

    public function requests(): HasMany
    {
        return $this->hasMany(Request::class);
    }

    public function routes(): BelongsToMany
    {
        return $this->belongsToMany(Route::class, 'passenger_route');
    }


    // ------------------ Accessors & Mutator -------------------------
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
        return $value;
    }

    public function getImageAttribute()
    {
        return $this->attributes['image'] ?? asset('uploads/placeholder.jpg');
    }

    public function getImageNameAttribute()
    {
        return $this->attributes['image'];
    }
}
