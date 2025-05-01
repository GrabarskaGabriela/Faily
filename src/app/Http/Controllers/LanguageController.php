<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;

class LanguageController extends Controller
{
    public function changeLanguage(Request $request)
    {
        $locale = $request->locale;

        if (in_array($locale, ['pl', 'en', 'jpn', 'es', 'ua'])) {
            Cookie::queue('locale', $locale, 60 * 24 * 60);
        }

        return redirect()->back();
    }
}
