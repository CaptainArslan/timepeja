<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Schedule extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'schedules';

    protected $fillable = [
        'o_id',
        'u_id',
        'route_id',
        'v_id',
        'd_id',
        'date',
        'time',
        'status'
    ];
    
    public function organizations()
    {
        return $this->belongsTo(Organization::class, 'o_id', 'id');
    }
    public function routes()
    {
        return $this->belongsTo(Route::class, 'route_id', 'id');
    }
    public function vehicles()
    {
        return $this->belongsTo(Vehicle::class, 'v_id', 'id');
    }
    public function drivers()
    {
        return $this->belongsTo(Driver::class, 'd_id', 'id');
    }
    public function users()
    {
        return $this->belongsTo(User::class, 'u_id', 'id');
    }
}
