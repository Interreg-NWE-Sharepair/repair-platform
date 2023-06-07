<?php

namespace App\Services;

use App\Models\Location;
use Exception;
use maxh\Nominatim\Nominatim;

class LocationGeoService
{
    public static function geocodeLocation(Location $location)
    {
        try {
            // url encode the address
            $url = 'https://nominatim.openstreetmap.org';

            //Adding guzzle client ourselfs to disable ssl verification as it for some reason is not working at the time.
            $nominatim = new Nominatim($url);

            if (!$location->city) {
                return null;
            }

            $search = $nominatim->newSearch()->city($location->city);
            if ($location->country) {
                $search->country($location->country);
            }
            if ($location->street) {
                $search->street($location->street . ' ' . $location->number);
            }
            if ($location->postal_code) {
                $search->postalCode($location->postal_code);
            }

            $search->limit(1)->polygon('geojson')->addressDetails();

            $result = $nominatim->find($search);

            if (empty($result)) {
                return null;
            }
            $place = $result[0];

            if (isset($place['lat'], $place['lon'])) {
                $latitude = $place['lat'];
                $longitude = $place['lon'];
            } else {
                $geo = $place['geojson']['coordinates'];

                //Multiple coordinates
                if (is_array($geo[0]) && is_array($geo[0][0])) {
                    $geo = array_shift($geo[0]);
                }
                // One level deeper if the coordinate has more coordinates
                if (is_array($geo[0]) && is_array($geo[0][0])) {
                    $geo = array_shift($geo[0]);
                }

                [
                    $longitude,
                    $latitude,
                ] = $geo;
            }

            return [
                'lng' => $longitude,
                'lat' => $latitude,
            ];
        } catch (Exception $e) {
            report($e);
        }

        return null;
    }
}
