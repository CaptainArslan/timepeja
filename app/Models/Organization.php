<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Organization extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'name',
        'branch_name',
        'email',
        'phone',
        's_id',
        'c_id',
        'address',
        'head_name',
        'head_email',
        'head_phone',
        'head_address',
        'status',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    /**
     * manager relation function with manager
     *
     * @return void
     */
    public function manager()
    {
        return $this->belongsTo(Manager::class, 'id', 'o_id');
    }

    public function city()
    {
        return $this->belongsTo(City::class, 'c_id', 'id');
    }

    public function state()
    {
        return $this->belongsTo(State::class, 's_id', 'id');
    }
}
