<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Financials extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = ['user_id','org_trail_days', 'org_start_date', 'org_end_date'];
}
