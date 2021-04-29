<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class Category extends Model
{
    protected $table = 'category';

    protected $fillable = ['name'];

    public function documents() {
        return $this->belongsToMany('App\Document');
    }

    public function subcat() {

        return $this->hasMany('App\Category','parent_id','id') ;

    }

    public static function findName($id)
    {
        $name = Category::where('id', '=', $id)->pluck('name');

        return $name[0]?? '--';
    }

    public static function findParentId($id)
    {
        $parent_id = Category::where('id', '=', $id)->pluck('parent_id');

        return $parent_id[0];
    }

    public static function findCat($id)
    {
        $parent_id = Category::where('id', '=', $id)->pluck('name','id');

        return $parent_id[0];
    }

    public static function findSubCatId($id)
    {
        $parent_id = Category::where('id', '=', $id)->pluck('child_id');

        return $parent_id[0];
    }

    
}
