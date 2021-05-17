<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Category;

class PermissionsController extends Controller
{
    public function __construct() {
        return $this->middleware(['auth','role:Admin|Moderator']);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $permissions = Permission::pluck('name','id')->all();
        return view('permissions.index',compact('permissions'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'string|required',            
        ]);
        // $permission = Permission::create($request->all());
        $permission = new Permission;
        $permission->name = $request->input('name');
        
        if(!empty($request->input('sub_child_id'))){
            $category_id = $request->input('sub_child_id');
        } 
        elseif(!empty($request->input('child_id'))){
            $category_id = $request->input('child_id');
        } 
        else {
            $category_id = $request->input('parent_id');
        }
        
        $permission->category_id = $category_id;
        // save to db
        $permission->save();

        \Log::addToLog('New permission ' . $request->input('name') . ' was added');

        return redirect('/roles')->with('success', 'Permission Added');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {       
        // $role = Role::findOrFail($id);
        $permissions = Permission::findOrFail($id);
        $category_id = $permissions->category_id;

        if(!empty($category_id)){
            $cat = Category::findorFail($category_id);
            $parent_id = $cat->parent_id;
            $p_cat_name = Category::where("parent_id", 0)->pluck('name', 'id');

            $sub_cat_name=Category::where("parent_id", $parent_id)->where("child_id",0)->pluck('name', 'id');
            $child_list=Category::where("child_id",$category_id)->pluck('name', 'id');

        }else{
            $cat = "";
            $parent_id = "";
            $p_cat_name = "";
            $sub_cat_name = "";
            $child_list = "";
        }
        
        return view('permissions.edit',compact('permissions','sub_cat_name','parent_id','category_id','p_cat_name','cat','child_list'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

        $this->validate($request, [
            'name' => 'string|required',            
        ]);
        
        $permission = Permission::findOrFail($id);
        $permission->name = $request->input('name');

        if(!empty($request->input('sub_child_id'))){
            $category_id = $request->input('sub_child_id');
        } 
        elseif(!empty($request->input('child_id'))){
            $category_id = $request->input('child_id');
        } 
        else {
            $category_id = $request->input('parent_id');
        }
        $permission->category_id = $category_id;
        // save to db
        $permission->save();

        \Log::addToLog('Permission ID ' . $id . ' was edited');

        return redirect('roles')->with('success', 'Permission Updated!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $permission = Permission::find($id);
        $permission->delete();

        \Log::addToLog('Permission ID ' . $id . ' was deleted');

        return redirect('/roles')->with('success', 'Permission Deleted');
    }

    public function getRolePermissionsID($role_id){
        
    } 
}
