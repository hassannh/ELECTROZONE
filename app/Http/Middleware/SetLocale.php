<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class SetLocale
{
    protected array $supported = ['ar', 'fr', 'en'];

    public function handle(Request $request, Closure $next)
    {
        // 1. If a language switch was requested via ?lang=xx
        if ($request->has('lang') && in_array($request->lang, $this->supported)) {
            Session::put('locale', $request->lang);
        }

        // 2. Use session locale, else default to Arabic
        $locale = Session::get('locale', 'ar');

        App::setLocale($locale);

        // 3. Set RTL direction in session for use in templates
        Session::put('is_rtl', $locale === 'ar');

        return $next($request);
    }
}
