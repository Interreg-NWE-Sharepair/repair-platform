<?php

namespace App\Http\Middleware;

use Closure;
use Inertia\Inertia;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class AddLocaleChangeUrls
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
        if ($request->expectsJson()) {
            return $next($request);
        }

        //First get the current locale for the route
        $currentLocale = app()->getLocale();
        setlocale(LC_ALL, config("laravellocalization.supportedLocales.$currentLocale.regional"));

        foreach (config('app.supported_locales') as $locale) {
            //Set the locale so for example slugs will be translated
            app()->setLocale($locale);
            $localeUrls[] = [
                'locale' => $locale,
                'url' => LaravelLocalization::getLocalizedURL($locale),
                'isCurrent' => ($locale === LaravelLocalization::getCurrentLocale()),
            ];
        }

        //Set locale back to the one you're in
        app()->setLocale($currentLocale);

        Inertia::share('localeButtons', $localeUrls ?? []);

        return $next($request);
    }
}
