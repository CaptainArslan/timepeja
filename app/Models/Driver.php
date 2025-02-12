<?php

namespace App\Models;

use App\Traits\HasOrganization;
use Illuminate\Support\Facades\Storage;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Builder;
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
    use HasOrganization;

    public const ONLINE = true;
    public const OFFLINE = false;

    public const STATUS_ACTIVE = true;
    public const STATUS_DEACTIVE = false;

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
        'online_status' => 'boolean'
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'otp',
        'device_token',
        'cnic_expiry_date',
        'license_expiry_date',
        'online_status',
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
            get: fn($value) => $value == self::STATUS_ACTIVE ? 'Active' : 'Deactive',
        );
    }

    protected function cnicFront(): Attribute
    {
        return new Attribute(
            get: fn($value) => $value && Storage::exists($value) ? Storage::url($value) : null,
        );
    }

    protected function cnicBack(): Attribute
    {
        return new Attribute(
            get: fn($value) => $value && Storage::exists($value) ? Storage::url($value) : null,
        );
    }

    protected function licenseFront(): Attribute
    {
        return new Attribute(
            get: fn($value) => $value && Storage::exists($value) ? Storage::url($value) : null,
        );
    }

    protected function licenseBack(): Attribute
    {
        return new Attribute(
            get: fn($value) => $value && Storage::exists($value) ? Storage::url($value) : null,
        );
    }

    // ------------------ Scopes -----------------------------------
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_ACTIVE);
    }

    public function scopeInactive(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_DEACTIVE);
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
