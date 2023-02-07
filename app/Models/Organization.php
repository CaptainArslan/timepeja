<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\TransportManager;

class Organization extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = ['name', 'user_id', 'branch_name', 'org_type ', 'email ', 'phone', 'address', 'head_name'];

     /**
     * manager relation function with manager
     *
     * @return void
     */
    public function manager()
    {
        return $this->hasOne(TransportManager::class, 'org_id', 'id');
    }
}
