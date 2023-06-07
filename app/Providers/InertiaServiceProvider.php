<?php

namespace App\Providers;

use App\Facades\EmployeeOrganisationRepository;
use App\Facades\PersonRepository;
use App\Facades\UserLocationRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\ServiceProvider;
use Inertia\Inertia;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class InertiaServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        Inertia::share([
            'app' => function () {
                return [
                    'name' => config('app.name'),
                    'url' => config('app.url'),
                ];
            },
            'locales' => function () {
                return LaravelLocalization::getSupportedLocales() ?? [];
            },
            'locale' => function () {
                return app()->getLocale();
            },
            'fallback_locale' => function () {
                if (app()->getLocale() === 'nl') {
                    return 'en';
                }

                return 'nl';
            },
            'user' => function () {

                /** @var \App\Models\User $user */
                $user = Auth::user();

                if (!$user) {
                    return null;
                }

                $person = PersonRepository::getByUser($user)->firstOrFail();
                $isRepairer = EmployeeOrganisationRepository::isRepairer($person);

                if (EmployeeOrganisationRepository::isNovaAdmin($person) || $user->hasAnyRole(['statik', 'admin'])) {
                    \session([
                        'info' => trans('messages.warning_logged_in_admin'),
                        [],
                        app()->getLocale(),
                    ]);
                } else {
                    \session(['success' => null]);
                }

                return [
                    'name' => $user->name,
                    'organisations' => EmployeeOrganisationRepository::getOrganisations($person)->all(),
                    'hasRepairer' => $isRepairer,
                ];
            },
            'language' => function () {
                $locale = app()->getLocale();
                $translations = [];

                $json = lang_path($locale . '.json');
                if (File::exists($json)) {
                    $translations += json_decode(file_get_contents($json), true);
                }

                $folder = lang_path($locale);
                if (File::exists($folder)) {
                    $files = File::files($folder);
                    foreach ($files as $file) {
                        $translationGroup = $file->getFilenameWithoutExtension();
                        $groupedTranslationLines = trans($translationGroup);
                        $namedTranslationLines = [];
                        if (is_array($groupedTranslationLines)) {
                            foreach ($groupedTranslationLines as $key => $line) {
                                $namedTranslationLines[$translationGroup . '.' . $key] = $line;
                            }

                            $translations += $namedTranslationLines;
                        }
                    }
                }

                return $translations;
            },
            'errors' => function () {
                if (is_string(Session::get('errors'))) {
                    return Session::get('errors') ?: (string)null;
                } else {
                    return Session::get('errors') ? Session::get('errors')->getBag('default')->getMessages() : (object)[];
                }
            },
            'success' => function () {
                return Session::get('success') ?: (string)null;
            },
            'warning' => function () {
                return Session::get('warning') ?: (string)null;
            },
            'info' => function () {
                return Session::get('info') ?: (string)null;
            },
        ]);
    }
}
