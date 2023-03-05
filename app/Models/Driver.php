<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Driver extends Model
{
    use HasFactory;

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
        'licence_no',
        'licence_no_front_pic',
        'licence_no_back_pic',
        'licence_expiry_date',
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
