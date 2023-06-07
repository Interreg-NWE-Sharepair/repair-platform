<?php

namespace App\Observers;

use App\Models\Location;
use App\Services\LocationGeoService;

class LocationObserver
{
    public function saving(Location $location)
    {
        $longlat = LocationGeoService::geocodeLocation($location);

        if ($longlat) {
            $location->latitude = $longlat['lat'];
            $location->longitude = $longlat['lng'];
        }

        return $location;
    }

    public function saved(Location $location)
    {
        if (!$location->isDirty('organisation')){
            $location->load('organisation');
        }
        if ($location->organisation) {
            if (optional($location->organisation->locales)->isEmpty()) {
                $location->organisation->generateLocales($location->getUsedLocales());
            }
        }
    }
}
