<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrganizationType extends Model
{
    use HasFactory;
    use SoftDeletes;

    /**
     * array for fillable
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'desc',
        'u_id',
        'status'
    ];


    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'u_id' => 'integer',
    ];
}
