<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Category;

class RolesController extends Controller
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
        // for combo boxes
        // $roles = Role::pluck('name','id')->all();
        $roles = Role::all();
        $permissions = Permission::pluck('name','id')->all();
        $category = Category::where('parent_id', 0)->pluck('name', 'id');
        // for table
        // $rs = Role::all();

        return view('roles.index',compact('roles','permissions','category'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // abort_if(Gate::denies('agent_create'), Response::HTTP_FORBIDDEN, '403 Forbidden'); - check permissions
        $permissions = Permission::all();  
              
        return view('roles.create', compact('permissions'));
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
            'name' => 'required|max:15|unique:roles,name',
            'permissions' => 'required',

        ]);       
        
        $input = $request->except(['permissions']);
        $permissions = $request['permissions'];
        
        // // save role name
        // $role->fill($input)->save();
        $role = Role::create($input);

        $p_all = Permission::all();
        foreach ($p_all as $p) {
            $role->revokePermissionTo($p);
        }

        foreach ($permissions as $permission) {
            // get corresponding form permission in db
            $p = Permission::where('id',$permission)->firstOrFail();
            $role->givePermissionTo($p);
        }

        \Log::addToLog('New role ' . $request->input('name') . ' was added');

        return redirect('/roles')->with('success','Role and Permissions Added');
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
    // public function edit($id)
    public function edit(Role $role)
    {
        // abort_if(Gate::denies('role_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        // $role = Role::findOrFail($id);
        $permissions = Permission::all();
        //get the permissions of this particular role
        $role->load('permissions');

        return view('roles.edit',compact('role','permissions'));
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
            'name' => 'required|max:15|unique:roles,name,'.$id,
            'permissions' => 'required',

        ]);

        $role = Role::findOrFail($id);

        $input = $request->except(['permissions']);
        $permissions = $request['permissions'];
        
        // save role name
        $role->fill($input)->save();

        $p_all = Permission::all();
        foreach ($p_all as $p) {
            $role->revokePermissionTo($p);
        }

        foreach ($permissions as $permission) {
            // get corresponding form permission in db
            $p = Permission::where('id',$permission)->firstOrFail();
            $role->givePermissionTo($p);
        }

        \Log::addToLog('Role ID ' . $id . ' was edited');

        return redirect('/roles')->with('success','Role Updated');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

}
