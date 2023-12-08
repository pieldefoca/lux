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
        
        if(Locale::where('code', $pathStart)->where('active', true)->exists()) {
            app()->setLocale($pathStart);
        }

        return $next($request);
    }
}