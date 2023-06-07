<?php

use App\Models\Device;
use App\Models\LocationOld;
use App\Models\RepairerOld;
use App\Models\RepairStatus;
use Faker\Generator as Faker;

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

$factory->define(App\Models\RepairLogLink::class, function (Faker $faker) {
    return [
        'url' => $faker->url,
    ];
});
