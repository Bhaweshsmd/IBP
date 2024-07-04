<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Constants;
use App\Models\GlobalFunction;
use App\Models\GlobalSettings;
use App\Models\SalonCategories;
use App\Models\SalonImages;
use App\Models\Salons;
use App\Models\ServiceImages;
use App\Models\ServiceMapImage;
use App\Models\Services;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\SalonBookingSlots;
use App\Models\Users;

class ServiceController extends Controller
{
    function index()
    {
        return view('services.index');
    }
    
    function fetch_services(Request $request)
    {
        $user = Users::find($request->user_id);
        
        if($user->user_type==1){
            $services = Services::select(['id','latitude','logintude','salon_id','category_id','rating','thumbnail','title','service_time','service_number','about','rules','qauntity','foreiner_price as price','foreiner_price_per_day as price_per_day','foreiner_discount as discount'])->where('category_id', $request->category_id)->where('status',1)->get();
        }else{
            $services = Services::select(['id','latitude','logintude','salon_id','category_id','thumbnail','rating','title','service_time','service_number','about','rules','qauntity','price','price_per_day','discount'])->where('category_id', $request->category_id)->where('status',1)->get(); 
        }
        
        $bookings['services'] = $services; 
            
        return GlobalFunction::sendDataResponse(true, 'Services fetched successfully', $bookings);
    }
    
