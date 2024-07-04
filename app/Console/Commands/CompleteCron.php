<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Models\Bookings;
use App\Models\Coupons;
use App\Models\GlobalFunction;
use App\Models\Salons;
use App\Models\Users;
use App\Models\UserWalletStatements;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Models\Constants;
use App\Models\GlobalSettings;
use App\Models\PlatformData;
use App\Models\PlatformEarningHistory;
use App\Models\SalonEarningHistory;
use App\Models\SalonPayoutHistory;
use App\Models\SalonReviews;
use App\Models\SalonWalletStatements;
use Carbon\Carbon;
use Mockery\Generator\StringManipulation\Pass\ConstantsPass;
use PHPUnit\TextUI\XmlConfiguration\Constant;
use Symfony\Component\VarDumper\Caster\ConstStub;
use App\Models\Services;
use App\Jobs\SendNotification;
use App\Models\SalonBookingSlots;
use App\Models\EmailTemplate;
use App\Models\Taxes;
use App\Models\Fee;
use App\Jobs\SendEmail;
use App\Models\RevenueSetting;
use DB;
class CompleteCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'complete:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        
        
        $today = date('Y-m-d'); // 2024-05-08
        $today_time = date('H:i:s'); // 16:52:19
        $bookings = Bookings::where('status',Constants::orderAccepted)->where('date',$today)->with(['salon', 'user'])->get();
        $data['cron_run_date']=date('Y-m-d H:i:s');
        $data["cron_name"]="Update Booking Confirmation";
        
        DB::table('cron_table')->insert($data);
        
        if(!empty($bookings)){
            foreach($bookings as $booking){
                $booking_date = date('Y-m-d',strtotime($booking->date));
                $complete_time = date('H:i:s',strtotime('+'.$booking->booking_hours.'hour',strtotime($booking->time)));
                
                if($today == $booking_date && $today_time >= $complete_time ){
                    // $check[] = date('h:i',strtotime('+'.$booking->booking_hours.'hour',strtotime($booking->time)));
                     $user = Users::find($booking->user_id);
                     $salon = Salons::where('id',1)->first();
            
                    $booking->status = Constants::orderCompleted;
                    $booking->save();
        
                    // Commission calculation
                    $earning = $booking->subtotal;
                    $settings = GlobalSettings::first();
                    $commissionAmount = ($settings->comission / 100) * $earning;
                    
                    // Revenue divide
                    $revenue_per= RevenueSetting::latest()->first();
                    $ibp_revenue = ($revenue_per->ibp_revenue/100)*$earning;
                    $account_maintenance = ($revenue_per->account_maintenance/100)*$earning;
                    $technical_support= ($revenue_per->technical_support/100)*$earning;
        
                    // Adding Earning statement
                    $earningSummary = "Earning from booking: " . $booking->booking_id;
                    GlobalFunction::addSalonStatementEntry($salon->id, $booking->booking_id, $earning, Constants::credit, Constants::salonWalletEarning, $earningSummary);
        
                    // Adding Commission deduct statement
                    $commissionSummary = "Commission of booking: " . $booking->booking_id . " : (" . $settings->comission . "%)";
                    GlobalFunction::addSalonStatementEntry($salon->id, $booking->booking_id, $commissionAmount, Constants::debit, Constants::salonWalletCommission, $commissionSummary);
        
                    // Adding earning to salon wallet + count increase + lifetime earning increase
                    $earningAfterCommission = $earning - $commissionAmount;
                    $salon->wallet = $salon->wallet + $earningAfterCommission;
                    $salon->total_completed_bookings = $salon->total_completed_bookings + 1;
                    $salon->lifetime_earnings = $salon->lifetime_earnings + $earningAfterCommission;
                    $salon->save();
        
                    // Adding Earning Logs Of Salon
                    $salonEarningHistory = new SalonEarningHistory();
                    $salonEarningHistory->salon_id = $salon->id;
                    $salonEarningHistory->booking_id = $booking->id;
                    $salonEarningHistory->earning_number = GlobalFunction::generateSalonEarningHistoryNumber();
                    $salonEarningHistory->amount = $earningAfterCommission;
                    $salonEarningHistory->save();
        
                    // Adding Earning Logs of Platform
                    $platformEarningHistory = new PlatformEarningHistory();
                    $platformEarningHistory->type = "booking";
                    $platformEarningHistory->earning_number = GlobalFunction::generatePlatformEarningHistoryNumber();
                    $platformEarningHistory->amount = $commissionAmount;
                    $platformEarningHistory->ibp_revenue = $ibp_revenue;
                    $platformEarningHistory->account_maintenance = $account_maintenance;
                    $platformEarningHistory->technical_support = $technical_support;
                                        
                    $platformEarningHistory->commission_percentage = $settings->comission;
                    $platformEarningHistory->booking_id = $booking->id;
                    $platformEarningHistory->salon_id = $salon->id;
                    $platformEarningHistory->save();
                    // Increasing total platform earning data
                    $platformData = PlatformData::first();
                    $platformData->lifetime_earnings = $platformData->lifetime_earnings + $commissionAmount;
                    $platformData->save();
        
                    // Sending push to user
                    $title = "Booking : " . $booking->booking_id;
                    $message = "Booking has been completed successfully!";
                    $type="booking";
                    dispatch(new SendNotification($title,$message,$type,$booking->user_id));
                    // GlobalFunction::sendPushToUser($title, $message, $user);
                }
            }
        }
        
    //   return response()->json(['status' => true, 'message' => "Booking completed by provider successfully!"]);
        
        
       // return 0;
    }
}
