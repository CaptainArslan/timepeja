<?php

namespace App\Models;

use Illuminate\Support\Carbon;
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

    public const STATUS_ONLINE = true;
    public const STATUS_OFFLINE = false;

    public const STATUS_ACTIVE = true;
    public const STATUS_INACTIVE = false;

    public const DRIVER_LIMIT_PER_PAGE = 10;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'o_id',
        'u_id',
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
        'token',
        'status',
        'online_status',
        'address',
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
        'online_status' => 'boolean'
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
        'device_token',
        'cnic_expiry_date',
        'license_expiry_date',
        'license_no_front_pic',
        'license_no_back_pic',
        'cnic_front_pic',
        'cnic_back_pic',
        'online_status',
        'license_no',
        'created_at',
        'updated_at',
        'deleted_at',
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
    // ------------------ Relations -----------------------------------
    // ----------------------------------------------------------------

    /**
     * Undocumented function
     *
     * @return void
     */
    public function organization()
    {
        return $this->belongsTo(Organization::class, 'o_id', 'id');
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
     * Set the phone number attribute.
     *
     * @param  string  $value
     * @return void
     */
    public function setLicenseNoAttribute($value)
    {
        $this->attributes['license_no'] = str_replace('-', '', $value);
    }

    /**
     * Get the license_no number attribute.
     *
     * @param  string  $value
     * @return string
     */
    public function getLicenseNoAttribute($value)
    {
        return substr($value, 0, 10) . '-' . substr($value, 11, 3);
    }

    /**
     * Set the phone number attribute.
     *
     * @param  string  $value
     * @return void
     */
    public function setCnicAttribute($value)
    {
        $this->attributes['cnic'] = str_replace('-', '', $value);
    }

    /**
     * Get the phone number attribute.
     *
     * @param  string  $value
     * @return string
     */
    public function getCnicAttribute($value)
    {
        return substr($value, 0, 5) . '-' . substr($value, 5, 7) . '-' . substr($value, 12);
    }


    /**
     * Get the front picture of the cnic.
     *
     * @param  string  $value
     * @return string|null
     */
    public function getCnicFrontPicAttribute($value)
    {
        if (filter_var($value, FILTER_VALIDATE_URL)) {
            $value = $this->attributes['cnic_front_pic'];
        } else {
            $value = asset('uploads/drivers/placeholder.jpg');
        }
        return $value;

        // return $this->attributes['cnic_front_pic'] ? asset('uploads/drivers/cnic/' . $this->attributes['cnic_front_pic']) : asset('uploads/drivers/cnic/placeholder.jpg');
    }

    /**
     * Get the front picture name of the vehicle.
     *
     * @param  string  $value
     * @return string|null
     */
    public function getCnicFrontPicNameAttribute()
    {
        $url = $this->attributes['cnic_front_pic'] ?? null;

        // Extract the image name from the URL if it's present
        if ($url && filter_var($url, FILTER_VALIDATE_URL)) {
            $path = parse_url($url, PHP_URL_PATH);
            $name = basename($path);

            return $name;
        }

        // Return the simple name if it's already present
        return $this->attributes['cnic_front_pic'] ?? null;
        // return $this->attributes['cnic_front_pic'];
    }

    /**
     * Get the back picture of the cnic.
     *
     * @param  string  $value
     * @return string|null
     */
    public function getCnicBackPicAttribute($value)
    {
        if (filter_var($value, FILTER_VALIDATE_URL)) {
            $value = $this->attributes['cnic_back_pic'];
        } else {
            $value = asset('uploads/drivers/placeholder.jpg');
        }
        return $value;

        // return $this->attributes['cnic_back_pic'] ? asset('uploads/drivers/cnic/' . $this->attributes['cnic_back_pic']) : asset('uploads/drivers/cnic/placeholder.jpg');
    }

    /**
     * Get the front picture name of the vehicle.
     *
     * @param  string  $value
     * @return string|null
     */
    public function getCnicBackPicNameAttribute()
    {
        $url = $this->attributes['cnic_back_pic'] ?? null;

        // Extract the image name from the URL if it's present
        if ($url && filter_var($url, FILTER_VALIDATE_URL)) {
            $path = parse_url($url, PHP_URL_PATH);
            $name = basename($path);

            return $name;
        }

        // Return the simple name if it's already present
        return $this->attributes['cnic_back_pic'] ?? null;
        // return $this->attributes['cnic_back_pic'];
    }

    /**
     * Get the front picture of the cnic.
     *
     * @param  string  $value
     * @return string|null
     */
    public function getLicenseNoFrontPicAttribute($value)
    {
        if (filter_var($value, FILTER_VALIDATE_URL)) {
            $value = $this->attributes['license_no_front_pic'];
        } else {
            $value = asset('uploads/drivers/placeholder.jpg');
        }
        return $value;
        // return $this->attributes['license_no_front_pic'] ? asset('uploads/drivers/license/' . $this->attributes['license_no_front_pic']) : asset('uploads/drivers/license/placeholder.jpg');
    }

    /**
     * Get the front picture name of the vehicle.
     *
     * @param  string  $value
     * @return string|null
     */
    public function getLicenseNoFrontPicNameAttribute()
    {
        $url = $this->attributes['license_no_front_pic'] ?? null;

        // Extract the image name from the URL if it's present
        if ($url && filter_var($url, FILTER_VALIDATE_URL)) {
            $path = parse_url($url, PHP_URL_PATH);
            $name = basename($path);

            return $name;
        }

        // Return the simple name if it's already present
        return $this->attributes['license_no_front_pic'] ?? null;
        // return $this->attributes['license_no_front_pic'];
    }

    /**
     * Get the back picture of the cnic.
     *
     * @param  string  $value
     * @return string|null
     */
    public function getLicenseNoBackPicAttribute($value)
    {

        if (filter_var($value, FILTER_VALIDATE_URL)) {
            $value = $this->attributes['license_no_back_pic'];
        } else {
            $value = asset('uploads/drivers/placeholder.jpg');
        }
        return $value;

        // return $this->attributes['license_no_back_pic'] ? asset('uploads/drivers/license/' . $this->attributes['license_no_back_pic']) : asset('uploads/drivers/license/placeholder.jpg');
    }

    /**
     * Get the front picture name of the vehicle.
     *
     * @param  string  $value
     * @return string|null
     */
    public function getLicenseNoBackPicNameAttribute()
    {
        $url = $this->attributes['license_no_back_pic'] ?? null;

        // Extract the image name from the URL if it's present
        if ($url && filter_var($url, FILTER_VALIDATE_URL)) {
            $path = parse_url($url, PHP_URL_PATH);
            $name = basename($path);

            return $name;
        }

        // Return the simple name if it's already present
        return $this->attributes['license_no_back_pic'] ?? null;
        // return $this->attributes['license_no_back_pic'];
    }

    /**
     * Get the back picture of the cnic.
     *
     * @param  string  $value
     * @return string|null
     */
    public function getProfilePictureAttribute($value)
    {

        if (filter_var($value, FILTER_VALIDATE_URL)) {
            $value = $this->attributes['profile_picture'];
        } else {
            $value = asset('uploads/drivers/placeholder.jpg');
        }
        return $value;

        // return $this->attributes['license_no_back_pic'] ? asset('uploads/drivers/license/' . $this->attributes['license_no_back_pic']) : asset('uploads/drivers/license/placeholder.jpg');
    }

    /**
     * Get the front picture name of the vehicle.
     *
     * @param  string  $value
     * @return string|null
     */
    public function getProfilePictureNameAttribute()
    {
        $url = $this->attributes['profile_picture'] ?? null;

        // Extract the image name from the URL if it's present
        if ($url && filter_var($url, FILTER_VALIDATE_URL)) {
            $path = parse_url($url, PHP_URL_PATH);
            $name = basename($path);

            return $name;
        }

        // Return the simple name if it's already present
        return $this->attributes['profile_picture'] ?? null;
        // return $this->attributes['license_no_back_pic'];
    }

    // /**
    //  * Get the created_at.
    //  *
    //  * @param  string  $value
    //  * @return string|null
    //  */
    // public function getCreatedAtAttribute($value)
    // {
    //     return Carbon::parse($value)->format('Y-m-d');
    // }

    // /**
    //  * Get the updated_at.
    //  *
    //  * @param  string  $value
    //  * @return string|null
    //  */
    // public function getUpdatedAtAttribute($value)
    // {
    //     return Carbon::parse($value)->format('Y-m-d');
    // }
}
