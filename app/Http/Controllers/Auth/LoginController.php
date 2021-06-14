<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    // protected $redirectTo = '/home';
    protected function redirectTo()
    {        
        
        if(auth()->user()->hasRole('Admin')) {
            return '/dashboard';
        }
        elseif (auth()->user()->hasRole('Moderator')) {
            return '/users';
        }
        else { //user 
            //redirect to first_time_login if NDA submission is 0
            if(auth()->user()->nda_status == 0){
                return '/first_time_login';
            }else{
                return '/documents';
            }            
        }       
    }

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }
}
