<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
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
    public const STATUS_CANCELLED = 'cancelled';
    public const STATUS_DELETED = 'deleted';
    public const SCHOOL = 'school';
    public const COLLEGE = 'college';
    public const UNIVERSITY = 'university';
    public const MAX_GUARDIAN_ALLOWED = 3;
    public const LIMIT = 3;

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
        'pickup_city',
        'city',
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
        'upload_image'
    ];

    protected $casts = [
        'child_requests_count' => 'integer',
        'organization_id' => 'integer',
        'passenger_id' => 'integer',
        'parent_request_id' => 'integer',
        'route_id' => 'integer',
        'pickup_city_id' => 'integer',
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s',
    ];

    protected $hidden = [
        'created_by',
        'deleted_at',
        'created_user_id',
    ];

    /**
     * get the request organization
     *
     * @return BelongsTo
     */
    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }


    /**
     * @return BelongsTo
     */
    public function passenger(): BelongsTo
    {
        return $this->belongsTo(Passenger::class);
    }


    /**
     * @return BelongsTo
     */
    public function route(): BelongsTo
    {
        return $this->belongsTo(Route::class);
    }

    /**
     * @return HasMany
     */
    public function childRequests(): HasMany
    {
        return $this->hasMany(Request::class, 'parent_request_id');
    }

    /**
     * @return BelongsTo
     */
    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }

    /**
     * @return mixed|string|void
     */
    public function getUserId()
    {
        if ($this->type == self::STUDENT) {
            return $this->roll_no;
        } elseif ($this->type == self::EMPLOYEE) {
            return $this->employee_comp_id;
        } elseif ($this->type == self::STUDENT_GUARDIAN) {
            return 'Student Guardian';
        } elseif ($this->type == self::EMPLOYEE_GUARDIAN) {
            return 'Employee Guardian';
        }
    }

    /**
     * @return mixed|string|void
     */
    public function getUserClassOrDepartment()
    {
        if ($this->type == self::STUDENT) {
            return $this->class;
        } elseif ($this->type == self::EMPLOYEE) {
            return $this->department;
        } elseif ($this->type == self::STUDENT_GUARDIAN) {
            return 'Student Guardian';
        } elseif ($this->type == self::EMPLOYEE_GUARDIAN) {
            return 'Employee Guardian';
        }
    }
}
