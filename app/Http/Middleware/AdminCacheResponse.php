<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminCacheResponse
{
    public function handle(Request $request, Closure $next): Response
    {
        // Only cache if enabled, GET requests, and for authenticated admins
        if (env('ADMIN_CACHE_ENABLED', true) && $request->isMethod('get') && Auth::check() && Auth::user()->isAdmin()) {
            
            // Use a versioning system to clear all admin cache instantly when data changes
            $version = Cache::get('admin_cache_version', 1);
            $url = $request->fullUrl();
            $cacheKey = "admin_v{$version}_" . md5($url);

            // If cache exists, return it
            if (Cache::has($cacheKey) && !$request->has('refresh_cache')) {
                $content = Cache::get($cacheKey);
                return response($content)->header('X-Admin-Cache', 'HIT');
            }

            // Otherwise, get the response
            $response = $next($request);

            // Store it if it's a successful HTML response
            if ($response->isSuccessful() && str_contains($response->headers->get('Content-Type'), 'text/html')) {
                // Store for 24 hours (or until version changes)
                Cache::put($cacheKey, $response->getContent(), 86400);
            }

            return $response->header('X-Admin-Cache', 'MISS');
        }

        return $next($request);
    }
}
