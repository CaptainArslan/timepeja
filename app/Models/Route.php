<?php

namespace App\Models;

use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Route extends Model
{
    use HasFactory;
    use SoftDeletes;

    public const STATUS_ACTIVE = true;
    public const STATUS_INACTIVE = false;

    protected $fillable = [
        'organization_id',
        'name',
        'number',
        'from',
        'to',
        'status',
        'way_points',
    ];

    protected $casts = [
        'organization_id' => 'integer',
        'number' => 'integer',
        'status' => 'boolean',
        'from ' => 'array',
        'to' => 'array',
        'way_points' => 'array',
    ];

    protected $hidden = [
        'deleted_at'
    ];


    // ------------------- Relationships --------------------------------
    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    public function passengers()
    {
        return $this->belongsToMany(Passenger::class, 'passenger_route');
    }

    // ------------------- Accessors and Mutators --------------------------------
    protected function name(): Attribute
    {
        return new Attribute(
            get: fn($value) => ucwords(strtolower($value)),
            set: fn($value) => ucwords(strtolower($value)),
        );
    }

    protected function from(): Attribute
    {
        return new Attribute(
            get: fn($value) => ucwords(strtolower($value)),
            set: fn($value) => ucwords(strtolower($value)),
        );
    }

    protected function to(): Attribute
    {
        return new Attribute(
            get: fn($value) => ucwords(strtolower($value)),
            set: fn($value) => ucwords(strtolower($value)),
        );
    }

    protected function createdAt(): Attribute
    {
        return new  Attribute(
            get: fn($value) => Carbon::parse($value)->format('Y-m-d'),
        );
    }

    protected function updatedAt(): Attribute
    {
        return new Attribute(
            get: fn($value) => Carbon::parse($value)->format('Y-m-d'),
        );
    }


    // ------------------- Scopes --------------------------------
    public function scopeByOrganization($query, $organization_id)
    {
        return $query->when($organization_id, function ($query) use ($organization_id) {
            return $query->where('o_id', $organization_id);
        }, function ($query) {
            return $query;
        });
    }
}
