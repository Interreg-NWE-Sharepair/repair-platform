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

$factory->define(App\Models\DeviceType::class, function (Faker $faker) {
    $colorName = $faker->safeColorName;

    return [
        'name' => $colorName,
        'code' => strtolower($colorName),
        'is_visible' => true,
    ];
});
