<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    protected $table = 'students';

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
        'type',
        'status',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    protected $cast = [
        // 
    ];
}
