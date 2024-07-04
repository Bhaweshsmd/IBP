<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Auth;
use Session;
use App\Models\Users;
class CheckAccountVerification
{
    public function handle(Request $request, Closure $next)
    {   
               
          if(Session::get('user_id')){
              $is_verified= Users::where('id',Session::get('user_id'))->first()->is_verified??0;
              if(!$is_verified){
                  return redirect('account-verification');
              }
         }
        
        return $next($request);
    }
}
