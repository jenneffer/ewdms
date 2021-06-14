<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
// share
use \App\User;
use \App\Document;
use Illuminate\Support\Facades\View;
use DB;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);
        // requests number
        $numReq = count(User::where('status',false)->get());
        View::share('requests',$numReq);
        // trash noti
        $trash = count(Document::where('isExpire',2)->get());
        View::share('trashfull',$trash);

        //nda submission
        $numSubmission = count(DB::table('users')
                        ->join('signed_nda', 'signed_nda.user_id', '=', 'users.id')            
                        ->select('users.*', 'signed_nda.file_name')
                        ->where('nda_status',false)
                        ->get());      
        View::share('submissions',$numSubmission);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
