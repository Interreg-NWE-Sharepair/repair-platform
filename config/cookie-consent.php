<?php

return [
    'policyUrl_nl' => env('COOKIE_POLICY_URL_NL', null),
    'policyUrl_en' => env('COOKIE_POLICY_URL_EN', null),
    'policyUrl_fr' => env('COOKIE_POLICY_URL_FR', null),
    'policyUrl_de' => env('COOKIE_POLICY_URL_DE', null),
    'ignored_paths' => ['/nl/cookie-policy', '/en/cookie-policy', '/fr/cookie-policy', '/de/cookie-policy'],
    'cookie_key' => '__cookie_consent',
    'policy_url_nl' => env('COOKIE_POLICY_REPGUI_URL_NL', null),
    'policy_url_en' => env('COOKIE_POLICY_REPGUI_URL_EN', null),
    'policy_url_fr' => env('COOKIE_POLICY_REPGUI_URL_FR', null),
    'policy_url_de' => env('COOKIE_POLICY_REPGUI_URL_DE', null),
    'cookie_value_analytics' => '2',
    'cookie_value_marketing' => '3',
    'cookie_value_both' => 'true',
    'cookie_value_none' => 'false',
    'cookie_expiration_days' => '365',
    'gtm_event' => 'cookie_refresh',
];
