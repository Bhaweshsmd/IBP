<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Banners;
use App\Models\Constants;
use App\Models\GlobalFunction;
use App\Models\GlobalSettings;
use App\Models\SalonAvailability;
use App\Models\SalonAwards;
use App\Models\SalonBankAccounts;
use App\Models\SalonBookingSlots;
use App\Models\SalonCategories;
use App\Models\SalonEarningHistory;
use App\Models\SalonGallery;
use App\Models\SalonImages;
use App\Models\SalonNotifications;
use App\Models\SalonPayoutHistory;
use App\Models\SalonReviews;
use App\Models\Salons;
use App\Models\ServiceImages;
use App\Models\Services;
use App\Models\Taxes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Models\Bookings;
use App\Models\Fee;
use App\Models\SalonMapImage;
use App\Models\SalonWalletStatements;

class SalonController extends Controller
{
    function index(Request $request)
    {
        return view('platforms.index');
    }
    
    function list(Request $request)
    {
        $totalData =  Salons::where('status', Constants::statusSalonActive)->count();
        $rows = Salons::with(['images', 'bankAccount'])->orderBy('id', 'DESC')->where('status', Constants::statusSalonActive)->get();

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
            $result = Salons::with(['images', 'bankAccount'])
                ->offset($start)
                ->limit($limit)
                ->where('status', Constants::statusSalonActive)
                ->orderBy($order, $dir)
                ->get();
        } else {
            $search = $request->input('search.value');
            $result =  Salons::with(['images', 'bankAccount'])
                ->where('status', Constants::statusSalonActive)
                ->where(function ($query) use ($search) {
                    $query->Where('salon_number', 'LIKE', "%{$search}%")
                        ->orWhere('salon_name', 'LIKE', "%{$search}%")
                        ->orWhere('email', 'LIKE', "%{$search}%");
                })
                ->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();
            $totalFiltered = Salons::with(['images', 'bankAccount'])
                ->where('status', Constants::statusSalonActive)
                ->where(function ($query) use ($search) {
                    $query->Where('salon_number', 'LIKE', "%{$search}%")
                        ->orWhere('salon_name', 'LIKE', "%{$search}%")
                        ->orWhere('email', 'LIKE', "%{$search}%");
                })
                ->count();
        }
        $data = array();

        $settings = GlobalSettings::first();

