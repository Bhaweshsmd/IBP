<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Banners;
use App\Models\Bookings;
use App\Models\Constants;
use App\Models\Coupons;
use App\Models\FaqCats;
use App\Models\Faqs;
use App\Models\GlobalFunction;
use App\Models\GlobalSettings;
use App\Models\PlatformEarningHistory;
use App\Models\SalonCategories;
use App\Models\SalonNotifications;
use App\Models\SalonPayoutHistory;
use App\Models\SalonReviews;
use App\Models\Salons;
use App\Models\Taxes;
use App\Models\UserNotification;
use App\Models\UserWalletRechargeLogs;
use App\Models\UserWithdrawRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Google\Client;
use Illuminate\Support\Facades\File;
Use DB;
use App\Models\Language;
use App\Models\LanguageContent;
use App\Models\User;
use App\Models\EmailTemplate;
use App\Models\Fee;
use App\Models\NotificationTemplate;
use App\Models\SupportTicket;
use App\Models\RevenueSetting;
use App\Models\AdminEmailTemplate;
use App\Models\CardFee;
use App\Models\CardTopup;
use App\Models\SupportTicketMessage;
use App\Models\Users;
use App\Models\Card;
use App\Models\TansactionType;
use Carbon\Carbon;
use App\Models\AdminNotificationTemplate;
use App\Models\MaintenanceFee;
use App\Models\CardmembershipFee;

class ReviewController extends Controller
{
    function index()
    {
        return view('reviews.index');
    }
    
