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
                    ->view('mails.email-template');
        
        if(!empty($this->demo->attachment)){
            foreach($this->demo->attachment as $attachment){
                $mail->attach(public_path('images/enquiries/').$attachment->getClientOriginalName(), [
                    'as' => $attachment->getClientOriginalName(),
                    'mime' => $attachment->getClientmimeType(),
                ]);
            }            
        }                      
        return $mail;
    }
}
