<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Enquiries;
use App\Mail\EmailEnquiry;
use Illuminate\Support\Facades\Mail; 
class EnquiriesController extends Controller
{
    public function index()
    {
        return view('home');
    }

    public function store(Request $request){
        $this->validate($request, [
            'name' => 'required|string|max:125',
            'email' => 'required|string|max:125',
            'title' => 'required|string|max:125',
            'content' => 'required|string|max:255',
            'file' => 'max:50000',
          ]);
        $enq = new Enquiries;
        $enq->name = $request->input('name');
        $enq->email = $request->input('email');
        $enq->title = $request->input('title');
        $enq->content = $request->input('content');
        
        //store file attachment into folder
        $imageName = [];
        $path = "";        
        if($request->hasFile('attachment')) {
            foreach($request->attachment as $attachment){                
                $imageName[] = $attachment->getClientOriginalName();
                $path = $attachment->move(public_path('/images/enquiries'), $attachment->getClientOriginalName());
            }  
            //concat image name into string    
            $strImageName = implode(",", $imageName);
            $enq->attachment = $strImageName;
        }        
        
        $enq->save();
        \Log::addToLog('New Enquiries, ' . $request->input('title') . ' was recorded');
        
        //testing mail send
        $objDemo = new \stdClass();
        $objDemo->title = $request->input('title');
        $objDemo->content = $request->input('content');
        $objDemo->sender = $request->input('email');
        $objDemo->senderName = $request->input('name');
        $objDemo->attachment = $request->attachment;
        // $objDemo->mimeType = $mimeType;
        $objDemo->receiver = 'EW DMS Administrator';

        Mail::to('business@european-wellness.com')->send(new EmailEnquiry($objDemo));
        
        return redirect('/')->with('success', 'Email has been sent. We will get back to you as soon as possible.');
        
    }
}
