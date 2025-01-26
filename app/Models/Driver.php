<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
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
        'cnic_front_pic',
        'cnic_back_pic',
        'cnic_expiry_date',
        'license_no',
        'license_no_front_pic',
        'license_no_back_pic',
        'license_expiry_date',
        'otp',
        'device_token',
        'status',
        'online_status',
        'address',
    ];

    protected $casts = [
        'o_id' => 'integer',
        'u_id' => 'integer',
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

    // ----------------------------------------------------------------
    // ------------------ Jwt Auth  -----------------------------------
    // ----------------------------------------------------------------
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



    // ----------------------------------------------------------------
    // ------------------ Relations -----------------------------------
    // ----------------------------------------------------------------
    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }


    // ----------------------------------------------------------------
    // ------------------ Accessors & Mutator -------------------------
    // ----------------------------------------------------------------

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
        // return substr($value, 0, 4) . '-' . substr($value, 4, 8);
    }

    public function setLicenseNoAttribute($value)
    {
        $this->attributes['license_no'] = str_replace('-', '', $value);
    }

    public function getLicenseNoAttribute($value)
    {
        return $value;
    }

    public function setCnicAttribute($value)
    {
        $this->attributes['cnic'] = str_replace('-', '', $value);
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
}
