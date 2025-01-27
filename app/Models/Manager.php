<?php

namespace App\Models;

use Illuminate\Support\Carbon;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Manager extends Authenticatable implements JWTSubject
{
    use HasFactory;
    use SoftDeletes;
    use Notifiable;

    protected $table = 'managers';

    public const STATUS_ACTIVE = true;
    public const STATUS_INACTIVE = false;

    protected $fillable = [
        'organization_id',
        'name',
        'email',
        'phone',
        'password',
        'picture',
        'about',
        'status',
        'address',
    ];

    protected $casts = [
        'organization_id' => 'integer',
        'status' => 'boolean',
        'address' => 'array',
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'deleted_at',
    ];

    // ------------------ Relationships --------------------------------
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [
            'exp' => now()->addMonth(1)->timestamp, // Set token expiration to 7 days from now
        ];
    }

    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    public function deviceTokens(): MorphMany
    {
        return $this->morphMany(DeviceToken::class, 'deviceable');
    }


    // ------------------ Accessors & Mutator -------------------------
    protected function name(): Attribute
    {
        return new Attribute(
            get: fn($value) => ucwords(strtolower($value)),
            set: fn($value) => ucwords(strtolower($value))
        );
    }

    protected function picture(): Attribute
    {
        return new Attribute(
            get: function ($value) {
                if (filter_var($value, FILTER_VALIDATE_URL)) {
                    return $value;
                } elseif ($value) {
                    return asset('uploads/managers/profiles/' . $value);
                } else {
                    return asset('uploads/managers/profiles/placeholder.jpg');
                }
            }
        );
    }

    protected function pictureName(): Attribute
    {
        return new Attribute(
            get: function () {
                $url = $this->attributes['picture'] ?? null;

                if ($url && filter_var($url, FILTER_VALIDATE_URL)) {
                    $path = parse_url($url, PHP_URL_PATH);
                    return basename($path);
                }

                return $this->attributes['picture'] ?? null;
            }
        );
    }

    protected function address(): Attribute
    {
        return new Attribute(
            get: fn($value) => json_decode($value, true),
            set: fn($value) => json_encode($value)
        );
    }
}
