<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Cookie;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    public function handle(Request $request, Closure $next): Response
    {
        $locale = $request->cookie('locale') ?? session('locale');

        \Log::debug('Locale from cookie: ' . $locale);

        $availableLocales = ['pl', 'en', 'jpn', 'es', 'ua'];

        if ($locale && in_array($locale, $availableLocales)) {
            $this->setLocaleWithCache($locale);
        } else {
            $browserLocale = substr($request->server('HTTP_ACCEPT_LANGUAGE') ?? '', 0, 2);

            $localeMap = [
                'ja' => 'jpn',
                'uk' => 'ua'
            ];

            if (isset($localeMap[$browserLocale])) {
                $browserLocale = $localeMap[$browserLocale];
            }

            if ($browserLocale && in_array($browserLocale, $availableLocales)) {
                $this->setLocaleWithCache($browserLocale);
                Cookie::queue('locale', $browserLocale, 60 * 24 * 60);
            } else {
                $this->setLocaleWithCache('en');
            }
        }

        return $next($request);
    }

    protected function setLocaleWithCache(string $locale): void
    {
        \Log::debug("Setting locale to: {$locale}");
        App::setLocale($locale);
        session(['locale' => $locale]);

        $cacheKey = "translations_{$locale}";
        $cacheTtl = 60 * 60 * 24; //24 h

        if (!Cache::has($cacheKey) || request()->has('refresh_cache')) {
            \Log::debug("Building translations cache for locale: {$locale}");
            $translations = [];

            $messagesFile = lang_path("{$locale}/messages.php");
            if (file_exists($messagesFile)) {
                $translations['messages'] = require $messagesFile;
                \Log::debug("Loaded messages file for locale: {$locale}");
            } else {
                \Log::warning("Messages file not found for locale: {$locale}");
            }

            $validationFile = lang_path("{$locale}/validation.php");
            if (file_exists($validationFile)) {
                $translations['validation'] = require $validationFile;
            }

            $authFile = lang_path("{$locale}/auth.php");
            if (file_exists($authFile)) {
                $translations['auth'] = require $authFile;
            }

            Cache::put($cacheKey, $translations, $cacheTtl);
            \Log::debug("Translations cache built for locale: {$locale}");
        }
    }
}
