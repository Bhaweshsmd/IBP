<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Models\MaintenanceSetting;
use Carbon\Carbon;

class CheckLogin
{
    public function handle(Request $request, Closure $next)
    {
        $cuurent_date = Carbon::now()->format('Y-m-d');
        $cuurent_time = Carbon::now()->format('H:i');
        $maintenance = MaintenanceSetting::where(function ($query) {$query->where('type', 1)->orWhere('type', 3);})->whereDate('date', '=', $cuurent_date)->whereTime('to_time', '>=', $cuurent_time)->whereTime('from_time', '<=', $cuurent_time)->orderBy('id', 'desc')->first();
        if(!empty($maintenance)){
            $data['maintainance'] = MaintenanceSetting::where('id', $maintenance->id)->first();
            return response()->view('maintainance.break', $data);
        }
        
        $response = $next($request);
        $response->headers->set('Cache-Control', 'nocache, no-store, max-age=0, must-revalidate');
        $response->headers->set('Pragma', 'no-cache');
        $response->headers->set('Expires', 'Sun, 02 Jan 2021 00:00:00 GMT');


      if (Session::get('user_name')) {
            return $response;
        } else {
            return redirect(url('/'));
        }
    }
}
