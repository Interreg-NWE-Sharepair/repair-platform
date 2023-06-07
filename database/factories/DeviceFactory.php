<?php

use App\Models\DeviceType;
use App\Models\LocationOld;
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

$factory->define(App\Models\Device::class, function (Faker $faker) {
    return [
        'brand_name' => $faker->domainWord,
        'model_name' => $faker->numberBetween(1000, 10000),
        'device_type_id' => DeviceType::all()->random()->id,
        'issue_description' => $faker->domainWord,
        'location_id' => LocationOld::all()->random()->id,
        'first_name' => $faker->firstName,
        'last_name' => $faker->lastName,
        'email' => $faker->email,
        'telephone' => $faker->phoneNumber,
        'closed_at' => now(),
    ];
});
