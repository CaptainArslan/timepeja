<?php

namespace App\Models;

use App\Traits\HasOrganization;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Schedule extends Model
{
    use HasFactory;
    use SoftDeletes;
    use HasOrganization;

    public const STATUS_PUBLISHED = 'published';
    public const STATUS_DRAFT = 'draft';
    public const TRIP_STATUS_UPCOMING = 'upcoming';
    public const TRIP_STATUS_INPROGRESS = 'in-progress';
    public const TRIP_STATUS_COMPLETED = 'completed';
    public const TRIP_STATUS_DELAYED = 'delayed';
    public const TRIP_NOTDELAYED = false;
    public const TRIP_ISDELAYED = true;
    public const SCHEDULE_TIME = 15;

    protected $table = 'schedules';

    protected $fillable = [
        'organization_id',
        'route_id',
        'vehicle_id',
        'driver_id',
        'date',
        'time',
        'status',
        'start_time',
        'end_time',
        'is_delayed',
        'trip_status',
        'is_notified',
        'delayed_reason',
    ];

    protected $casts = [
        'organization_id' => 'integer',
        'route_id' => 'integer',
        'vehicle_id' => 'integer',
        'driver_id' => 'integer',
        'is_delayed' => 'boolean',
        'date' => 'string'
    ];


    // ----------------------------------------------------------------
    // ------------------- Relationships ------------------------------
    // ----------------------------------------------------------------
    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    public function route()
    {
        return $this->belongsTo(Route::class);
    }

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function driver()
    {
        return $this->belongsTo(Driver::class);
    }

    // ----------------------------------------------------------------
    // ------------------ Accessors & Mutator -------------------------
    // ----------------------------------------------------------------

    protected function time(): Attribute
    {
        return new Attribute(
            get: fn($value) => Carbon::parse($value)->format('h:i A'),
        );
    }

    protected function createdAt(): Attribute
    {
        return new Attribute(
            get: fn($value) => Carbon::parse($value)->format('Y-m-d'),
        );
    }

    protected function updatedAt(): Attribute
    {
        return new Attribute(
            get: fn($value) => Carbon::parse($value)->format('Y-m-d'),
        );
    }


    // ----------------------------------------------------------------
    // -------------------------- Scopes ------------------------------
    // ----------------------------------------------------------------

    public function scopeIsNotNotified(Builder $query): Builder
    {
        return  $query->where('is_notified', 0);
    }

    public function scopeIsNotified(Builder $query): Builder
    {
        return  $query->where('is_notified', 1);
    }

    public function scopeIsDelayed(Builder $query): Builder
    {
        return  $query->where('is_delayed', 1);
    }
}
