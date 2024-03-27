<?php

namespace Pieldefoca\Lux\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Route;
use Livewire\Livewire;

class LuxMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        config([
            'livewire.layout' => 'lux::components.layouts.app',
            'livewire.class_namespace' => 'Pieldefoca\\Lux\\Livewire',
        ]);

        View::share([
            'lang' => session('luxLang', config('lux.locales')[0]),
        ]);

        return $next($request);
    }
}