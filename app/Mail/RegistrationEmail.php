<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class RegistrationEmail extends Mailable
{
      public $details;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($details)
    {
        $this->details = $details;
    }
    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {    
        //   $data = array(
        //     'messages' => $this->message
        // );
        // 'settings' => $settings ['user' => auth()->user()]
           return $this->view('email')
                ->subject("testing email ibp")
                ->from('ibp@isidelbeachpark.com');

               
    }
}
