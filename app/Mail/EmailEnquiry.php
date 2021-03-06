<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class EmailEnquiry extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $demo;
    public function __construct($demo)
    {

        $this->demo = $demo;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $mail = $this->from($this->demo->sender)
                    ->subject('EW DMS Enquiries') 
                    ->view('mails.email-template');
        
        if(!empty($this->demo->attachment)){
            foreach($this->demo->attachment as $attachment){
                $mail->attach(public_path('images/enquiries/').$this->demo->id."_".$attachment->getClientOriginalName(), [
                    'as' => $this->demo->id."_".$attachment->getClientOriginalName(),
                    'mime' => $attachment->getClientmimeType(),
                ]);
            }            
        }                      
        return $mail;
    }
}
