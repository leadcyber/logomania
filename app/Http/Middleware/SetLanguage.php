<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetLanguage
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Get the lang parameter from the query string
        $lang = $request->query('lang');

        // If lang is not in the query, check the session
        if (!$lang) {
            $lang = session('lang', config('app.locale'));
        } else {
            // Validate that the language is supported
            $supportedLocales = config('app.supported_locales', []);
            if (!in_array($lang, $supportedLocales)) {
                $lang = config('app.locale');
            }

            // Set the application locale in the session
            session(['lang' => $lang]);
        }

        // Set the application locale
        app()->setLocale($lang);

        return $next($request);
    }
}
