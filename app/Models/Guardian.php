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
     * Undocumented function
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
    public function students()
    {
        return $this->belongsToMany(Student::class, 'student_guardians', 'guardian_id', 'student_id');
    }

    /**
     * Undocumented function
     *
     * @return void
     */
    public function employees()
    {
        return $this->belongsToMany(Employee::class, 'employee_guardians', 'guardian_id', 'employee_id');
    }
}
