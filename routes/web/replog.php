<?php

use App\Http\Controllers\Person\PersonController;
use App\Http\Controllers\Replog\Api\DeviceController as ApiDeviceController;
use App\Http\Controllers\Replog\Api\OrganisationController as ApiOrganisationController;
use App\Http\Controllers\Replog\Api\RepairerController as ApiRepairerController;
use App\Http\Controllers\Replog\ContactController;
use App\Http\Controllers\Replog\Device\DeviceController;
use App\Http\Controllers\Replog\Event\EventController;
use App\Http\Controllers\Replog\HomeController;
use App\Http\Controllers\Replog\Organisation\OrganisationController;
use App\Http\Controllers\Replog\Organisation\OrganisationDashboardController;
use App\Http\Controllers\Replog\PageController;
use App\Http\Controllers\Replog\Repairer\Auth\RepairerLoginController;
use App\Http\Controllers\Replog\Repairer\Auth\RepairerRegisterController;
use App\Http\Controllers\Replog\Repairer\Device\DashboardController;
use App\Http\Controllers\Replog\Repairer\Device\DeviceDetailController;
use App\Http\Controllers\Replog\Repairer\Device\DeviceRepairLogController;
use App\Http\Controllers\Replog\Repairer\Device\DeviceRepairLogNoteController;
use App\Http\Controllers\Replog\Repairer\RepairLogController;
use App\Models\Contact;
use App\Models\Device;
use App\Models\OrganisationRequest;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

Route::group([
    'middleware' => ['localizePosts'],
], function () {
    Route::post(LaravelLocalization::transRoute('routes.device_store'), [DeviceController::class, 'store'])->name('device_store');
});

