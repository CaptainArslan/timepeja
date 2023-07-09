<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Requests extends Model
{
    use HasFactory;
    use SoftDeletes;
    use Notifiable;

    protected $table = 'requests';

    public const REQUEST_TYPE_STUDENT = 'student';
    public const REQUEST_TYPE_EMPLOYEE = 'employee';
    public const REQUEST_TYPE_GUARDIAN = 'guardian';

    public const REQUEST_STATUS_PENDING = 'pending';
    public const REQUEST_STATUS_APPROVE = 'approve';
    public const REQUEST_STATUS_DISAPPROVE = 'disapprove';
    public const REQUEST_STATUS_MEET_PERSONALLY = 'meet-personally';

    public const STUDENT_SCHOOL = 'scchool';
    public const STUDENT_COLLEGE = 'college';
    public const STUDENT_UNIVERSITY = 'university';
    public const EMPLOYEE = 'employee';
    public const GUARDIAN = 'guarian';

    /**
     * Fillabel for mass assignment
     *
     * @var array
     */
    protected $fillable = [
        'passenger_id',
        'organization_id',
        'roll_no',
        'class',
        'section',
        'profile_card',
        'descipline',
        'designation',
        'employee_comp_id',
        'route_id',
        'transport_start_time',
        'transport_end_time',
        'qualification',
        'batch_year',
        'degree_duration',
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
     * relation with route
     *
     * @return void
     */
    public function route()
    {
        // return $this->belongsTo(Route::class, 'r_id', 'id');
        return $this->belongsTo(Route::class);
    }

    /**
     * relation with passenger
     *
     * @return void
     */
    public function passenger()
    {
        // return $this->belongsTo(Passenger::class, 'p_id', 'id');
        return $this->belongsTo(Passenger::class);
    }


    /**
     * relation with organization
     *
     * @return void
     */
    public function organization()
    {
        // return $this->belongsTo(Organization::class, 'o_id', 'id');
        return $this->belongsTo(Organization::class);
    }

    /**
     * relation with organization
     *
     * @return void
     */
    public function student()
    {
        // return $this->belongsTo(Organization::class, 'o_id', 'id');
        return $this->belongsTo(Student::class);
    }

    /**
     * relation with organization
     *
     * @return void
     */
    public function employee()
    {
        // return $this->belongsTo(Organization::class, 'o_id', 'id');
        return $this->belongsTo(Employee::class);
    }
    /**
     * relation with organization
     *
     * @return void
     */
    public function guardian()
    {
        // return $this->belongsTo(Organization::class, 'o_id', 'id');
        return $this->belongsTo(Guardian::class);
    }
}
