<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    protected $table = 'students';

    public const STUDENT_TYPE_SCHOOL = 'school';
    public const STUDENT_TYPE_COLLEGE = 'college';
    public const STUDENT_TYPE_UNIVERSITY = 'university';

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

    /**
     * Undocumented function
     *
     * @return void
     */
    public function guardians()
    {
        return $this->belongsToMany(Guardian::class, 'employee_guardian', 'employee_id', 'guardian_id');
    }
}
