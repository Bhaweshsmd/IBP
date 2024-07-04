<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class BasicEmail extends Mailable
{
    use Queueable, SerializesModels;
    public $details;

    public function __construct($details)
    {
        $this->details=$details;
    }

    public function build()
    {     
        $this->subject($this->details['subject'])->from('ibp@isidelbeachpark.com','Isidel Beach Park')->markdown('basicmail');
    }
}
