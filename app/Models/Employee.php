<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;
    protected $table = 'employees';

    public const EMPLOYEE_STATUS_SCHOOL = 'school';
    public const EMPLOYEE_STATUS_COLLEGE = 'college';
    public const EMPLOYEE_STATUS_UNIVERSITY = 'university';

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
    ];

    protected $cast = [
        'request_id' => 'integer',
        'city_id' => 'integer',
        'pickup_city_id' => 'integer',
        'lattitude' => 'float',
        'longitude' => 'float',
        'status' => 'boolean',
    ];

    // ------------------ Relationships ------------------
    public function requests()
    {
        return $this->hasMany(Request::class);
    }

    public function guardians()
    {
        return $this->belongsToMany(Guardian::class, 'employee_guardian', 'employee_id', 'guardian_id');
    }
}
