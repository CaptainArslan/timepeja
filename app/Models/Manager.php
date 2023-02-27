<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Manager extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [ 'id', 'u_id', 'o_id', 'name', 'email', 'phone', 'pic', 'address', 'created_at', 'updated_at', 'deleted_at'];

    protected $table = 'managers';
    public function organizations()
    {
        return $this->hasOne(Organization::class, 'o_id', 'id');
    }
}
