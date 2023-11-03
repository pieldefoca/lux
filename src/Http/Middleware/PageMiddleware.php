<?php

namespace Pieldefoca\Lux\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Pieldefoca\Lux\Models\Locale;
use Illuminate\Support\Facades\Route;
use Pieldefoca\Lux\Models\Page;
use Symfony\Component\HttpFoundation\Response;

class PageMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $defaultLocale = Locale::default();

        $path = $request->path();

        if(str($path)->startsWith($defaultLocale->code.'/')) {
            return redirect(str($path)->replace($defaultLocale->code.'/', ''));
        }

        $locales = Locale::active()
            ->where('default', false)
            ->get()
            ->map(fn($locale) => $locale->code);

        foreach($locales as $locale) {
            if(str($request->path())->startsWith("{$locale}/") || $request->path() === $locale) {
                app()->setLocale($locale);
            }
        }

        return $next($request);
    }
}
