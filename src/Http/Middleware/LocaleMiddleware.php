<?php

namespace Pieldefoca\Lux\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Pieldefoca\Lux\Models\Locale;

class LocaleMiddleware
{
        /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $pathStart = explode('/', $request->path())[0];
        
        if(in_array($pathStart, config('lux.locales'))) {
            app()->setLocale($pathStart);
        }

        return $next($request);
    }
}