Route::group([
    'prefix' => LaravelLocalization::setLocale(),
    'middleware' => ['AddLocaleChangeUrls', 'localeSessionRedirect', 'localizationRedirect', 'localeViewPath', 'localize'],
], function () {
    Auth::routes(['verify' => true]);
    Route::get('logout', '\App\Http\Controllers\Auth\LoginController@logout');
    /** ADD ALL LOCALIZED ROUTES INSIDE THIS GROUP **/
    //Employee(repairer) pages
    Route::get(LaravelLocalization::transRoute('routes.repairer_login_index'), [RepairerLoginController::class, 'index'])->name('repairer_login_index');
    Route::post(LaravelLocalization::transRoute('routes.repairer_login_store'), [RepairerLoginController::class, 'store'])->name('repairer_login_store');
    Route::get(LaravelLocalization::transRoute('routes.repairer_register_index'), [RepairerRegisterController::class, 'index'])->name('repairer_register_index');
    Route::post(LaravelLocalization::transRoute('routes.repairer_register_step_0_store'), [RepairerRegisterController::class, 'storeStep0'])->name('repairer_register_step_0_store');
    Route::post(LaravelLocalization::transRoute('routes.repairer_register_step_1_store'), [RepairerRegisterController::class, 'storeStep1'])->name('repairer_register_step_1_store');
    Route::get(LaravelLocalization::transRoute('routes.repairer_register_confirmation'), [RepairerRegisterController::class, 'confirmation'])->name('repairer_register_confirmation');
    //Static content Pages
    Route::get(LaravelLocalization::transRoute('routes.home_index'), [HomeController::class, 'index'])->name('home_index');
    Route::get(LaravelLocalization::transRoute('routes.about'), [PageController::class, 'about'])->name('about');
    Route::get(LaravelLocalization::transRoute('routes.privacy'), [PageController::class, 'privacy'])->name('privacy');
    Route::get(LaravelLocalization::transRoute('routes.cookies'), [PageController::class, 'cookie'])->name('cookies');
    Route::get(LaravelLocalization::transRoute('routes.terms_conditions'), [PageController::class, 'termsConditions'])->name('terms_conditions');
    Route::get(LaravelLocalization::transRoute('routes.participation'), [PageController::class, 'participate'])->name('participation');
    Route::get(LaravelLocalization::transRoute('routes.instructions'), [PageController::class, 'instructions'])->name('instructions');
    // Generated pages based on the dynamic content in Nova Page model
    Route::get(LaravelLocalization::transRoute('routes.static_page'), [PageController::class, 'page'])->name('static_page');
    //Device Create
    Route::get(LaravelLocalization::transRoute('routes.device_create'), [DeviceController::class, 'create'])->name('device_create');
    Route::get(LaravelLocalization::transRoute('routes.device_confirmation'), [DeviceController::class, 'confirmation'])->name('device_confirmation');
    Route::post(LaravelLocalization::transRoute('routes.device_step_0_store'), [DeviceController::class, 'storeStep0'])->name('device_step_0_store');
    Route::post(LaravelLocalization::transRoute('routes.device_step_1_store'), [DeviceController::class, 'storeStep1'])->name('device_step_1_store');
    Route::post(LaravelLocalization::transRoute('routes.device_step_2_store'), [DeviceController::class, 'storeStep2'])->name('device_step_2_store');
    //Contact form
    Route::get(LaravelLocalization::transRoute('routes.contact_index'), [ContactController::class, 'index'])->name('contact_index');
    Route::post(LaravelLocalization::transRoute('routes.contact_store'), [ContactController::class, 'store'])->name('contact_store');
    Route::get(LaravelLocalization::transRoute('routes.contact_confirmation'), [ContactController::class, 'confirmation'])->name('contact_confirmation');
    //Organisation detail
    Route::get(LaravelLocalization::transRoute('routes.location_index'), [OrganisationController::class, 'index'])->name('location_index');
    Route::get(LaravelLocalization::transRoute('routes.location_show'), [OrganisationController::class, 'show'])->name('location_show');
    //Register repair organisation
    Route::get(LaravelLocalization::transRoute('routes.location_create'), [OrganisationController::class, 'create'])->name('location_create');
    Route::post(LaravelLocalization::transRoute('routes.location_store'), [OrganisationController::class, 'store'])->name('location_store');
    Route::get(LaravelLocalization::transRoute('routes.location_confirmation'), [OrganisationController::class, 'confirmation'])->name('location_confirmation');

    Route::get(LaravelLocalization::transRoute('routes.device_show_repaired'), [DeviceController::class, 'show'])->name('device_show_repaired');

    Route::get('/error', function () {
        return Inertia::render('Error', [
            'status' => 404,
        ]);
    });

    Route::get('/mails/{id?}', function ($id = null) {
        $user = auth()->user();
        if ($user && ($user->hasRole('admin') || $user->hasRole('statik') || $user->hasRole('entity-admin'))) {
            if (! $id) {
                return view('pages.mails.overview');
            }
            if ($id == 1) {
                return (new App\Http\Services\MailService)->sendContactMail(Contact::inRandomOrder()->first(), true);
            }
            if ($id == 2) {
                return (new App\Http\Services\MailService)->sendDeviceRegisteredMail(Device::inRandomOrder()->first(), true);
            }
            if ($id == 3) {
                return (new App\Http\Services\MailService)->sendOpenDevicesToRepairer(\App\Models\Person::inRandomOrder()->first(), Device::inRandomOrder()->limit(4)->get(), \App\Models\Organisation::inRandomOrder()->first(), 4, true);
            }

            if ($id == 7) {
                return (new App\Http\Services\MailService)->sendOrganisationSuggestionMail(OrganisationRequest::inRandomOrder()->first(), true);
            }

            return 'No mail found with ID: '.$id;
        }
        abort(404);
    });
});

