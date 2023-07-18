<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Guardian extends Model
{
    use HasFactory;
    protected $table = 'guardians';

    protected $fillable = [
        'request_id',
        'student_id',
        'employee_id',
        'name',
        'phone',
        'email',
        'image',
        'house_no',
        'street_no',
        'town',
        'additional_detail',
        'city_id',
        'cnic',
        'cnic_front',
        'cnic_back',
        'guardian_code',
        'pickup_address',
        'pickup_city_id',
        'lattitude',
        'longitude',
        'relation',
        'status',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    protected $cast = [
        // 
    ];
    
    /**
     * Student relation with request
     *
     * @return void
     */
    public function requests()
    {
        return $this->hasMany(Request::class);
    }

    /**
     * Student relation with passenger
     *
     * @return void
     */
    public function passenger()
    {
        return $this->belongsTo(Passenger::class);
    }
}
