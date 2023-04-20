<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Financials extends Model
{
    use HasFactory;
    use SoftDeletes;

    /**
     * Table name
     *
     * @var string
     */
    protected $table = 'financials';


    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'o_id' => 'integer',
        'u_id' => 'integer',
    ];

    /**
     * the attributes that are mass assignable
     *
     * @var array
     */
    protected $fillable = [
        'u_id',
        'org_trail_days',
        'org_start_date',
        'org_end_date'
    ];


    
}
