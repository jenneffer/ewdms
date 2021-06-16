<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\NDA;
use App\User;
use App\Mail\Email;
use Illuminate\Support\Facades\Mail; 
use Illuminate\Support\Facades\File;
use Carbon\Carbon;
use DB;


class NDAController extends Controller
{
    public function __construct(){
        return $this->middleware(['auth']);
    }

    public function index(){
        $submission = DB::table('users')
            ->join('signed_nda', 'signed_nda.user_id', '=', 'users.id')            
            ->select('users.*', 'signed_nda.file_name')
            ->where('nda_status',false)
            ->get();        

        $approved_submission = DB::table('users')
            ->join('signed_nda', 'signed_nda.user_id', '=', 'users.id')            
            ->select('users.*', 'signed_nda.file_name')
            ->where('nda_status',true)
            ->get();     

        return view('nda.index',compact('submission','approved_submission'));
        
    }

    public function downloadNDA(){
        $file= public_path(). "/images/EW Non Disclosure Agreement.pdf";

        $headers = array(
                'Content-Type: application/pdf',
                );
        
        \Log::addToLog('User ID : '.auth()->user()->id.' has downloaded NDA form');

        return \Response::download($file, 'EW Non Disclosure Agreement.pdf', $headers);
    }

    public function store(Request $request){      
        $this->validate($request, [                        
        // 'nda_form' => 'required|file',    
        'nda_form' => 'required|mimetypes:application/pdf'        
        
        ]);  

        //check is submission exist
        $data = NDA::where('user_id', auth()->user()->id)->exists();
        // don't allow overlapping submission
        if($data){ 
            //auto logout and redirect to login page
            \Auth::logout();
            return redirect('/login')->with('success','You have made a previous submission.Please wait for the approval to continue to access the system.');;
        }
        else{
            if($request->hasFile('nda_form')) {               
                $nda = new NDA;
                $rename_file = time().rand(0, 1000)."_".$request->nda_form->getClientOriginalName();
                $nda->file_name = $rename_file;  
                $nda->file_type = $request->nda_form->getClientMimeType(); 
                $nda->user_id = auth()->user()->id;                                       
                $nda->save();    
                $path = $request->nda_form->move(public_path('/images/NDA Signed'), $rename_file);

                //update the last_login_at in user table
                // $user = User::find(auth()->user()->id);
                // if($user) {
                //     $user-> = Carbon::now()->toDateTimeString();
                //     $user->save();
                // }

                //sent email to administrator/moderator of the submission
                $objDemo = new \stdClass();        
                $objDemo->sender = auth()->user()->email;      
                $objDemo->senderName = auth()->user()->name;     
                $objDemo->receiver = 'business@european-wellness.com';
                $objDemo->receiverName = 'Administrator';
                $objDemo->subject = 'NDA Submission From ' .auth()->user()->name;
                $objDemo->template = 'mails.nda-submit-template';
                
                \Log::addToLog('New NDA Submission from  User ID : '.auth()->user()->id);
                
                Mail::to('business@european-wellness.com')->send(new Email($objDemo));

                //auto logout and redirect to login page
                \Auth::logout();
                return redirect('/login')->with('success','Your document has been sent.Please wait for the approval to continue to access the system.');
                            
            }  

        }
        
    }

    public function update(Request $request, $id){
        
        $user = User::findOrFail($id);
        $user->nda_status = true;
        $user->save();

        \Log::addToLog('NDA Submission from  User ID : '.$id.' was Approved');

        //send email to user the login link - give system link and his credential.
        $user_email = User::where('id', $id)->pluck('email')->first();  
        $user_name = User::where('id', $id)->pluck('name')->first();        
        //testing mail send to notify the NDA form approve sudah
        $objDemo = new \stdClass();        
        $objDemo->sender = 'business@european-wellness.com';
        $objDemo->receiver = $user_email;
        $objDemo->receiverName = $user_name;
        $objDemo->subject = 'NDA Submission Approved';
        $objDemo->template = 'mails.nda-approve-template';
        
        Mail::to($user_email)->send(new Email($objDemo));
        
        return redirect('/nda')->with('success','NDA Approved');
        
    }

    public function reject(Request $request){
        //reject invalid NDA form. Sent email to inform user to resend, check column last_login_at in users table - kasi null balik value supaya redirected to first user login page.. kasi dia link untuk login balik tu system           
        // delete the file on disk
        $filename = NDA::where('user_id', $request->id)->pluck('file_name')->first(); //get the file name from db
        $image_path = public_path()."/images/NDA Signed/$filename";  // Value is not URL but directory file path        
        if(File::exists($image_path)) {
            File::delete($image_path);
        }
        // delete db record
        NDA::where('user_id',$request->id)->delete();     

        // update nda_status and last_login_at in user table, 
        $user = User::findOrFail($request->id);
        $user->nda_status = false;
        // $user->last_login_at = null;
        $user->save();
        // notify user via email, to reupload nda
        $objDemo = new \stdClass();        
        $objDemo->sender = 'business@european-wellness.com';
        $objDemo->receiver = $user->email;
        $objDemo->receiverName = $user->name;
        $objDemo->subject = 'NDA Submission Rejected';
        $objDemo->template = 'mails.nda-reject-template';
        
        Mail::to($user->email)->send(new Email($objDemo));

        \Log::addToLog('NDA Submission by user ID ' . $request->id . ' was deleted');
    
        return redirect('/nda')->with('success', 'Email sent to user and NDA Submission was Deleted!');
    }

    public function viewNda($file_name){
        // \Log::addToLog('NDA Submission by user ID ' . $request->id . ' was deleted');
        return view('nda.view_nda_pdf', compact('file_name'));
    }
    
}
