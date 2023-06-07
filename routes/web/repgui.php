<?php

use App\Http\Controllers\Repgui\ContactController;
use App\Http\Controllers\Repgui\GuidanceController;
use App\Http\Controllers\Repgui\HomeController;
use App\Http\Controllers\Repgui\LocationController;
use App\Http\Controllers\Repgui\PageController;
use App\Http\Controllers\Repgui\RepairImpactCalculationController;
use App\Http\Controllers\Repgui\TutorialController;
use App\Http\Controllers\Replog\Device\DeviceController;
use App\Http\Controllers\Replog\Repairer\Auth\RepairerRegisterController;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

Route::group([
    'prefix' => LaravelLocalization::setLocale(),
    'middleware' => ['AddLocaleChangeUrls', 'localeSessionRedirect', 'localizationRedirect', 'localeViewPath', 'localize', 'cookieConsent'],
], function () {
    // Static pages
    Route::get(LaravelLocalization::transRoute('routes.home_index'), [HomeController::class, 'index'])->name('home_index');
    Route::get(LaravelLocalization::transRoute('routes.about_project'), [PageController::class, 'about'])->name('about');
    Route::get(LaravelLocalization::transRoute('routes.privacy'), [PageController::class, 'privacy'])->name('privacy');
    Route::get(LaravelLocalization::transRoute('routes.cookies'), [PageController::class, 'cookie'])->name('cookies');
    Route::get(LaravelLocalization::transRoute('routes.terms_conditions'), [PageController::class, 'termsConditions'])->name('terms_conditions');
    Route::get(LaravelLocalization::transRoute('routes.static_page'), [PageController::class, 'page'])->name('static_page');
    Route::get(LaravelLocalization::transRoute('routes.recycle'), [PageController::class, 'recycle'])->name('recycle');

    // Contact form
    Route::get(LaravelLocalization::transRoute('routes.contact_index'), [ContactController::class, 'index'])->name('contact');
    Route::post(LaravelLocalization::transRoute('routes.contact_store'), [ContactController::class, 'store'])->name('contact_store');
    Route::get(LaravelLocalization::transRoute('routes.contact_confirmation'), [ContactController::class, 'confirmation'])->name('contact_confirmation');

    //Tutorials / Guides
    Route::get(LaravelLocalization::transRoute('routes.tutorial_index'), [TutorialController::class, 'index'])->name('tutorial_index');
    Route::get(LaravelLocalization::transRoute('routes.tutorial_show'), [TutorialController::class, 'show'])->name('tutorial_show');
    Route::get(LaravelLocalization::transRoute('routes.tutorial_external'), [TutorialController::class, 'external'])->name('tutorial_external');

    // Tips
    Route::get(LaravelLocalization::transRoute('routes.tips_index'), [PageController::class, 'tips'])->name('tips_index');
    Route::get(LaravelLocalization::transRoute('routes.contribute_index'), [PageController::class, 'contribute'])->name('contribute_index');

    //Guide
    Route::get(LaravelLocalization::transRoute('routes.guide_step_1'), [GuidanceController::class, 'step1'])->name('guide_step_1');
    Route::post(LaravelLocalization::transRoute('routes.guide_step_1_store'), [GuidanceController::class, 'step1Store'])->name('guide_step_1_store');
    Route::get(LaravelLocalization::transRoute('routes.guide_step_2'), [GuidanceController::class, 'step2'])->name('guide_step_2');
    Route::post(LaravelLocalization::transRoute('routes.guide_step_2_store'), [GuidanceController::class, 'step2Store'])->name('guide_step_2_store');
    Route::get(LaravelLocalization::transRoute('routes.guide_step_3'), [GuidanceController::class, 'step3'])->name('guide_step_3');
    Route::get(LaravelLocalization::transRoute('routes.guide_step_diy'), [GuidanceController::class, 'stepDiy'])->name('guide_step_diy');
    Route::get(LaravelLocalization::transRoute('routes.guide_step_map'), [GuidanceController::class, 'guideMap'])->name('guide_step_map');
    Route::get(LaravelLocalization::transRoute('routes.locations_show_redirect'), [LocationController::class, 'redirect'])->name('locations_show_redirect');

    //Locations
    Route::get(LaravelLocalization::transRoute('routes.repair_map_index'), [LocationController::class, 'index'])->name('repair_map_index');
    Route::get(LaravelLocalization::transRoute('routes.locations_show'), [LocationController::class, 'show'])->name('locations_show');

    //registration redirect to repairconnects
    Route::get(LaravelLocalization::transRoute('routes.repairer_register_index'), [RepairerRegisterController::class, 'redirectIndex'])->name('repairer_register_index');
    Route::get(LaravelLocalization::transRoute('routes.device_create'), [DeviceController::class, 'redirectCreate'])->name('device_create');

    //Repair impact tool
    Route::get(LaravelLocalization::transRoute('routes.repair_impact_calculation_index'), [RepairImpactCalculationController::class, 'index'])->name('repair_impact_calculation_index');
    Route::post(LaravelLocalization::transRoute('routes.repair_impact_calculation_index'), [RepairImpactCalculationController::class, 'calculate'])->name('repair_impact_calculation_calculate');
    Route::get(LaravelLocalization::transRoute('routes.repair_impact_calculation_result'), [RepairImpactCalculationController::class, 'result'])->name('repair_impact_calculation_result');
});


