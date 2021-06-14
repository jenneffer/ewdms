<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class NDA extends Model
{
    protected $table = 'signed_nda';

    protected $fillable = [
    	'user_id', 'file_name'
    ];
}
