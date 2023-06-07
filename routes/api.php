<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

//Could be split into different route files per version later
Route::group([
    'prefix' => 'v1',
], function () {
    Route::get('/', [\App\Http\Controllers\Api\V1\LocationController::class, 'index'])->name('');

    //Locations
    Route::get('locations', [\App\Http\Controllers\Api\V1\LocationController::class, 'index']);
    Route::get('locations/{location:uuid}', [\App\Http\Controllers\Api\V1\LocationController::class, 'show']);

    //Suggestions
    Route::post('locations/suggestions', [\App\Http\Controllers\Api\V1\LocationController::class, 'suggestNew']);
    Route::post('locations/{location:uuid}/suggestions', [\App\Http\Controllers\Api\V1\LocationController::class, 'suggestChange']);

    //Organisation types
    Route::get('organisation_types', [\App\Http\Controllers\Api\V1\OrganisationTypeController::class, 'index']);
    Route::get('organisation_types/{organisation_type:uuid}', [\App\Http\Controllers\Api\V1\OrganisationTypeController::class, 'show']);

    //Device types
    Route::get('product_categories', [\App\Http\Controllers\Api\V1\DeviceTypeController::class, 'index']); //Deprecated
    Route::get('product_categories/{device_type:uuid}', [\App\Http\Controllers\Api\V1\DeviceTypeController::class, 'show']); //Deprecated
    Route::get('device_types', [\App\Http\Controllers\Api\V1\DeviceTypeController::class, 'index']);
    Route::get('device_types/{device_type:uuid}', [\App\Http\Controllers\Api\V1\DeviceTypeController::class, 'show']);

    //Repair statuses
    Route::get('completed_repair_statuses', [\App\Http\Controllers\Api\V1\CompletedRepairStatusController::class, 'index']);

    //Repair devices
    Route::get('devices', [\App\Http\Controllers\Api\V1\DeviceController::class, 'index']);
    Route::post('devices/log/completed', [\App\Http\Controllers\Api\V1\DeviceController::class, 'logCompletedDevice']);


    //Activity sectors
    Route::get('activity_sectors', [\App\Http\Controllers\Api\V1\ActivitySectorController::class, 'index']);
    Route::get('activity_sectors/{activity_sector:uuid}', [\App\Http\Controllers\Api\V1\ActivitySectorController::class, 'show']);
});
