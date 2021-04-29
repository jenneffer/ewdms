<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    protected $table = 'document';
    
    protected $fillable = ['name','url','description'];

    public function user() {
        return $this->belongsTo('App\User');
    }

    public function category(){
        return $this->belongsTo('App\Category');
    }

    public function categories() {
        return $this->belongsToMany('App\Category');
    }
}
