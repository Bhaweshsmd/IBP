<?php

namespace App\Http\Middleware;

use App\Models\Users;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class AuthorizeUser
{
    public function handle(Request $request, Closure $next)
    {
        if (isset($_SERVER['HTTP_AUTHTOKEN'])) {
            $auth_token = $_SERVER['HTTP_AUTHTOKEN'];

            $user = Users::where('auth_token', $auth_token)->first();

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
