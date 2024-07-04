<?php

namespace App\Jobs;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Mail\RegistrationEmail;
use App\Mail\BasicEmail;
use Mail;

class SendEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $details;

    public function __construct($details)
    {
        $this->details = $details;
    }

    public function handle()
    {     
        $email = new BasicEmail($this->details);
        Mail::to($this->details['to'])->send($email);
    }
}
