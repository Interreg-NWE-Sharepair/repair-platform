<?php

return [
    'access_token' => env('MAPBOX_API_KEY'),
    'country' => 'be,nl,fr,de,lu,gb,ch,at,ie',
    'limit' => 10,
    'types' => 'place,locality,postcode',
    'fuzzyMatch' => false,
];
