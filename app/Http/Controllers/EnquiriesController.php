<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Enquiries;
use App\Attachment;
use App\Mail\Email;
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
            'attachment' => 'max:50000',
          ]);
        $enq = new Enquiries;
        $enq->name = $request->input('name');
        $enq->email = $request->input('email');
        $enq->title = $request->input('title');
        $enq->content = $request->input('content');
        
        $enq->save();
        //store file attachment into folder
        $imageName = [];
        $path = "";        
        if($request->hasFile('attachment')) {

            
            foreach($request->attachment as $attachment){ 
                $att = new Attachment;
                $rename_file = $enq->id."_".$attachment->getClientOriginalName();
                $att->file_name = $rename_file;   
                $att->file_type = $attachment->getClientMimeType();
                $att->enquiries_id = $enq->id; //id from enquiries table                                
                $att->save();    
                $path = $attachment->move(public_path('/images/enquiries'), $rename_file);
            } 
                         
        }                
        
        // \Log::addToLog('New Enquiries, ' . $request->input('title') . ' was recorded');
        
        //testing mail send
        $objDemo = new \stdClass();
        $objDemo->id = $enq->id;
        $objDemo->title = $request->input('title');
        $objDemo->content = $request->input('content');
        $objDemo->sender = $request->input('email');
        $objDemo->senderName = $request->input('name');
        $objDemo->attachment = $request->attachment;
        $objDemo->subject = 'EW DMS Enquiries';
        $objDemo->template = 'mails.email-template';
        // $objDemo->mimeType = $mimeType;
        $objDemo->receiver = 'EW DMS Administrator';

        Mail::to('business@european-wellness.com')->send(new Email($objDemo));
        
        return redirect('/dashboard')->with('success', 'Email has been sent. We will get back to you as soon as possible.');
        
    }
}
