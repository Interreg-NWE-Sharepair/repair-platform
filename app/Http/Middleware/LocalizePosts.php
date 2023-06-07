<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\App;
use Mcamara\LaravelLocalization\LanguageNegotiator;

class LocalizePosts
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
        if (!$request->isMethod('POST')) {
            return $next($request);
        }

        $locale = session('locale', false);

        if (empty($locale) && app('laravellocalization')->hideUrlAndAcceptHeader()) {
            // When default locale is hidden and accept language header is true,
            // then compute browser language when no session has been set.
            // Once the session has been set, there is no need
            // to negotiate language from browser again.
            $negotiator = new LanguageNegotiator(app('laravellocalization')->getDefaultLocale(), app('laravellocalization')->getSupportedLocales(), $request);
            $locale = $negotiator->negotiateLanguage();
            session(['locale' => $locale]);
        }

        if ($locale === false) {
            $locale = app('laravellocalization')->getCurrentLocale();
        }
        App::setLocale($locale);

        return $next($request);
    }
}