    function list(Request $request)
    {
        $totalData =  Services::where('status', '!=', '2')->count();
        $rows = Services::with(['images','reviews'])->where('status', '!=', '2')->orderBy('id', 'DESC')->get();
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
                ->where('status', '!=', '2')->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();
        } else {
            $search = $request->input('search.value');
                
                   $categories_id = SalonCategories::where('title', 'LIKE', "%{$search}%")
                        ->pluck('id');
                       $result =  Services::with(['images'])
                       ->where('status', '!=', '2')->whereIn('category_id',$categories_id)
                        ->offset($start)
                        ->limit($limit)
                        ->orderBy($order, $dir)
                        ->get(); 
                        $totalFiltered =  Services::with(['images'])
                       ->where('status', '!=', '2')->whereIn('category_id',$categories_id)
                        ->offset($start)
                        ->limit($limit)
                        ->orderBy($order, $dir)
                        ->count(); 
                 
            
            if(!$totalFiltered){
            $result =  Services::with(['images'])
                ->where('status', '!=', '2')->where(function ($query) use ($search) {
                    $query->Where('service_number', 'LIKE', "%{$search}%")
                        ->orWhere('title', 'LIKE', "%{$search}%")
                        ->orWhere('price', 'LIKE', "%{$search}%");
                })
                ->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();
            $totalFiltered = Services::with(['images','reviews'])
                ->where('status', '!=', '2')->where(function ($query) use ($search) {
                    $query->Where('service_number', 'LIKE', "%{$search}%")
                        ->orWhere('title', 'LIKE', "%{$search}%")
                        ->orWhere('price', 'LIKE', "%{$search}%");
                })
                ->count();
            } 
                
        }
        $data = array();
        $i=1;
        foreach ($result as $item) {

            $firstImage = $item->thumbnail;

            if ($firstImage == null) {
                $serviceImg = '<img src="http://placehold.jp/150x150.png" width="50" height="50">';
            } else {
                $imgUrl = GlobalFunction::createMediaUrl($firstImage);
                $serviceImg = '<img src="' . $imgUrl . '" width="50" height="50">';
            }
            
            if(has_permission(session()->get('user_type'), 'view_services')){
                $view = '<a href="' . route('viewService', $item->id) . '" class="mr-2 btn btn-info text-white editBtn " rel=' . $item->id . ' ><i class="fa fa-edit faPostion"></i></a>';
            }else{
                $view = '';
            }
            
            if(has_permission(session()->get('user_type'), 'delete_services')){
                $delete = '<a href="" class="mr-2 btn btn-danger text-white delete deleteBtn" rel=' . $item->id . ' ><i class="fa fa-trash "></i></a>';
            }else{
                $delete = '';
            }
            
            $action = $view  . $delete;

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
                $i++,
                $item->service_number,
                $serviceImg,
                $item->title??'',
                $item->category->title??'',
                $item->service_time,
                $settings->currency . number_format($item->price, 2, '.', ','),
                $item->discount . "%",
                 $settings->currency .number_format($item->foreiner_price, 2, '.', ','),
                $item->foreiner_discount . "%",
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
    
    function create()
    {
        $categories = SalonCategories::where(['is_deleted'=>0,'parent'=>null,'status'=>'1'])->get();
        $settings = GlobalSettings::first();
        return view('services.create', [
            'settings' => $settings,
            'categories' => $categories
        ]);
    }
    
    function preview(Request $request)
    {
        $rules = [
            'category_id' => 'required',
            'title' => 'required',
            'price' => 'required',
            'foreiner_price'=>'required',
            'thumbnail' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $messages = $validator->errors()->all();
            $msg = $messages[0];
            return back()->with(['status' => false, 'message' => $msg])->withInput();
        }
        $category = SalonCategories::where('id', $request->category_id)->first();
       
        if($request->thumbnail){
          $thumnail =GlobalFunction::saveFileAndGivePath($request->thumbnail);
        }
        if ($category == null) {
            return back()->with(['status' => false, 'message' => "Category doesn't exists!"])->withInput();;
        }

        $service = new Services();
        $service->service_number = GlobalFunction::generateServiceNumber();
        $service->salon_id = 1;
        $service->status = 2;
        $service->category_id = $request->category_id;
        $service->title = $request->title;
        $service->slug = GlobalFunction::cleanString($request->title);
        $service->title_in_papiamentu =$request->title_in_papiamentu;
        $service->title_in_dutch = $request->title_in_dutch;
        $service->price = GlobalFunction::cleanString($request->price);
        $service->service_time = GlobalFunction::cleanString($request->service_time);
        $service->thumbnail  =$thumnail; 
        $service->latitude  =GlobalFunction::cleanString($request->latitude); 
        $service->logintude  =GlobalFunction::cleanString($request->logintude); 
        $service->foreiner_price  =GlobalFunction::cleanString($request->foreiner_price); 
        $service->foreiner_discount  =GlobalFunction::cleanString($request->foreiner_discount); 
        $service->qauntity  =$request->qauntity; 
        $service->rules  =$request->rules;
        $service->service_type  =$request->service_type;
        $service->rules_in_papiamentu =$request->rules_in_papiamentu;
        $service->rules_in_dutch  =$request->rules_in_dutch;
        $service->local_cancellation_charges  =GlobalFunction::cleanString($request->local_cancellation_charges); 
        $service->foreiner_cancellation_charges  =GlobalFunction::cleanString($request->foreiner_cancellation_charges); 
        if ($request->has('discount')) {
            $service->discount = $request->discount;
        }
        $service->discount = $request->discount;
        $service->about = $request->about;
        $service->about_in_papiamentu = $request->about_in_papiamentu;
        $service->about_in_dutch= $request->about_in_dutch;
        $service->history = $request->history;
        $service->history_in_papiamentu = $request->history_in_papiamentu;
        $service->history_in_dutch= $request->history_in_dutch;
        $service->included_items  =$request->included_items;
        $service->included_items_in_papiamentu  =$request->included_items_in_papiamentu;
        $service->included_items_in_dutch  =$request->included_items_in_dutch;
        $service->save();
        
        if($request->images){
            foreach ($request->images as $image) {
                $img = new ServiceImages();
                $img->service_id = $service->id;
                $img->image = GlobalFunction::saveFileAndGivePath($image);
                $img->save();
            }
        }
        
        if($request->map_images){
            foreach ($request->map_images as $image){
                $img = new ServiceMapImage();
                $img->service_id = $service->id;
                $img->image = GlobalFunction::saveFileAndGivePath($image);
                $img->save();
            }
        }
        
        $prev_service = Services::with(['images', 'salon'])->find($service->id);
        $categories = SalonCategories::where('is_deleted', 0)->get();
        $settings = GlobalSettings::first();
        $slots = SalonBookingSlots::where('salon_id', $prev_service->id)->orderBy('time','ASC')->get();
        foreach ($slots as $slot) {
            $slot->time = $slot->time;
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
        
        return view('services.preview', [
            'service' => $prev_service,
            'settings' => $settings,
            'categories' => $categories,
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
    
    function store(Request $request)
    {
        $service = Services::find($request->id);
        $service->status = '1';
        $service->save();
        
        $del_service = Services::where('status', '2')->get();
        foreach($del_service as $del){
            $serviceImages = ServiceImages::where('service_id', $del->id)->get();
            foreach ($serviceImages as $image) {
                GlobalFunction::deleteFile($image->image);
                $image->delete();
            }
        }
        Services::where('status', '2')->delete();
        
        return redirect('services')->with(['service_message' => 'Service Added Successfully']);
    }
    
    function edit($id)
    {
        $service = Services::with(['images', 'salon'])->find($id);
        $categories = SalonCategories::where('is_deleted', 0)->get();
        $settings = GlobalSettings::first();
        $slots = SalonBookingSlots::where('salon_id', $id)->orderBy('time','ASC')->get();
        foreach ($slots as $slot) {
            $slot->time = $slot->time;
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
        
        return view('services.edit', [
            'service' => $service,
            'settings' => $settings,
            'categories' => $categories,
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
        $thumnail="";
        $service = Services::find($request->id);
        $service->title = $request->title;
        $service->slug = GlobalFunction::cleanString($request->title);
        $service->title_in_papiamentu = $request->title_in_papiamentu;
        $service->title_in_dutch =$request->title_in_dutch;
        $service->category_id = $request->category_id;
        $service->gender = $request->gender;
        $service->about = $request->about;
        $service->about_in_papiamentu = $request->about_in_papiamentu;
        $service->about_in_dutch= $request->about_in_dutch;
        $service->history = $request->history;
        $service->history_in_papiamentu = $request->history_in_papiamentu;
        $service->history_in_dutch= $request->history_in_dutch;
        $service->price = $request->price;
        $service->service_time = $request->service_time;
        $service->discount = $request->discount;
        if($request->thumbnail){
            $thumnail =GlobalFunction::saveFileAndGivePath($request->thumbnail);
            $service->thumbnail  =$thumnail; 
        }
        $service->latitude  =GlobalFunction::cleanString($request->latitude); 
        $service->logintude  =GlobalFunction::cleanString($request->logintude); 
        $service->foreiner_price  =GlobalFunction::cleanString($request->foreiner_price); 
        $service->foreiner_discount  =GlobalFunction::cleanString($request->foreiner_discount); 
        $service->qauntity  =$request->qauntity; 
        $service->rules  =$request->rules; 
        $service->service_type  =$request->service_type; 
        $service->rules_in_papiamentu =$request->rules_in_papiamentu;
        $service->rules_in_dutch  =$request->rules_in_dutch;
        $service->local_cancellation_charges  =GlobalFunction::cleanString($request->local_cancellation_charges); 
        $service->foreiner_cancellation_charges  =GlobalFunction::cleanString($request->foreiner_cancellation_charges); 
        $service->included_items  =$request->included_items;
        $service->included_items_in_papiamentu  =$request->included_items_in_papiamentu;
        $service->included_items_in_dutch  =$request->included_items_in_dutch;
        $service->save();
        if($request->images){
            foreach ($request->images as $image) {
                $img = new ServiceImages();
                $img->service_id = $service->id;
                $img->image = GlobalFunction::saveFileAndGivePath($image);
                $img->save();
            }
        }
        
        if($request->map_images){
            foreach ($request->map_images as $image){
                $img = new ServiceMapImage();
                $img->service_id = $service->id;
                $img->image = GlobalFunction::saveFileAndGivePath($image);
                $img->save();
            }
        }
        
        return redirect('services')->with(['service_message' => 'Service Updated Successfully']);
    }
    
    function delete($id)
    {
        $service = Services::where('id', $id)->first();
        if ($service == null) {
            return response()->json(['status' => false, 'message' => "Service doesn't exists!"]);
        }
        $serviceImages = ServiceImages::where('service_id', $service->id)->get();
        foreach ($serviceImages as $image) {
            GlobalFunction::deleteFile($image->image);
            $image->delete();
        }
        $service->delete();
        return redirect('services')->with(['service_message' => 'Service Deleted Successfully']);
    }
    
    function status($id, $status)
    {
        $service = Services::find($id);
        $service->status = $status;
        $service->save();
        
        if($status == '1'){
            $message = 'Service Enabled Successfully';
        }else{
            $message = 'Service Disabled Successfully';
        }

        return redirect('services')->with(['service_message' => $message]);
    }
    
    function delete_service_image($id)
    {
        $serviceImg = ServiceImages::find($id);
        GlobalFunction::deleteFile($serviceImg->image);
        $serviceImg->delete();

        return back()->with(['slot_message' => 'Image Deleted Successfully']);
    }
    
    function delete_service_map_image($id)
    {
        $serviceImg = ServiceMapImage::find($id);
        GlobalFunction::deleteFile($serviceImg->image);
        $serviceImg->delete();
        
        return back()->with(['slot_message' => 'Image Deleted Successfully']);
    }
    
    function categorised_services($id)
    {
        $category = SalonCategories::where('id', $id)->first();
        return view('services.categorised', ['category' => $category]);
    }
    
    function categorised_services_list(Request $request, $id)
    {
        $totalData =  Services::count();
        $rows = Services::with(['images','reviews'])->where('category_id', $id)->orderBy('id', 'DESC')->get();
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
                ->where('category_id', $id)
                ->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();
        } else {
            $search = $request->input('search.value');
            $result =  Services::with(['images'])
                ->where('category_id', $id)
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
                ->where(function ($query) use ($search) {
                    $query->Where('service_number', 'LIKE', "%{$search}%")
                        ->orWhere('title', 'LIKE', "%{$search}%")
                        ->orWhere('price', 'LIKE', "%{$search}%");
                })
                ->count();
        }
        $data = array();
        foreach ($result as $item) {

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
                $delete = '<a href="" class="mr-2 btn btn-danger text-white delete deleteBtn" rel=' . $item->id . ' ><i class="fa fa-trash "></i></a>';
            }else{
                $delete = '';
            }
            
            $action = $view  . $delete;

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
                $item->service_number,
                $serviceImg,
                $item->title??'',
                $item->category->title??'',
                $item->service_time,
                $settings->currency . $item->price,
                $item->discount . "%",
                $settings->currency . $item->foreiner_price,
                $item->foreiner_discount . "%",
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
    
    function add_booking_slots(Request $request)
    {
        $rules = [
            'time' => 'required',
            'weekday' => 'required',
            'service_id' => 'required',
            'booking_limit' => 'required',
            'booking_hours'=>'required',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $messages = $validator->errors()->all();
            $msg = $messages[0];
            return back()->with(['status' => false, 'message' => $msg])->withInput();

            // return response()->json(['status' => false, 'message' => $msg]);
        }
        $salon = Services::where('id', $request->service_id)->first();
        if ($salon == null) {
            $msg= 'Salon does not exists!';
            
            return back()->with(['status' => false, 'message' => $msg])->withInput();
        }

        $slot = SalonBookingSlots::where('booking_hours',$request->booking_hours)->where('time', str_replace(':','',$request->time))
            ->where('weekday', $request->weekday)
            ->where('salon_id', $salon->id)
            ->first();

        if ($slot == null) {
            $slot = new SalonBookingSlots();
            $slot->time = str_replace(':','',$request->time);
            $slot->booking_hours=$request->booking_hours;
            $slot->weekday = $request->weekday;
            $slot->salon_id = $request->service_id;
            $slot->booking_limit = $request->booking_limit;
            $slot->save();

            // $salon = Salons::with(['images', 'bankAccount', 'slots'])->find($request->service_id);
            $msg=  'Slot added successfully';
            return back()->with(['status' => true, 'message' => $msg])->withInput();

        } else {
            
            $msg= 'This Slot is available already!';
            return back()->with(['status' => false, 'message' => $msg])->withInput();

        }
    }

    function delete_booking_slots($id=null)
    {
        $slot = SalonBookingSlots::find($id);
        if ($slot == null) {
            return GlobalFunction::sendSimpleResponse(false, 'Slot does not Exists');
        }
        $slot->delete();

        return back()->with(['slot_message' => 'Slot Deleted Successfully']);
    }
}
