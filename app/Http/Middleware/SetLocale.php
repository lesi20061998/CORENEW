<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\App;
use App\Models\Language;
use Illuminate\Support\Facades\Cache;

class SetLocale
{
    /**
     * Handle an incoming request.
     * Simple non-redirection locale setting for consolidated single-codebase testing.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // 1. Determine the locale (Priority: Session > Browser > Default DB Language > Fallback)
        $locale = session('locale');

        if (!$locale) {
            $defaultLang = Cache::remember('default_language_code', 3600, function() {
                return Language::where('is_default', true)->value('code') ?: config('app.fallback_locale');
            });
            $locale = strtolower((string)$defaultLang);
        }

        // 2. Set the application locale
        App::setLocale($locale);

        return $next($request);
    }
}
