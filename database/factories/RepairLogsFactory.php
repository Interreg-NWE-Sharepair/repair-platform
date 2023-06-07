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

$factory->define(App\Models\RepairLog::class, function (Faker $faker) {
    return [
        'repair_status_id' => RepairStatus::all()->random()->id,
        'device_id' => Device::all()->random()->id,
        'repairer_id' => RepairerOld::all()->random()->id,
    ];
});
