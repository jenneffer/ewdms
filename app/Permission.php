<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class Permission extends Model
{
    protected $table = 'permissions';

    protected $fillable = ['name','guard_name'];
    

    public function roles() {
        return $this->belongsToMany(Role::class);
    }
    
}
