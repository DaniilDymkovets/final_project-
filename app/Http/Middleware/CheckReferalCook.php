<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Cookie;
use App\Facades\SystemSettings;


class CheckReferalCook
{
    /**
     * Проверяем наличие параметра referal во всех запросах,
     * при наличии пишем в одноимённуюю куку.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $name_cook_ref = SystemSettings::get('referal_link')?:'referal';
        if ($request->{$name_cook_ref}) {
            //проверка на существование такого реферального кода
            if(\App\Models\UserProfile::where('referal',$request->{$name_cook_ref})->first()) {
            Cookie::queue(Cookie::make($name_cook_ref, $request->{$name_cook_ref}, 2628000));
            }
        }
        return $next($request);
    }
}
