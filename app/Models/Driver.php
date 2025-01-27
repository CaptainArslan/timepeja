<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Driver extends Authenticatable implements JWTSubject
{
    use HasFactory;
    use SoftDeletes;
    use Notifiable;

    public const ONLINE = true;
    public const OFFLINE = false;

    public const STATUS_ACTIVE = true;
    public const STATUS_INACTIVE = false;

    protected $fillable = [
        'organization_id',
        'name',
        'email',
        'password',
        'phone',
        'cnic',
        'profile_picture',
        'cnic_front',
        'cnic_back',
        'license_no',
        'license_front',
        'license_back',
        'status',
        'online_status',
        'address',
    ];

    protected $casts = [
        'organization_id' => 'integer',
        'status' => 'boolean',
        'online_status' => 'boolean'
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'otp',
        'device_token',
        'cnic_expiry_date',
        'license_expiry_date',
        // 'license_no_front_pic',
        // 'license_no_back_pic',
        // 'cnic_front_pic',
        // 'cnic_back_pic',
        'online_status',
        // 'license_no',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    // ------------------ Jwt Auth  -----------------------------------
    public function getJWTIdentifier(): mixed
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims(): array
    {
        return [
            'exp' => now()->addMonth(1)->timestamp, // Set token expiration to 30 days from now
        ];
    }


    // ------------------ Relations -----------------------------------
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
            set: fn($value) => ucwords(strtolower($value)),
            get: fn($value) => ucwords(strtolower($value))
        );
    }

    protected function address(): Attribute
    {
        return new Attribute(
            set: fn($value) => json_encode($value),
            get: fn($value) => json_decode($value, true)
        );
    }

    protected function status(): Attribute
    {
        return new Attribute(
            get: fn($value) => $value === self::STATUS_ACTIVE ? 'Active' : 'Deactive',
            set: fn($value) => $value === 'Active' ? self::STATUS_ACTIVE : self::STATUS_INACTIVE
        );
    }

    public function getCnicAttribute($value)
    {
        return $value;
    }

    public function getCnicFrontPicAttribute($value)
    {
        if (filter_var($value, FILTER_VALIDATE_URL)) {
            $value = $this->attributes['cnic_front_pic'];
        } else {
            $value = asset('uploads/drivers/placeholder.jpg');
        }
        return $value;
    }

    public function getCnicFrontPicNameAttribute()
    {
        $url = $this->attributes['cnic_front_pic'] ?? null;

        if ($url && filter_var($url, FILTER_VALIDATE_URL)) {
            $path = parse_url($url, PHP_URL_PATH);
            return  basename($path);
        }
        return $this->attributes['cnic_front_pic'] ?? null;
    }

    public function getCnicBackPicAttribute($value)
    {
        if (filter_var($value, FILTER_VALIDATE_URL)) {
            $value = $this->attributes['cnic_back_pic'];
        } else {
            $value = asset('uploads/drivers/placeholder.jpg');
        }
        return $value;
    }

    public function getCnicBackPicNameAttribute()
    {
        $url = $this->attributes['cnic_back_pic'] ?? null;

        if ($url && filter_var($url, FILTER_VALIDATE_URL)) {
            $path = parse_url($url, PHP_URL_PATH);
            $name = basename($path);

            return $name;
        }
        return $this->attributes['cnic_back_pic'] ?? null;
    }

    public function getLicenseNoFrontPicAttribute($value)
    {
        if (filter_var($value, FILTER_VALIDATE_URL)) {
            $value = $this->attributes['license_no_front_pic'];
        } else {
            $value = asset('uploads/drivers/placeholder.jpg');
        }
        return $value;
    }

    public function getLicenseNoFrontPicNameAttribute()
    {
        $url = $this->attributes['license_no_front_pic'] ?? null;

        // Extract the image name from the URL if it's present
        if ($url && filter_var($url, FILTER_VALIDATE_URL)) {
            $path = parse_url($url, PHP_URL_PATH);
            $name = basename($path);
            return $name;
        }
        return $this->attributes['license_no_front_pic'] ?? null;
    }

    public function getLicenseNoBackPicAttribute($value)
    {

        if (filter_var($value, FILTER_VALIDATE_URL)) {
            $value = $this->attributes['license_no_back_pic'];
        } else {
            $value = asset('uploads/drivers/placeholder.jpg');
        }
        return $value;
    }

    public function getLicenseNoBackPicNameAttribute()
    {
        $url = $this->attributes['license_no_back_pic'] ?? null;

        if ($url && filter_var($url, FILTER_VALIDATE_URL)) {
            $path = parse_url($url, PHP_URL_PATH);
            $name = basename($path);

            return $name;
        }

        return $this->attributes['license_no_back_pic'] ?? null;
    }

    public function getProfilePictureAttribute($value)
    {

        if (filter_var($value, FILTER_VALIDATE_URL)) {
            $value = $this->attributes['profile_picture'];
        } else {
            $value = asset('uploads/drivers/placeholder.jpg');
        }
        return $value;
    }

    public function getProfilePictureNameAttribute()
    {
        $url = $this->attributes['profile_picture'] ?? null;

        if ($url && filter_var($url, FILTER_VALIDATE_URL)) {
            $path = parse_url($url, PHP_URL_PATH);
            $name = basename($path);

            return $name;
        }

        return $this->attributes['profile_picture'] ?? null;
    }

    // ------------------ Scopes -----------------------------------

    public function scopeByCompany(Builder $query, $organizationId = null): Builder
    {
        return $query->when($organizationId, function ($query, $organizationId) {
            return $query->where('organization_id', $organizationId);
        });
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_ACTIVE);
    }

    public function scopeInactive(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_INACTIVE);
    }

    public function scopeSearch(Builder $query, $search): Builder
    {
        return $query->when($search, function ($query, $search) {
            return $query->where('name', 'like', '%' . $search . '%')
                ->orWhere('email', 'like', '%' . $search . '%')
                ->orWhere('phone', 'like', '%' . $search . '%')
                ->orWhere('cnic', 'like', '%' . $search . '%')
                ->orWhere('license_no', 'like', '%' . $search . '%');
        });
    }
}