    function list(Request $request)
    {
        $totalData =  SalonReviews::with(['booking', 'salon'])->count();
        $rows = SalonReviews::with(['booking', 'salon'])->orderBy('id', 'DESC')->get();

        $result = $rows;

        $columns = array(
            0 => 'id',
            1 => 'fullname',
            2 => 'identity',
            3 => 'username',
        );

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        $totalFiltered = $totalData;
        if (empty($request->input('search.value'))) {
            $result = SalonReviews::with(['booking', 'salon'])
                ->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();
        } else {
            $search = $request->input('search.value');
            
            $result =  SalonReviews::with(['booking', 'salon'])
                ->whereHas('booking', function ($q) use ($search) {
                    $q->where('booking_id', 'LIKE', "%{$search}%");
                })
                ->orWhere('comment', 'LIKE', "%{$search}%")
                ->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();
            $totalFiltered = SalonReviews::with(['booking', 'salon'])
                ->whereHas('booking', function ($q) use ($search) {
                    $q->where('booking_id', 'LIKE', "%{$search}%");
                })
                ->orWhere('comment', 'LIKE', "%{$search}%")
                ->count();
                
            if(!$totalFiltered){
                   $users_ids= Users::where('profile_id','LIKE', "%{$search}%")
                    ->orWhere('first_name', 'LIKE', "%{$search}%")
                    ->orWhere('last_name', 'LIKE', "%{$search}%")
                    ->pluck('id');
                  
                $result =  SalonReviews::with(['booking', 'salon'])->whereIn('user_id',$users_ids)
                ->orWhere('comment', 'LIKE', "%{$search}%")
                ->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();
                
                $totalFiltered = SalonReviews::with(['booking', 'salon'])->whereIn('user_id',$users_ids)
                    ->orWhere('comment', 'LIKE', "%{$search}%")
                    ->offset($start)
                    ->limit($limit)
                    ->orderBy($order, $dir)
                    ->count();
            }
        }
        
        $data = array();
        $j=1;
        foreach ($result as $item) {
            
            if($item->status == '0'){
                if(has_permission(session()->get('user_type'), 'edit_reviews')){
                    $approve = '<a href="" class="mr-2 btn btn-success text-white approve approveBtn" rel=' . $item->id . ' ><i class="fa fa-check faPostion"></i></a>';
                }else{
                    $approve = '';
                }
                
                if(has_permission(session()->get('user_type'), 'edit_reviews')){
                    $reject = '<a href="" class="mr-2 btn btn-warning text-white reject rejectBtn" rel=' . $item->id . ' ><i class="fa fa-times faPostion"></i></a>';
                }else{
                    $reject = '';
                }
                
                $status = '<span class="badge bg-primary text-white ">Pending</span>';
            }elseif($item->status == '1'){
                $approve = '';
                $reject = '';
                $status = '<span class="badge bg-success text-white ">Approved</span>';
            }elseif($item->status == '2'){
                $approve = '';
                $reject = '';
                $status = '<span class="badge bg-danger text-white ">Rejected</span>';
            }
            
            if(has_permission(session()->get('user_type'), 'edit_reviews')){
                $edit = '<a href="' . route('edit.reviews', $item->id) . '" class="mr-2 btn btn-primary text-white edit editBtn" rel=' . $item->id . ' ><i class="fa fa-edit faPostion"></i></a>';
            }else{
                $edit = '';
            }
            
            if(has_permission(session()->get('user_type'), 'delete_reviews')){
                $delete = '<a href="" class="mr-2 btn btn-danger text-white delete deleteBtn" rel=' . $item->id . ' ><i class="fa fa-trash faPostion"></i></a>';
            }else{
                $delete = '';
            }

            $starDisabled = '<i class="fas fa-star starDisabled"></i>';
            $starActive = '<i class="fas fa-star starActive"></i>';

            $ratingBar = '';
            for ($i = 0; $i < 5; $i++) {
                if ($item->rating > $i) {
                    $ratingBar = $ratingBar . $starActive;
                } else {
                    $ratingBar = $ratingBar . $starDisabled;
                }
            }
        
            $salon = '<a href="' . route('viewService', $item->salon_id) . '"><span class="badge bg-primary text-white">' . $item->service->title . '</span></a>';
            
            if ($item->user_id != null) {
                $userid = '<a href="' . route('users.profile', $item->user->id) . '"><span class="badge bg-primary text-white">' . $item->user->profile_id . '</span></a>';
            }
            
            if ($item->user_id != null) {
                $userName = '<a href="' . route('users.profile', $item->user->id) . '"><span class="badge bg-primary text-white">' . $item->user->first_name.' '.$item->user->last_name . '</span></a>';
            }

            $action = $approve.$reject.$edit.$delete;
            
            $data[] = array(
                $j++,
                $ratingBar,
                $salon,
                $userid,
                $userName,
                $item->comment,
                $item->booking != null ? $item->booking->booking_id : "",
                date('d-m-Y h:i A',strtotime($item->created_at)),
                $status,
                $action,
            );
        }
        $json_data = array(
            "draw"            => intval($request->input('draw')),
            "recordsTotal"    => intval($totalData),
            "recordsFiltered" => $totalFiltered,
            "data"            => $data
        );
        echo json_encode($json_data);
        exit();
    }
    
    function edit($id)
    {
        $review = SalonReviews::find($id);
        $user = Users::where('id', $review->user_id)->first();
        $service = $review->service;

        return view('reviews.edit', ['review' => $review, 'user' => $user, 'service' => $service]);
    }
    
    function update(Request $request, $id)
    {
        $review = SalonReviews::where('id', $id)->update([
            'comment' => $request->comment,
            'status' => $request->status,
        ]);

        return redirect('reviews')->with(['review_message' => 'Review Updated Successfully']);
    }
    
    function approve($id)
    {
        $review = SalonReviews::where('id', $id)->update(['status' => '1']);
        return redirect('reviews')->with(['review_message' => 'Review Approved Successfully']);
    }
    
    function reject($id)
    {
        $review = SalonReviews::where('id', $id)->update(['status' => '2']);
        return redirect('reviews')->with(['review_message' => 'Review Rejected Successfully']);
    }
    
    function delete($id)
    {
        $review = SalonReviews::find($id);
        $salon = $review->service;
        $review->delete();

        $salon->rating = $salon->avgRating();
        $salon->save();

        return redirect('reviews')->with(['review_message' => 'Review Deleted Successfully']);
    }
}