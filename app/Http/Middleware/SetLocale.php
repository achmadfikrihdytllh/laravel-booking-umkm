<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class SetLocale
{
    public const SUPPORTED = ['id', 'en'];

    public function handle(Request $request, Closure $next)
    {
        $locale = $request->session()->get('locale');

        if (! in_array($locale, self::SUPPORTED, true)) {
            // Pertama kali: ikuti bahasa browser. Selain Inggris -> Indonesia.
            $pref = strtolower(substr((string) $request->getPreferredLanguage(['id', 'en']), 0, 2));
            $locale = in_array($pref, self::SUPPORTED, true) ? $pref : 'id';
            $header = strtolower((string) $request->header('Accept-Language'));
            if ($header === '') {
                $locale = 'id';
            }
        }

        app()->setLocale($locale);
        Carbon::setLocale($locale);

        return $next($request);
    }
}