//Authenticated Localized routes
Route::group([
    'prefix' => LaravelLocalization::setLocale(),
    'middleware' => ['auth', 'AddLocaleChangeUrls', 'localeSessionRedirect', 'localizationRedirect', 'localeViewPath', 'localize', 'userLocaleByRoute'],
], function () {
    //Employee Dashboard overviews
    Route::get(LaravelLocalization::transRoute('routes.repairer_dashboard'), [DashboardController::class, 'index'])->name('repairer_dashboard');
    Route::get(LaravelLocalization::transRoute('routes.person_profile_show'), [PersonController::class, 'show'])->name('person_profile_show');
    Route::post(LaravelLocalization::transRoute('routes.person_profile_store'), [PersonController::class, 'store'])->name('person_profile_store');

    //Device details and repair start
    Route::get(LaravelLocalization::transRoute('routes.device_show'), [DeviceDetailController::class, 'show'])->name('device_show');
    Route::get(LaravelLocalization::transRoute('routes.device_select_repair'), [DeviceDetailController::class, 'deviceSelectRepair'])->name('device_select_repair')->middleware('checkIfRepairer');
    Route::get(LaravelLocalization::transRoute('routes.device_confirm_repair'), [DeviceDetailController::class, 'deviceConfirmSelectRepair'])->name('device_confirm_repair')->middleware('checkIfRepairer');
    Route::get(LaravelLocalization::transRoute('routes.device_start_repair'), [DeviceDetailController::class, 'deviceStartRepair'])->name('device_start_repair')->middleware('checkIfRepairer');

    //Device logs details + Edits
    Route::post(LaravelLocalization::transRoute('routes.device_log_update_device'), [DeviceRepairLogController::class, 'updateDevice'])->name('repair_log_edit_device')->middleware('checkIfRepairer');
    Route::post(LaravelLocalization::transRoute('routes.device_log_repaired_edit'), [DeviceRepairLogController::class, 'updateLog'])->name('device_log_repaired_edit');
    Route::post(LaravelLocalization::transRoute('routes.device_log_repaired_reopen'), [DeviceRepairLogController::class, 'reopenDevice'])->name('device_log_repaired_reopen');
    Route::post(LaravelLocalization::transRoute('routes.device_log_repaired_store'), [DeviceRepairLogController::class, 'closeLog'])->name('device_log_repaired_store');
    Route::get(LaravelLocalization::transRoute('routes.device_log_show'), [DeviceRepairLogController::class, 'show'])->name('device_log_show');
    Route::post(LaravelLocalization::transRoute('routes.device_log_note_add'), [DeviceRepairLogNoteController::class, 'add'])->name('device_log_note_add')->middleware('checkIfRepairer');
    Route::get(LaravelLocalization::transRoute('routes.device_log_note_edit'), [DeviceRepairLogNoteController::class, 'edit'])->name('device_log_note_edit')->middleware('checkIfRepairer');
    Route::post(LaravelLocalization::transRoute('routes.device_log_note_update'), [DeviceRepairLogNoteController::class, 'update'])->name('device_log_note_update')->middleware('checkIfRepairer');
    Route::post(LaravelLocalization::transRoute('routes.device_contact_edit'), [DeviceRepairLogController::class, 'updateContact'])->name('device_contact_edit');

    Route::post(LaravelLocalization::transRoute('routes.device_note_add'), [DeviceController::class, 'addNote'])->name('device_note_add');
    Route::post(LaravelLocalization::transRoute('routes.repair_log_edit_notes'), [DeviceRepairLogController::class, 'updateRepairLogNotes'])->name('repair_log_edit_notes');
    Route::post(LaravelLocalization::transRoute('routes.device_close_store'), [DeviceRepairLogController::class, 'closeDevice'])->name('device_close_store');

    // Device detail Event Organizer
    Route::get(LaravelLocalization::transRoute('routes.device_unlink_event'), [DeviceDetailController::class, 'unlinkEvent'])->name('device_unlink_event');
    Route::get(LaravelLocalization::transRoute('routes.device_unlink_event_follow_up'), [DeviceDetailController::class, 'unlinkEventFollowUp'])->name('device_unlink_event_follow_up');
    Route::post(LaravelLocalization::transRoute('routes.device_link_event_follow_up'), [DeviceDetailController::class, 'linkEventFollowUp'])->name('device_link_event_follow_up');
    Route::get(LaravelLocalization::transRoute('routes.device_link_follow_up_via_device'), [DeviceDetailController::class, 'addDeviceToEventFollowUpViaDetail'])->name('device_link_follow_up_via_device');
    Route::get(LaravelLocalization::transRoute('routes.device_unlink_follow_up'), [DeviceDetailController::class, 'removeDeviceToEventFollowUpViaDetail'])->name('device_unlink_follow_up');
    Route::post('device/event/assign/{slug}', [DeviceDetailController::class, 'assignEvent'])->name('device_assign_event');
    Route::post('device/repairer/assign/{slug}', [DeviceDetailController::class, 'assignRepairer'])->name('device_assign_repairer');

    //Organisation dashboard overviews
    Route::get(LaravelLocalization::transRoute('routes.location_general_overview'), [OrganisationDashboardController::class, 'index'])->name('location_general_overview');
    Route::get(LaravelLocalization::transRoute('routes.location_devices_overview'), [OrganisationDashboardController::class, 'devices'])->name('location_devices_overview');
    Route::get(LaravelLocalization::transRoute('routes.location_repairer_fixed_overview'), [OrganisationDashboardController::class, 'repairerDeviceHistory'])->name('location_repairer_fixed_overview');
    Route::get(LaravelLocalization::transRoute('routes.location_repairers_overview'), [OrganisationDashboardController::class, 'repairers'])->name('location_repairers_overview');
    Route::get(LaravelLocalization::transRoute('routes.location_events_overview'), [OrganisationDashboardController::class, 'events'])->name('location_events_overview');
    Route::get(LaravelLocalization::transRoute('routes.location_past_events_overview'), [OrganisationDashboardController::class, 'pastEvents'])->name('location_past_events_overview');
    Route::get(LaravelLocalization::transRoute('routes.event_show'), [EventController::class, 'show'])->name('event_show');
    Route::get(LaravelLocalization::transRoute('routes.location_impact_overview'), [OrganisationDashboardController::class, 'impact'])->name('location_impact_overview');

    //Repair Log repair history page
    Route::get(LaravelLocalization::transRoute('routes.history_repair_log_overview'), [RepairLogController::class, 'index'])->name('history_repair_log_overview');
});

