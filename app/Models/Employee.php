<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;
    protected $table = 'employees';

    protected $fillable = [
        'request_id',
        'name',
        'phone',
        'email',
        'image',
        'house_no',
        'street_no',
        'town',
        'additional_detail',
        'city_id',
        'pickup_address',
        'pickup_city_id',
        'lattitude',
        'longitude',
        'status',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    protected $cast = [
        // 
    ];
}