        foreach ($result as $item) {
            $imgUrl = GlobalFunction::createMediaUrl($item->owner_photo);

            if ($item->owner_photo != null) {
                $ownerImage = '<img src="' . $imgUrl . '" width="50" height="50">';
            } else {
                $ownerImage = '<img src="http://placehold.jp/150x150.png" width="50" height="50">';
            }
            
            if(has_permission(session()->get('user_type'), 'view_company')){
                $view = '<a href="' . route('platforms.edit', $item->id) . '" class="mr-2 btn btn-info text-white  viewBtn" rel=' . $item->id . ' ><i class="fa fa-eye"></i></a>';
            }else{
                $view = '';
            }

            $gender = "";
            if ($item->gender_served == Constants::salonGenderMale) {
                $gender = '<span  class="badge bg-info text-white ">' . __("Male") . '</span>';
            } else if ($item->gender_served == Constants::salonGenderFemale) {
                $gender = '<span  class="badge bg-danger text-white ">' . __("Female") . '</span>';
            } else if ($item->gender_served == Constants::salonGenderUnisex) {
                $gender = '<span  class="badge bg-primary text-white ">' . __("Unisex") . '</span>';
            }

            $topRated = "";
            if ($item->top_rated == 1) {
                $topRated = '<label class="switch ">
                                <input rel=' . $item->id . ' type="checkbox" class="topRated" checked>
                                <span class="slider round"></span>
                            </label>';
            } else {
                $topRated = '<label class="switch ">
                                <input rel=' . $item->id . ' type="checkbox" class="topRated">
                                <span class="slider round"></span>
                            </label>';
            }

            $salonContact = "";
            $salonContact = '<p>' . $item->email . '<br>' . $item->salon_phone . '</p>';
            $lifetimeEarning = $settings->currency . $item->lifetime_earnings;

            $action = $view;

            $data[] = array(
                $item->salon_number,
                $item->salon_name,
                $lifetimeEarning,
                $salonContact,
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
    
    function edit($salonId)
    {
        $salon = Salons::with(['images', 'bankAccount'])->find($salonId);
        $settings = GlobalSettings::first();
        $salonCats = SalonCategories::whereIn('id', explode(',', $salon->salon_categories))->orderBy('sort','asc')->get();
        $salon->mon_fri_from = GlobalFunction::formateTimeString($salon->mon_fri_from);
        $salon->mon_fri_to = GlobalFunction::formateTimeString($salon->mon_fri_to);
        $salon->sat_sun_from = GlobalFunction::formateTimeString($salon->sat_sun_from);
        $salon->sat_sun_to = GlobalFunction::formateTimeString($salon->sat_sun_to);
        
        $total_accepted=Bookings::where('status',1)->get()->count();
        $total_completed=Bookings::where('status',2)->get()->count();
         $total_cancelled=Bookings::where('status',4)->get()->count();
        
        $slots = SalonBookingSlots::where('salon_id', $salonId)->get();
        foreach ($slots as $slot) {
            $slot->time = GlobalFunction::formateTimeString($slot->time);
        }

        $mondaySlots = array_filter($slots->toArray(), function ($slot) {
            return $slot['weekday'] === 1;
        });
        $tuesdaySlots = array_filter($slots->toArray(), function ($slot) {
            return $slot['weekday'] === 2;
        });
        $wednesdaySlots = array_filter($slots->toArray(), function ($slot) {
            return $slot['weekday'] === 3;
        });
        $thursdaySlots = array_filter($slots->toArray(), function ($slot) {
            return $slot['weekday'] === 4;
        });
        $fridaySlots = array_filter($slots->toArray(), function ($slot) {
            return $slot['weekday'] === 5;
        });
        $saturdaySlots = array_filter($slots->toArray(), function ($slot) {
            return $slot['weekday'] === 6;
        });
        $sundaySlots = array_filter($slots->toArray(), function ($slot) {
            return $slot['weekday'] === 7;
        });

        return view('platforms.view', [
            'salon' => $salon,
            'total_accepted'=>$total_accepted,
            'total_completed'=>$total_completed,
            'total_cancelled'=>$total_cancelled,
            'salonCats' => $salonCats,
            'settings' => $settings,
            'salonStatus' => array(
                'statusSalonSignUpOnly' => Constants::statusSalonSignUpOnly,
                'statusSalonPending' => Constants::statusSalonPending,
                'statusSalonActive' => Constants::statusSalonActive,
                'statusSalonBanned' => Constants::statusSalonBanned,
            ),
            'slots' => array(
                'mondaySlots' => $mondaySlots,
                'tuesdaySlots' => $tuesdaySlots,
                'wednesdaySlots' => $wednesdaySlots,
                'thursdaySlots' => $thursdaySlots,
                'fridaySlots' => $fridaySlots,
                'saturdaySlots' => $saturdaySlots,
                'sundaySlots' => $sundaySlots,
            )
        ]);
    }
    
    function update(Request $request)
    {
        $salon = Salons::find($request->id);
        $salon->salon_name = $request->salon_name;
        $salon->salon_phone = $request->salon_phone;
        $salon->gender_served = $request->gender_served;
        $salon->salon_about = $request->salon_about;
        $salon->salon_address = $request->salon_address;
        if($request->owner_photo){
           $salon->owner_photo = GlobalFunction::saveFileAndGivePath($request->owner_photo);
        }
         if($request->banner){
           $salon->banner = GlobalFunction::saveFileAndGivePath($request->banner);
        }
         $salon->salon_long = $request->salon_long;
         $salon->salon_lat = $request->salon_lat;

        
        $salon->save();
        return Globalfunction::sendSimpleResponse(true, 'Details Updated successfully');
    }
    
    function add_images(Request $request)
    {
        foreach ($request->images as $img) {
            $salonImg = new SalonImages();
            $salonImg->salon_id = $request->id;
            $salonImg->image = GlobalFunction::saveFileAndGivePath($img);
            $salonImg->save();
        }

        return Globalfunction::sendSimpleResponse(true, 'Images saved successfully!');
    }
    
    function delete_images($id)
    {
        $salonImg = SalonImages::find($id);
        GlobalFunction::deleteFile($salonImg->image);
        $salonImg->delete();

        return back()->with(['company_message' => 'Company Image Deleted Successfully']);
    }
    
    function gallery_list(Request $request)
    {
        $totalData =  SalonGallery::where('salon_id', $request->salonId)->count();
        $rows = SalonGallery::where('salon_id', $request->salonId)->orderBy('id', 'DESC')->get();

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
            $result = SalonGallery::where('salon_id', $request->salonId)
                ->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();
        } else {
            $search = $request->input('search.value');
            $result =  SalonGallery::where('salon_id', $request->salonId)
                ->where(function ($query) use ($search) {
                    $query->Where('description', 'LIKE', "%{$search}%");
                })
                ->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();
            $totalFiltered = SalonGallery::where('salon_id', $request->salonId)
                ->where(function ($query) use ($search) {
                    $query->Where('description', 'LIKE', "%{$search}%");
                })
                ->count();
        }
        $data = array();
        foreach ($result as $k=>$item) {

            if ($item->image == null) {
                $image = '<img src="http://placehold.jp/150x150.png" width="50" height="50">';
            } else {
                $imgUrl = GlobalFunction::createMediaUrl($item->image);
                $image = '<img src="' . $imgUrl . '" width="50" height="50">';
            }
            
            if(has_permission(session()->get('user_type'), 'view_gallery_images')){
                $view = '<a href="" data-desc="' . $item->description . '" data-image=' . $item->image . ' class="mr-2 btn btn-primary text-white view viewBtn" rel=' . $item->id . ' ><i class="fa fa-eye"></i></a>';
            }else{
                $view = '';
            }
            
            if(has_permission(session()->get('user_type'), 'delete_gallery_images')){
                $delete = '<a href="" class="mr-2 btn btn-danger text-white delete deleteBtn" rel=' . $item->id . ' ><i class="fa fa-trash"></i></a>';
            }else{
                $delete = '';
            }

            $action = $view . $delete;
            $data[] = array(
                ++$k,
                $image,
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
    
    function add_gallery(Request $request)
    {
        foreach ($request->images as $img) {
            
            $gallery = new SalonGallery();
            $gallery->salon_id = $request->id;
            $gallery->image = GlobalFunction::saveFileAndGivePath($img);
            $gallery->save();
        }

        return Globalfunction::sendSimpleResponse(true, 'Images saved successfully!');
    }
    
    function delete_gallery($id)
    {
        $item = SalonGallery::find($id);
        GlobalFunction::deleteFile($item->image);
        $item->delete();

        return back()->with(['company_message' => 'Gallery Image Deleted Successfully']);
    }
    
    function bookings_list(Request $request)
    {
        $totalData =  Bookings::where('salon_id', $request->salonId)->count();
        $rows = Bookings::where('salon_id', $request->salonId)
            ->with(['user', 'salon'])->orderBy('id', 'DESC')->get();
        $settings = GlobalSettings::first();

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
            $result = Bookings::where('salon_id', $request->salonId)
                ->with(['user', 'salon'])
                ->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();
        } else {
            $search = $request->input('search.value');
            $result =  Bookings::where('salon_id', $request->salonId)
                ->with(['user', 'salon'])
                ->where(function ($query) use ($search) {
                    $query->Where('booking_id', 'LIKE', "%{$search}%")
                        ->orWhere('payable_amount', 'LIKE', "%{$search}%");
                })
                ->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();
            $totalFiltered = Bookings::where('salon_id', $request->salonId)
                ->with(['user', 'salon'])
                ->where(function ($query) use ($search) {
                    $query->Where('booking_id', 'LIKE', "%{$search}%")
                        ->orWhere('payable_amount', 'LIKE', "%{$search}%");
                })
                ->count();
        }
        $data = array();
        foreach ($result as $k=>$item) { 

            if(has_permission(session()->get('user_type'), 'view_bookings')){
                $view = '<a href="' . route('bookings.view', $item->id) . '" class="mr-2 btn btn-primary viewBtn text-white" rel=' . $item->id . ' ><i class="fa fa-eye faPostion"></i></a>';
                $print = '<a href="' . route('booking.invoice', $item->id) . '" class="mr-2 btn btn-success printBtn text-white" target="_blank" rel=' . $item->id . ' ><i class="fa fa-print faPostion"></i></a>';
            }else{
                $view = '';
                $print = '';
            }

            $action = $view.$print;

            $salon = '<a href="' . route('platforms.edit', $item->salon->id) . '"><span class="badge bg-primary text-white">
                        ' . $item->salon->salon_name . '</span></a>';

            $status = "";
            if ($item->status == Constants::orderPlacedPending) {
                $status = '<span class="badge bg-warning text-white"rel="' . $item->id . '">' . __('Pending') . '</span>';
            } else if ($item->status == Constants::orderAccepted) {
                $status = '<span class="badge bg-primary text-white" rel="' . $item->id . '">' . __('Accepted') . '</span>';
            } else if ($item->status == Constants::orderCompleted) {
                $status = '<span class="badge bg-success text-white"rel="' . $item->id . '">' . __('Completed') . '</span>';
            } else if ($item->status == Constants::orderDeclined) {
                $status = '<span class="badge bg-danger text-white"rel="' . $item->id . '">' . __('Declined') . '</span>';
            } else if ($item->status == Constants::orderCancelled) {
                $status = '<span class="badge bg-danger text-white"rel="' . $item->id . '">' . __('Cancelled') . '</span>';
            }

            $dateTime =  date('d M Y',strtotime($item->date)) . '<br>' . date('h:i A',strtotime($item->time));
            $payableAmount = $settings->currency . $item->payable_amount;
            $full_name= '<a href="' . route('users.profile', $item->user->id) . '"><span class="">' . $item->user->first_name.' '.$item->user->last_name . '</span></a>';
            $data[] = array(
                ++$k,
                '<a href="' . route('bookings.view', $item->id) . '">'.$item->booking_id.'</a>',
                $full_name,
                $status,
                $dateTime,
                $settings->currency . number_format($item->service_amount, 2, '.', ','),
                $settings->currency . number_format($item->discount_amount, 2, '.', ','),
                $settings->currency . number_format($item->subtotal, 2, '.', ','),
                $settings->currency . number_format($item->total_tax_amount, 2, '.', ','),
                $payableAmount,
                GlobalFunction::formateTimeString($item->created_at),
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
    
    function services_list(Request $request)
    {
        $totalData =  Services::where('salon_id', $request->salonId)->count();
        $rows = Services::with(['images','reviews'])->where('salon_id', $request->salonId)->orderBy('id', 'DESC')->get();
        $settings = GlobalSettings::first();

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
            $result = Services::with(['images','reviews'])
                ->where('salon_id', $request->salonId)
                ->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();
        } else {
            $search = $request->input('search.value');
            $result =  Services::with(['images','reviews'])
                ->where('salon_id', $request->salonId)
                ->where(function ($query) use ($search) {
                    $query->Where('service_number', 'LIKE', "%{$search}%")
                        ->orWhere('title', 'LIKE', "%{$search}%")
                        ->orWhere('price', 'LIKE', "%{$search}%");
                })
                ->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();
            $totalFiltered = Services::with(['images','reviews'])
                ->where('salon_id', $request->salonId)
                ->where(function ($query) use ($search) {
                    $query->Where('service_number', 'LIKE', "%{$search}%")
                        ->orWhere('title', 'LIKE', "%{$search}%")
                        ->orWhere('price', 'LIKE', "%{$search}%");
                })
                ->count();
        }
        $data = array();
        foreach ($result as $k=>$item) {

            $firstImage = $item->images->get(0);

            if ($firstImage == null) {
                $serviceImg = '<img src="http://placehold.jp/150x150.png" width="50" height="50">';
            } else {
                $imgUrl = GlobalFunction::createMediaUrl($firstImage->image);
                $serviceImg = '<img src="' . $imgUrl . '" width="50" height="50">';
            }
            
            if(has_permission(session()->get('user_type'), 'view_services')){
                $view = '<a href="' . route('viewService', $item->id) . '" class="mr-2 btn btn-info text-white viewBtn " rel=' . $item->id . ' ><i class="fa fa-eye faPostion"></i></a>';
            }else{
                $view = '';
            }
            
            if(has_permission(session()->get('user_type'), 'delete_services')){
                $delete = '<a href="" class="mr-2 btn btn-danger text-white delete deleteBtn" rel=' . $item->id . ' ><i class="fa fa-trash faPostion"></i></a>';
            }else{
                $delete = '';
            }
           
            $action = $view  . $delete;

            $gender = "";
            if ($item->gender == Constants::salonGenderMale) {
                $gender = '<span  class="badge bg-info text-white ">' . __("Male") . '</span>';
            } else if ($item->gender == Constants::salonGenderFemale) {
                $gender = '<span  class="badge bg-danger text-white ">' . __("Female") . '</span>';
            } else if ($item->gender == Constants::salonGenderUnisex) {
                $gender = '<span  class="badge bg-primary text-white ">' . __("Unisex") . '</span>';
            }

            $onOff = "";
            if ($item->status == Constants::statusServiceOn) {
                $onOff = '<label class="switch ">
                                <input rel=' . $item->id . ' type="checkbox" class="onoff" checked>
                                <span class="slider round"></span>
                            </label>';
            } else {
                $onOff = '<label class="switch ">
                                <input rel=' . $item->id . ' type="checkbox" class="onoff">
                                <span class="slider round"></span>
                            </label>';
            }

            $data[] = array(
                ++$k,
                $item->service_number,
                $serviceImg,
                $item->title,
                $item->category->title,
                $item->service_time,
                $settings->currency . $item->price,
                $item->discount . "%",
                $gender,
                $onOff,
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
    
    function map_list(Request $request)
    {
        $totalData =  SalonMapImage::where('salon_id', $request->salonId)->count();
        $rows = SalonMapImage::where('salon_id', $request->salonId)->orderBy('id', 'DESC')->get();

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
            $result = SalonMapImage::where('salon_id', $request->salonId)
                ->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();
        } else {
            $search = $request->input('search.value');
            $result =  SalonMapImage::where('salon_id', $request->salonId)
                ->where(function ($query) use ($search) {
                    $query->Where('description', 'LIKE', "%{$search}%");
                })
                ->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();
            $totalFiltered = SalonMapImage::where('salon_id', $request->salonId)
                ->where(function ($query) use ($search) {
                    $query->Where('description', 'LIKE', "%{$search}%");
                })
                ->count();
        }
        $data = array();
        foreach ($result as $k=>$item) {

            if ($item->image == null) {
                $image = '<img src="http://placehold.jp/150x150.png" width="50" height="50">';
            } else {
                $imgUrl = GlobalFunction::createMediaUrl($item->image);
                $image = '<img src="' . $imgUrl . '" width="50" height="50">';
            }
            
            if(has_permission(session()->get('user_type'), 'view_gallery_images')){
                $view = '<a href="" data-desc="' . $item->description . '" data-image=' . $item->image . ' class="mr-2 btn btn-primary text-white view viewBtn" rel=' . $item->id . ' ><i class="fa fa-eye"></i></a>';
            }else{
                $view = '';
            }
            
            if(has_permission(session()->get('user_type'), 'delete_gallery_images')){
                $delete = '<a href="" class="mr-2 btn btn-danger text-white delete deleteBtn" rel=' . $item->id . ' ><i class="fa fa-trash"></i></a>';
            }else{
                $delete = '';
            }

            $action = $view . $delete;
            $data[] = array(
                ++$k,
                $image,
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
    
    function add_map(Request $request)
    {
        foreach ($request->images as $img) {
            
            $gallery = new SalonMapImage();
            $gallery->salon_id = $request->id;
            $gallery->image = GlobalFunction::saveFileAndGivePath($img);
            $gallery->save();
        }

        return Globalfunction::sendSimpleResponse(true, 'Images saved successfully!');
    }
    
    function delete_map($id)
    {
        $salonImg = SalonMapImage::find($id);
        GlobalFunction::deleteFile($salonImg->image);
        $salonImg->delete();

        return back()->with(['company_message' => 'Map Image Deleted Successfully']);
    }
    
    function reviews_list(Request $request)
    {
        $totalData =  SalonReviews::with('booking')->where('salon_id', $request->salonId)->count();
        $rows = SalonReviews::with('booking')->where('salon_id', $request->salonId)->orderBy('id', 'DESC')->get();

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
            $result = SalonReviews::with('booking')
                ->where('salon_id', $request->salonId)
                ->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();
        } else {
            $search = $request->input('search.value');
            $result =  SalonReviews::with('booking')->where('salon_id', $request->salonId)
                ->whereHas('booking', function ($q) use ($search) {
                    $q->where('booking_id', 'LIKE', "%{$search}%");
                })
                ->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();
            $totalFiltered = SalonReviews::with('booking')->where('salon_id', $request->salonId)
                ->whereHas('booking', function ($q) use ($search) {
                    $q->where('booking_id', 'LIKE', "%{$search}%");
                })
                ->count();
        }
        $data = array();
        foreach ($result as $k=>$item) {
            if(has_permission(session()->get('user_type'), 'delete_reviews')){
                $delete = '<a href="" class="mr-2 btn btn-danger text-white delete deleteBtn" rel=' . $item->id . ' ><i class="fa fa-trash"></i></a>';
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
         
         
          $salon = "";
            if ($item->salon != null) {
                $salon = '<a href="' . route('viewService', $item->salon->id) . '"><span class="badge bg-primary text-white">' . $item->service->title . '</span></a>';
            }
            
            $userName = "";
            if ($item->user_id != null) {
                $userName = '<a href="' . route('users.profile', $item->user->id) . '"><span class="badge bg-primary text-white">' . $item->user->profile_id . '</span></a>';
            }


            $action = $delete;
            $data[] = array(
                ++$k,
                $ratingBar,
                $salon,
                $userName,
                $item->comment,
                $item->booking != null ? $item->booking->booking_id : '',
                GlobalFunction::formateTimeString($item->created_at),
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
}
