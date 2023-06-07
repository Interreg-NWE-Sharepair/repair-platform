<?php

namespace App\Observers;

use App\Models\Organisation;

class OrganisationObserver
{
    public function saving(Organisation $organisation): Organisation
    {
        $organisation = $this->checkForOtherLocales($organisation);

        return $organisation;
    }

    public function saved(Organisation $organisation): Organisation
    {
        if (optional($organisation->locales)->isEmpty()) {
            $organisation->generateLocales();
        }

        return $organisation;
    }

    private function checkForOtherLocales(Organisation $organisation)
    {
        $locales = config('app.supported_locales');

        $name = $organisation->name;

        foreach ($locales as $locale) {
            if (!$organisation->hasTranslation('name', $locale)) {
                $organisation->setTranslation('name', $locale, $name);
            }
        }

        return $organisation;
    }
}
