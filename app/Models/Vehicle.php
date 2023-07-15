<?php

namespace App\Models;

use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Vehicle extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'vehicles';

    public const STATUS_ACTIVE = true;
    public const STATUS_DEACTIVE = false;

    // for pagination
    public const VEHICLE_LIMIT_PER_PAGE = 10;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'o_id',
        'u_id',
        'v_type_id',
        'number',
        'no_of_seat',
        'front_pic',
        'back_pic',
        'number_pic',
        'reg_date',
        'expiry_date',
        'model_no',
        'brand_name',
        'color',
        'chassis_no',
        'engine_no',
        'car_accessories',
        'status'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'o_id' => 'integer',
        'u_id' => 'integer',
        'v_type_id' => 'integer',
        'status' => 'boolean'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'no_of_seat',
        'back_pic',
        'reg_date',
        'expiry_date',
        'model_no',
        'brand_name',
        'color',
        'chassis_no',
        'engine_no',
        'car_accessories'
    ];


    // ----------------------------------------------------------------
    // -------------------------- Relations ---------------------------
    // ----------------------------------------------------------------

    public function organizations()
    {
        return $this->belongsTo(Organization::class, 'o_id', 'id');
    }

    public function vehiclesType()
    {
        return $this->belongsTo(VehicleType::class, 'v_type_id', 'id');
    }

    public function vehiclesTypes()
    {
        return $this->belongsTo(VehicleType::class, 'v_type_id', 'id');
    }


    // ----------------------------------------------------------------
    // ------------------ Accessors & Mutator -------------------------
    // ----------------------------------------------------------------

    /**
     * Get the front picture of the vehicle.
     *
     * @param  string  $value
     * @return string|null
     */
    public function getFrontPicAttribute($value)
    {
        if (filter_var($value, FILTER_VALIDATE_URL)) {
            $value = $this->attributes['front_pic'];
        } else {
            $value = asset('uploads/vehicles/placeholder.jpg');
        }
        return $value;
        // return $this->attributes['front_pic'] ? asset('uploads/vehicles/' . $this->attributes['front_pic']) : asset('uploads/vehicles/placeholder.jpg');
    }

    /**
     * Get the front picture name of the vehicle.
     *
     * @param  string  $value
     * @return string|null
     */
    public function getFrontPicNameAttribute()
    {
        $url = $this->attributes['front_pic'] ?? null;

        // Extract the image name from the URL if it's present
        if ($url && filter_var($url, FILTER_VALIDATE_URL)) {
            $path = parse_url($url, PHP_URL_PATH);
            $name = basename($path);

            return $name;
        }

        // Return the simple name if it's already present
        return $this->attributes['front_pic'] ?? null;

        // return $this->attributes['front_pic'];
    }

    /**
     * Get the back picture of the vehicle.
     *
     * @param  string  $value
     * @return string|null
     */
    public function getBackPicAttribute($value)
    {
        if (filter_var($value, FILTER_VALIDATE_URL)) {
            $value = $this->attributes['front_pic'];
        } else {
            $value = asset('uploads/vehicles/placeholder.jpg');
        }
        return $value;
        // return $this->attributes['back_pic'] ? asset('uploads/vehicles/' . $this->attributes['back_pic']) : asset('uploads/vehicles/placeholder.jpg');
    }

    /**
     * Get the back picture of the vehicle.
     *
     * @param  string  $value
     * @return string|null
     */
    public function getBackPicNameAttribute()
    {
        $url = $this->attributes['back_pic'] ?? null;

        // Extract the image name from the URL if it's present
        if ($url && filter_var($url, FILTER_VALIDATE_URL)) {
            $path = parse_url($url, PHP_URL_PATH);
            $name = basename($path);

            return $name;
        }

        // Return the simple name if it's already present
        return $this->attributes['back_pic'] ?? null;
        // return $this->attributes['back_pic'];
    }

    /**
     * Get the number plate picture of the vehicle.
     *
     * @param  string  $value
     * @return string|null
     */
    public function getNumberPicAttribute($value)
    {
        if (filter_var($value, FILTER_VALIDATE_URL)) {
            $value = $this->attributes['front_pic'];
        } else {
            $value = asset('uploads/vehicles/placeholder.jpg');
        }
        return $value;
        // return $this->attributes['number_pic'] ? asset('uploads/vehicles/' . $this->attributes['number_pic']) : asset('uploads/vehicles/placeholder.jpg');
    }

    /**
     * Get the number plate picture of the vehicle.
     *
     * @param  string  $value
     * @return string|null
     */
    public function getNumberPicNameAttribute()
    {
        $url = $this->attributes['number_pic'] ?? null;

        // Extract the image name from the URL if it's present
        if ($url && filter_var($url, FILTER_VALIDATE_URL)) {
            $path = parse_url($url, PHP_URL_PATH);
            $name = basename($path);

            return $name;
        }

        // Return the simple name if it's already present
        return $this->attributes['number_pic'] ?? null;
        // return $this->attributes['number_pic'];
    }

    /**
     * Get the registration date of the vehicle.
     *
     * @param  string  $value
     * @return string|null
     */
    public function getRegDateAttribute()
    {
        return $this->attributes['reg_date'] ? date('d-m-Y', strtotime($this->attributes['reg_date'])) : asset('uploads/vehicles/placeholder.jpg');
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
