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
    public const STATUS_APPROVE = 'approve';
    public const STATUS_DISAPPROVE = 'disapprove';
    public const STATUS_MEET_PERSONALLY = 'meet-personally';
    
    public const SCHOOL = 'school';
    public const COLLEGE = 'college';
    public const UNIVERSITY = 'university';

    /**
     * Fillabel for mass assignment
     *
     * @var array
     */
    protected $fillable = [
        'organization_id',
        'passenger_id',
        'type',
        'student_id',
        'roll_no',
        'class',
        'section',
        'qualification',
        'batch_year',
        'degree_duration',
        'employee_id',
        'descipline',
        'designation',
        'profile_card',
        'route_id',
        'transport_start_date',
        'transport_end_date',
        'status',
    ];

    protected $cast = [
        // 
    ];

    /**
     * Undocumented function
     *
     * @return void
     */
    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    /**
     * Undocumented function
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
    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    /**
     * Undocumented function
     *
     * @return void
     */
    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    /**
     * Undocumented function
     *
     * @return void
     */
    public function guardians()
    {
        // return $this->hasMany(Guardian::class);
        return $this->belongsToMany(Guardian::class, 'request_guardian', 'request_id', 'guardian_id');
    }

    /**
     * Undocumented function
     *
     * @return void
     */
    public function route()
    {
        return $this->belongsTo(Route::class);
    }
}