Route::group([
    'prefix' => 'api',
    'middleware' => ['auth', 'changeLocaleByQuery'],
], function () {
    Route::get('devices/fixed', [ApiDeviceController::class, 'getFixedDevices'])->name('api_devices_fixed');
    Route::get('devices/active/{organisation:uuid}', [ApiDeviceController::class, 'getActiveDevices'])->name('api_devices_active');
    Route::get('devices/organisation/overview/{organisation:uuid}', [ApiDeviceController::class, 'getLocationDeviceOverview'])->name('api_devices_location_overview');
    Route::get('devices/personal', [ApiDeviceController::class, 'getPersonalDevices'])->name('api_devices_personal');
    Route::get('devices/repaired', [ApiDeviceController::class, 'getRepairedDevices'])->name('api_devices_repaired');
    Route::get('devices/repairer/{organisation:uuid}', [ApiDeviceController::class, 'getRepairerDevices'])->name('api_devices_repairer');
    Route::get('devices/repairer/fixed', [ApiDeviceController::class, 'getRepairerFixed'])->name('api_devices_repairer_fixed');
    Route::get('devices/torepair/{organisation:uuid}', [ApiDeviceController::class, 'getToRepairDevices'])->name('api_devices_torepair');
    Route::get('organisation/repairers/{organisation:uuid}', [ApiOrganisationController::class, 'getRepairersOrganisation']);
    Route::get('organisation/events/past/{uuid}', [ApiOrganisationController::class, 'getPastEventsOrganisation']);
    Route::get('devices/fixed/repairer/{organisation:uuid}', [ApiDeviceController::class, 'getFixedDevicesRepairerLocation']);
    Route::post('repairer/event/attend/{id}', [ApiRepairerController::class, 'eventStatusSwitch'])->name('repairer_event_status_switch');
    Route::get('event/devices/search/{event:slug}', [ApiDeviceController::class, 'getEventDevices'])->name('event_devices_search');
    //Route::get('repairer/event/attending/{id}', [ApiRepairerController::class, 'isAttendingEvent'])->name('repairer_event_attending_status');
});

Route::group([
    'prefix' => 'api',
    'middleware' => 'changeLocaleByQuery',
], function () {
    Route::get('organisations/active', [ApiOrganisationController::class, 'getAvailableOrganisations'])->name('api_locations_available');
    Route::post('organisations/search', [ApiOrganisationController::class, 'search'])->name('location_search');
    Route::get('organisation/events/future/{uuid}', [ApiOrganisationController::class, 'getFutureEventsOrganisation']);
});
