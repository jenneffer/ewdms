<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class Role extends Model
{
    protected $table = 'roles';

    protected $fillable = ['name','guard_name'];
    

    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    public function permissions()
    {
        return $this->belongsToMany(Permission::class);
    }

    public static function findID($name)
    {
        $id = Role::where('name', '=', $name)->pluck('id');

        return $id[0]?? '--';
    }
    
}
