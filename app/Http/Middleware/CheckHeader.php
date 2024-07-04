<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Models\MaintenanceSetting;
use Carbon\Carbon;
use DB;

class CheckHeader
{
    public function handle($request, Closure $next)
    {
        $cuurent_date = Carbon::now()->format('Y-m-d');
        $cuurent_time = Carbon::now()->format('H:i');
        $maintenance = MaintenanceSetting::where(function ($query) {$query->where('type', 2)->orWhere('type', 3);})->whereDate('date', '=', $cuurent_date)->whereTime('to_time', '>=', $cuurent_time)->whereTime('from_time', '<=', $cuurent_time)->orderBy('id', 'desc')->first();
        if(!empty($maintenance)){
            return response()->json(['status' => '205', 'message' => $maintenance->subject_en]);
        }
        
        return $next($request);
    }
}
