<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class Enquiries extends Model
{
    protected $table = 'enquiries';

    protected $fillable = ['name','email','title','content','attachment'];
    
    
}
