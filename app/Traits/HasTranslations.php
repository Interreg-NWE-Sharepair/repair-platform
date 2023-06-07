<?php

namespace App\Traits;

use Spatie\Translatable\HasTranslations as BaseHasTranslations;

trait HasTranslations
{
    use BaseHasTranslations;

    public function getTranslation(string $key, string $locale, bool $useFallbackLocale = false)
    {
        $locale = $this->normalizeLocale($key, $locale, $useFallbackLocale);

        $translations = $this->getTranslations($key);

        $translation = $translations[$locale] ?? '';

        //If default locale 'en' isn't found, get first next existing locale (this is a workaround for nova)
        if (!$translation) {
            $locale = array_key_first($translations);

            return $translations[$locale] ?? '';
        }

        if ($this->hasGetMutator($key)) {
            return $this->mutateAttribute($key, $translation);
        }

        return $translation;
    }

    public function getLimitedTranslations($key, $withFallback = true)
    {
        $possibleLocales = config('app.supported_locales');
        $queryLocaleFallback = request()->input('locale_fallback') ? parseQueryBoolean(request()->input('locale_fallback')) : null;

        $translations = $this->getTranslations($key);
        $withFallback = $queryLocaleFallback ?? $withFallback;

        $parsedTranslations = [];
        $needsFallback = false;
        foreach ($possibleLocales as $possibleLocale) {
            //dd($translations);
            $value = $translations[$possibleLocale] ?? null;
            if (!$value) {
                $needsFallback = true;
            }
            $parsedTranslations[$possibleLocale] = $value;
        }

        if ($withFallback && $needsFallback) {
            $parsedTranslations['default'] = $this->findFallbackTranslation($translations);
        }

        return $parsedTranslations;
    }

    private function findFallbackTranslation($translations)
    {
        $fallbackTranslation = $translations[config('translatable.fallback_locale', 'en')] ?? null;
        if (!$fallbackTranslation) {
            $fallbackTranslation = $translations[array_key_first($translations)] ?? null;
        }

        return $fallbackTranslation;
    }
}
