<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\App;

class ChangeLocaleByQuery
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if ($request->has('lang')) {
            $locale = $request->get('lang');
            $locales = array_keys(config('translatable.default_locales'));
            if (in_array($locale, $locales)) {
                App::setLocale($locale);
                setlocale(LC_ALL, config("laravellocalization.supportedLocales.$locale.regional"));
            }
        }

        return $next($request);
    }
}
