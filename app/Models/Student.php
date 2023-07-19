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
        'name',
        'phone',
        'email',
        'house_no',
        'street_no',
        'town',
        'city_id',
        'pickup_address',
        'pickup_city_id',
        'lattitude',
        'longitude',
        'further_type',
        'image',
        'additional_detail',
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
     * Undocumented function
     *
     * @return void
     */
    public function guardians()
    {
        return $this->belongsToMany(Guardian::class, 'student_guardians', 'student_id', 'guardian_id');
    }
}
