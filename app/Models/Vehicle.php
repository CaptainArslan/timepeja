<?php

namespace App\Models;

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

    protected $fillable = [
        'organization_id',
        'vehicle_type_id',
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
        'organization_id' => 'integer',
        'vehicle_type_id' => 'integer',
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


    // -------------------------- Relations ---------------------------
    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    public function vehiclesType()
    {
        return $this->belongsTo(VehicleType::class);
    }

    public function locations()
    {
        return $this->hasMany(Location::class, 'vehicle_id', 'id');
    }

    public function trips()
    {
        return $this->hasMany(Trip::class, 'vehicle_id', 'id');
    }

    // ------------------ Accessors & Mutator -------------------------
    public function getFrontPicAttribute($value)
    {
        if (filter_var($value, FILTER_VALIDATE_URL)) {
            $value = $this->attributes['front_pic'];
        } else {
            $value = asset('uploads/vehicles/placeholder.jpg');
        }
        return $value;
    }

    public function getFrontPicNameAttribute()
    {
        $url = $this->attributes['front_pic'] ?? null;

        if ($url && filter_var($url, FILTER_VALIDATE_URL)) {
            $path = parse_url($url, PHP_URL_PATH);
            $name = basename($path);

            return $name;
        }
        return $this->attributes['front_pic'] ?? null;
    }

    public function getBackPicAttribute($value)
    {
        if (filter_var($value, FILTER_VALIDATE_URL)) {
            $value = $this->attributes['back_pic'];
        } else {
            $value = asset('uploads/vehicles/placeholder.jpg');
        }
        return $value;
    }

    public function getBackPicNameAttribute()
    {
        $url = $this->attributes['back_pic'] ?? null;

        if ($url && filter_var($url, FILTER_VALIDATE_URL)) {
            $path = parse_url($url, PHP_URL_PATH);
            $name = basename($path);

            return $name;
        }

        return $this->attributes['back_pic'] ?? null;
    }

    public function getNumberPicAttribute($value)
    {
        if (filter_var($value, FILTER_VALIDATE_URL)) {
            $value = $this->attributes['number_pic'];
        } else {
            $value = asset('uploads/vehicles/placeholder.jpg');
        }
        return $value;
    }

    public function getNumberPicNameAttribute()
    {
        $url = $this->attributes['number_pic'] ?? null;

        if ($url && filter_var($url, FILTER_VALIDATE_URL)) {
            $path = parse_url($url, PHP_URL_PATH);
            return basename($path);
        }
        return $this->attributes['number_pic'] ?? null;
    }

    public function getRegDateAttribute()
    {
        return $this->attributes['reg_date'] ? date('d-m-Y', strtotime($this->attributes['reg_date'])) : asset('uploads/vehicles/placeholder.jpg');
    }
}
