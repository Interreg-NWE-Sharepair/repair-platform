<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class ChangeDefaultLocalesByQuery
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $queryLocales = parseQueryArray(request()->input('locales'));

        if (is_iterable($queryLocales)) {
            $defaultLocales = config('app.supported_locales');

            $queryLocales = Arr::where($queryLocales, function ($locale) use ($defaultLocales) {
                return in_array($locale, $defaultLocales);
            });

            config()->set('app.supported_locales', $queryLocales);

            return $next($request);
        }

        return $next($request);
    }
}
