<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;

class UserLocaleByRoute
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
        $user = Auth::user();
        if (!$user) {
            return $next($request);
        }

        $userLocale = $user->locale;
        $locale = App::getLocale();

        if ($locale === $userLocale) {
            return $next($request);
        }

        $this->updateUserLocale($user, $locale);

        return $next($request);
    }

    private function updateUserLocale($user, $locale)
    {
        once(function () use ($user, $locale) {
            $user->locale = $locale;
            $user->save();
        });
    }
}
