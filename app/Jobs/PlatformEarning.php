<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\GlobalFunction;
use App\Models\PlatformEarningHistory;
use App\Models\PlatformData;
use App\Models\RevenueSetting;
use App\Models\GlobalSettings;



class PlatformEarning implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
     public $earning;
     public $booking_id;
     public $type;
     
    public function __construct($earning,$booking_id,$type)
    {
       $this->earning=$earning;
       $this->booking_id=$booking_id;
       $this->type=$type;
      
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {       
        
            $settings = GlobalSettings::first();
            $revenue_per= RevenueSetting::latest()->first();
            $ibp_revenue = ($revenue_per->ibp_revenue/100)*$this->earning;
            $account_maintenance = ($revenue_per->account_maintenance/100)*$this->earning;
            $technical_support= ($revenue_per->technical_support/100)*$this->earning;
            
            
            
            $platformEarningHistory = new PlatformEarningHistory();
            $platformEarningHistory->earning_number = GlobalFunction::generatePlatformEarningHistoryNumber();
             $platformEarningHistory->type = $this->type;
            $platformEarningHistory->amount = $this->earning;
            $platformEarningHistory->ibp_revenue = $ibp_revenue;
            $platformEarningHistory->account_maintenance = $account_maintenance;
            $platformEarningHistory->technical_support = $technical_support;
            $platformEarningHistory->booking_id = $this->booking_id??'';
            $platformEarningHistory->commission_percentage = $settings->comission;
            $platformEarningHistory->salon_id = 1;
            $platformEarningHistory->save();
            // Increasing total platform earning data
            $platformData = PlatformData::first();
            $platformData->lifetime_earnings = $platformData->lifetime_earnings + $this->earning;
            $platformData->save();
    }
}
