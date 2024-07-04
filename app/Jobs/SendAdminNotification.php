<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\SalonNotifications;
use App\Models\GlobalFunction;
use App\Models\UserNotification;

class SendAdminNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public $title;
    public $description;
    public $type;
    public $user_id;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($title,$description,$type,$user_id)
    {
        $this->title=$title;
        $this->description=$description;
        $this->type=$type;
        $this->user_id=$user_id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // $item = new UserNotification();
        // $item->user_id = $this->user_id;
        // $item->title = $this->title;
        // $item->description = $this->description;
        // $item->notification_type = $this->type;
        // $item->save();
        
        
        $item = new SalonNotifications();
        $item->user_id = $this->user_id;
        $item->title = $this->title;
        $item->description = $this->description;
        $item->notification_type = $this->type;
        $item->save();
        
        
    }
}
