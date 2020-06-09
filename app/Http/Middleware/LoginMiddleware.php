<?php

namespace App\Http\Middleware;

use App\Models\Employee;
use App\Models\Todo;
use Closure;

class LoginMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // Pre-Middleware Action
        if($request->token)
        {
            $check = Employee::where('api_token', $request->token)->first();

            if(!$check)
            {
                return response('Token Tidak Valid ', 401);
            }else{
                return $next($request);
            }
        }else{
            return response('Silahkan Masukkan Token ', 401);
        }

    }
}
