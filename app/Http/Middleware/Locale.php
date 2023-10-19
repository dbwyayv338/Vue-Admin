<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
use Mcamara\LaravelLocalization\Middleware\LaravelLocalizationMiddlewareBase;

/**
 * Class Locale
 * @package System\Http\Middleware
 * @author Jasmine <youjingqiang@gmail.com>
 */
class Locale extends LaravelLocalizationMiddlewareBase
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param \Closure $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
//        if ($this->shouldIgnore($request)) {
//            return $next($request);
//        }

        if (!auth('web')->guest() && $user = auth('web')->user()) {
            $userLocale = LaravelLocalization::getCurrentLocale();
            foreach(LaravelLocalization::getSupportedLocales() as $key => $value) {
                if ($user->lang === $value['regional']) {
                    $userLocale = $key;
                }
            }
            LaravelLocalization::setLocale($userLocale);
        }

        return $next($request);
    }
}
