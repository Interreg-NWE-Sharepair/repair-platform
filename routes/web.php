<?php

use App\Http\Controllers\Api\ChangelogController;
use App\Http\Controllers\Statistics\StatisticsController;
use App\Http\Controllers\TutorialEmbed\TutorialEmbedController;

if (siteIsRepgui()){
    Route::group([
            'middleware' => ['repgui']
    ], __DIR__.'/web/repgui.php');
} else {
    Route::group([
        'middleware' => ['replog']
    ], __DIR__.'/web/replog.php');
}

Route::get('api/changelog', [ChangelogController::class, 'index'])->name('api.changelog');

Route::get('api/repair/statistics', [StatisticsController::class, 'index'])->middleware('changeLocaleByQuery');

Route::get('api/guidance/tutorials', [TutorialEmbedController::class, 'index'])->middleware('changeLocaleByQuery');
Route::get('api/guidance/tutorials/{repairTutorial}', [TutorialEmbedController::class, 'show'])->middleware('changeLocaleByQuery')->name('api.tutorial.show');
route::get('api/guidance/external', [TutorialEmbedController::class, 'external'])->middleware('changeLocaleByQuery')->name('api.tutorial.external');
Route::get('api/guidance/documentation', [TutorialEmbedController::class, 'documentation']);

Route::mediaLibrary();

