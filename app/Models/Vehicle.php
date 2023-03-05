<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Vehicle extends Model
{
    use HasFactory , SoftDeletes;
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
        'status',
        'created_at',
        'updated_at',
        'deleted_at'
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
}
