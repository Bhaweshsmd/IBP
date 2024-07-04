<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Models\MaintenanceSetting;
use Carbon\Carbon;
use DB;

class CheckAuth
{
    public function handle($request, Closure $next)
    {
        $cuurent_date = Carbon::now()->format('Y-m-d');
        $cuurent_time = Carbon::now()->format('H:i');
        $maintenance = MaintenanceSetting::where(function ($query) {$query->where('type', 2)->orWhere('type', 3);})->whereDate('date', '=', $cuurent_date)->whereTime('to_time', '>=', $cuurent_time)->whereTime('from_time', '<=', $cuurent_time)->orderBy('id', 'desc')->first();
        if(!empty($maintenance)){
            return response()->json(['status' => '205', 'message' => $maintenance->subject_en]);
        }
        
        if (isset($_SERVER['HTTP_AUTHTOKEN'])) {
            $auth_token = $_SERVER['HTTP_AUTHTOKEN'];

            $user = DB::table('personal_access_tokens')->where('token', $auth_token)->first();

            if ($user != null) {
                return $next($request);
            } else {
                $data['status']    = false;
                $data['meassage'] = "Unauthorized Access";
                $data['reason'] = "user not found!";
                return new JsonResponse($data, 403);
            }
        } else {
            $data['status']    = false;
            $data['meassage'] = "Unauthorized Access";
            $data['reason'] = "Token Not Provided";
            return new JsonResponse($data, 403);
        }
    }
}
