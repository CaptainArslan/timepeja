<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Request extends Model
{
    use HasFactory;
    use SoftDeletes;
    use Notifiable;

    protected $table = 'requests';

    public const STUDENT = 'student';
    public const EMPLOYEE = 'employee';
    public const STUDENT_GUARDIAN = 'student_guardian';
    public const EMPLOYEE_GUARDIAN = 'employee_guardian';

    public const STATUS_PENDING = 'pending';
    public const STATUS_APPROVED = 'approved';
    public const STATUS_DISAPPROVED = 'disapproved';
    public const STATUS_MEET_PERSONALLY = 'meet-personally';

    public const SCHOOL = 'school';
    public const COLLEGE = 'college';
    public const UNIVERSITY = 'university';

    public const MAX_GUARDIAN_ALLOWED = 3;

    /**
     * Fillabel for mass assignment
     *
     * @var array
     */
    protected $fillable = [
        'organization_id',
        'parent_request_id',
        'type',
        'student_type',
        'gender',
        'name',
        'phone',
        'passenger_id',
        'email',
        'address',
        'pickup_address',
        'house_no',
        'street_no',
        'town',
        'lattitude',
        'longitude',
        'pickup_city_id',
        'additional_detail',
        'roll_no',
        'class',
        'section',
        'qualification',
        'batch_year',
        'degree_duration',
        'discipline',
        'employee_comp_id',
        'designation',
        'profile_card',
        'cnic_no',
        'cnic_front_image',
        'cnic_back_image',
        'relation',
        'guardian_code',
        'route_id',
        'transport_start_date',
        'transport_end_date',
        'created_by',
        'created_user_id',
        'status',
    ];

    protected $casts = [
        'child_requests_count' => 'integer',
        'organization_id' => 'integer',
        'passenger_id' => 'integer',
        'parent_request_id' => 'integer',
        'route_id' => 'integer',
        'pickup_city_id' => 'integer',
    ];

    protected $hidden = [
        'created_by',
        'created_user_id',
        'deleted_at',
    ];

    /**
     * get the request organization
     *
     * @return void
     */
    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    /**
     * get the request passenger
     *
     * @return void
     */
    public function passenger()
    {
        return $this->belongsTo(Passenger::class);
    }

    // /**
    //  * Undocumented function
    //  *
    //  * @return void
    //  */
    // public function student()
    // {
    //     return $this->belongsTo(Student::class);
    // }

    // /**
    //  * Undocumented function
    //  *
    //  * @return void
    //  */
    // public function employee()
    // {
    //     return $this->belongsTo(Employee::class);
    // }

    // /**
    //  * Undocumented function
    //  *
    //  * @return void
    //  */
    // public function guardians()
    // {
    //     // return $this->hasMany(Guardian::class);
    //     return $this->belongsToMany(Guardian::class, 'request_guardian', 'request_id', 'guardian_id');
    // }

    /**
     * get the request relation
     *
     * @return void
     */
    public function route()
    {
        return $this->belongsTo(Route::class);
    }

    /**
     * GET ALL THE CHILD REQUESTS
     *
     * @return void
     */
    public function childRequests()
    {
        return $this->hasMany(Request::class, 'parent_request_id');
    }

    /**
     * Get the city of the request
     *
     * @return void
     */
    public function city()
    {
        return $this->belongsTo(City::class);
    }
}
