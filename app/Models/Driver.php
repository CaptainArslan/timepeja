<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Driver extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'o_id',
        'u_id',
        'name',
        'email',
        'password',
        'phone',
        'cnic',
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
    ];

    public function organizations()
    {
        return $this->belongsTo(Organization::class, 'o_id', 'id');
    }
}
