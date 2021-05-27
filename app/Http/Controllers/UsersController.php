<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
// for displaying data
use App\Department;
// for assigning roles
use Spatie\Permission\Models\Role;
use DB;

class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

     public function __construct() {
        return $this->middleware(['auth','permission:manage']);
        // $this->middleware(['auth','admin']);
     }

    public function index()
    {
        // $users = User::where('status',true)->get();
        if (auth()->user()->hasRole('Admin'))
        {
          $users = User::where('status',true)->get();
        }
        elseif (auth()->user()->hasRole('Moderator'))
        {
          // $d = auth()->user()->department_id;
          // $users = User::where('status',true)->where('department_id',$d)->where('id','!=',auth()->user()->id)->get();
          $users = User::where('status',true)->where('id','!=',auth()->user()->id)->get();
        }
        else
        {
          return redirect('/dashboard')->with('error','Access Denied!');
        }
        // get all dept
        $depts = Department::all();
        // get roles
        if (auth()->user()->hasRole('Admin'))
        {          
          // $roles = Role::where('name','!=','Admin')->get();
          $roles = Role::all();          
        }
        else
        {
          $roles = Role::where('name','!=','Admin')->where('name','!=','Moderator')->get();
        }

        return view('users.index',compact('users','depts','roles'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
          'name' => 'required|string|max:255',
          'email' => 'required|string|email|max:255|unique:users',
          'password' => 'required|string|min:6|confirmed',
          // 'department_id' => 'required',
          'role' => 'required',
        ]);

        // $user = User::create($request->only('name','email','password','department_id'));
        $user = new User;
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->password = $request->input('password');
        // $user->department_id = $request->input('department_id');
        $user->role = $request->input('role');
        $user->status = true;
        $user->save();

        $role = $request->input('role');
        $role_r = Role::where('id',$role)->firstOrFail();
        $user->assignRole($role_r);

        \Log::addToLog('Created a user');

        return redirect('/users')->with('success','User Added');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = User::find($id);
        return view('users.show')->with('user',$user);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = User::findOrFail($id);
        $depts = Department::all();
        // get roles
        if (auth()->user()->hasRole('Admin'))
        {
          // $roles = Role::where('name','!=','Admin')->get();
          $roles = Role::all();
        }
        else
        {
          $roles = Role::where('name','!=','Admin')->where('name','!=','Moderator')->get();
        }
        return view('users.edit',compact('user','depts','roles'));
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
          'name' => 'required|string|max:255',
          'email' => 'required|string|email|max:255',
          // 'department_id' => 'required',
          'role' => 'required',
        ]);
        $user = User::findOrFail($id);
        $user->name = $request->input('name');
        $user->email = $request->input('email');

        //get role id to be updated in user table
        $role_id = DB::table("roles")->where("name", $request->input('role'))->pluck('id')->first();  
        $user->role = $role_id;
        $user->save();
       
        if ($request->input('role') !== $user->roles->pluck('name')->implode(' ')) {
          // first remove current role
          $user->removeRole($user->roles->pluck('name')->implode(' '));
          // then assign the new role
          $user->assignRole($request->input('role'));
        }

        \Log::addToLog('User ID '.$id.' was edited');

        return redirect('/users')->with('success','User Data Updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        \Log::addToLog('User ID : '.$id.' was deleted');

        return redirect('/users')->with('success', 'User deleted');
    }

    public function deactivate(Request $request){
      $user_id = $request->user_id;
      $user = User::findOrFail($user_id);
      $user->status = 0;
      $user->save();
      \Log::addToLog('User ID : '.$user_id.' was deactivated');

      return response()->json(['success' => true, 'msg'=>'User ".$user_id." was deactivated', 'url'=> '/users']);
    }

}
