<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SubCategory extends Model
{
    protected $table = 'sub_category';

    protected $fillable = ['name', 'parent_cat'];
    //protected $fillable = ['name'];
}
