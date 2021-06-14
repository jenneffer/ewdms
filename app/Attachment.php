<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class Attachment extends Model
{
    protected $table = 'attachment';

    protected $fillable = ['file_name','file_type','enquiries_id'];
    
    
}
