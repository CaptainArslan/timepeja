<?php

namespace App\Models;

use App\Traits\HasOrganization;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Route extends Model
{
    use HasFactory;
    use SoftDeletes;
    use HasOrganization;

    public const STATUS_ACTIVE = true;
    public const STATUS_DEACTIVE = false;

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
            set: fn($value) => strtolower($value),
        );
    }

    protected function from(): Attribute
    {
        return new Attribute(
            set: fn($value) => json_encode($value),
            get: fn($value) => json_decode($value, true)
        );
    }

    protected function to(): Attribute
    {
        return new Attribute(
            set: fn($value) => json_encode($value),
            get: fn($value) => json_decode($value, true)
        );
    }

    protected function status(): Attribute
    {
        return new Attribute(
            get: fn($value) => $value == self::STATUS_ACTIVE ? 'Active' : 'Deactive',
        );
    }


    protected function wayPoints(): Attribute
    {
        return new Attribute(
            set: fn($value) => json_encode($value),
            get: fn($value) => json_decode($value, true)
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
    public function scopeActive($query)
    {
        return $query->where('status', self::STATUS_ACTIVE);
    }

    public function scopeInactive($query)
    {
        return $query->where('status', self::STATUS_DEACTIVE);
    }

    public function scopeSearch($query, $search)
    {
        return $query->where('name', 'like', "%$search%")
            ->orWhere('number', 'like', "%$search%")
            ->orWhere('from', 'like', "%$search%")
            ->orWhere('to', 'like', "%$search%");
    }
}
