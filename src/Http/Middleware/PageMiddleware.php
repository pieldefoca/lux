<?php

namespace Pieldefoca\Lux\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Pieldefoca\Lux\Models\Page;
use Illuminate\Support\Str;

class PageMiddleware
{
        /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $path = $request->path();

        $locale = app()->currentLocale();
        $path = str($path)->remove("{$locale}/")->toString();
        if($path === $locale) $path = '/';

        $currentPage = Page::where("slug->{$locale}", $path)->first();
        $pageParams = [];

        if(is_null($currentPage)) {
            foreach(Page::all() as $page) {
                if($page->isDynamic()) {
                    $params = $page->getSlugParams();
                    
                    $regex = $page->getSlugRegex();
                    
                    if(str($path)->isMatch($regex)) {
                        $paramValues = str($path)->matchAll($regex);

                        foreach($params as $index => $param) {
                            $pageParams[$param] = $paramValues->get($index);
                        }

                        $currentPage = $page;
                    }
                }
            }
        }

        \View::share([
            'page' => $currentPage,
            'pageParams' => $pageParams,
        ]);

        return $next($request);
    }
}