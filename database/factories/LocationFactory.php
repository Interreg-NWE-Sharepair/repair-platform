<?php

use Faker\Generator as Faker;
use Illuminate\Support\Str;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(App\Models\LocationOld::class, function (Faker $faker) {
    $city = $faker->city;

    return [
        'name' => $city,
        'street' => $faker->streetName,
        'number' => $faker->numberBetween(1, 200),
        'postal_code' => $faker->postcode,
        'city' => $city,
        'is_visible' => true,
        'code' => str_replace(' ', '-', strtolower($city)),
    ];
});
