<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Cookie;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    public function handle(Request $request, Closure $next): Response
    {
        $locale = $request->cookie('locale');

        if (
            $locale && in_array($locale, ['pl', 'en', 'jpn', 'es', 'ua'])) {
            App::setLocale($locale);
        } else {
            $browserLocale = substr($request->server('HTTP_ACCEPT_LANGUAGE') ?? '', 0, 2);
            if ($browserLocale && in_array($browserLocale, ['pl', 'en', 'jpn', 'es', 'ua'])) {
                App::setLocale($browserLocale);
                Cookie::queue('locale', $browserLocale, 60 * 24 * 60);
            }
        }

        return $next($request);
    }
}
