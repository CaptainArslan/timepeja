<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Vehicle extends Model
{
    use HasFactory;
    use SoftDeletes;

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
    protected $table = 'vehicles';

    public function organizations()
    {
        return $this->belongsTo(Organization::class, 'o_id', 'id');
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
    public function getVehFrontPicAttribute($value)
    {
        return $this->attributes['front_pic'] ? asset('uploads/vehicles/' . $this->attributes['front_pic']) : null;
    }
}
