<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Cookie;

class LanguageController extends Controller
{
    public function changeLanguage($locale, Request $request)
    {
        $availableLocales = ['pl', 'en', 'jpn', 'es', 'ua'];

        \Log::debug('Trying to change language to: ' . $locale);

        if (!empty($locale) && in_array($locale, $availableLocales)) {
            \Log::debug('Setting new locale cookie and session: ' . $locale);

            Cookie::queue(Cookie::make('locale', $locale, 60 * 24 * 60, null, null, false, false));

            session(['locale' => $locale]);

            app()->setLocale($locale);

            $userId = auth()->id() ?? 'guest';

            \Log::debug('Clearing cache for locale: ' . $locale);

            foreach ($availableLocales as $availableLocale) {
                Cache::forget("translations_{$availableLocale}");
                Cache::forget("navbar_{$availableLocale}_{$userId}");
                Cache::forget("footer_{$availableLocale}");
            }

            if (Cache::has('navbar_keys')) {
                $navbarKeys = Cache::get('navbar_keys', []);
                foreach ($navbarKeys as $key) {
                    Cache::forget($key);
                }
            }

            return redirect()->back()->withInput(['refresh_cache' => true]);
        }

        \Log::warning('Invalid locale attempted: ' . $locale);
        return redirect()->back();
    }
}
