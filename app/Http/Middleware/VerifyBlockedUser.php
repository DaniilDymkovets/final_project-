<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class VerifyBlockedUser
{
    /**
     * Проверяем Забюлокирован пользователь или нет
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (Auth::guard('admin')->check()) {
            return $next($request);
        }
        
        if (Auth::guard()->check()) {     
            if(!Auth::guard()->user()->status) { 
                if (!$request->routeIs('user.logout')) {
                    return  abort(403, "Пользователь заблокирован администратором"); 
                }
            }
        }
        
        return $next($request);
    }
}
