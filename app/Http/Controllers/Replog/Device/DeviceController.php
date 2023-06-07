<?php

namespace App\Http\Controllers\Replog\Device;

use App\Facades\DeviceRepository;
use App\Facades\EmployeeOrganisationRepository;
use App\Facades\EventRepository;
use App\Facades\OrganisationRepository;
use App\Facades\PersonRepository;
use App\Http\Controllers\Replog\ReplogController;
use App\Http\Requests\DeviceNoteRequest;
use App\Http\Requests\DeviceStep1Request;
use App\Http\Requests\DeviceStep2Request;
use App\Http\Services\MailService;
use App\Models\Device;
use App\Models\Event;
use App\Models\ImageCategory;
use App\Models\Location;
use App\Models\Page;
use App\Models\Tenant;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Inertia\Inertia;
use Mcamara\LaravelLocalization\LaravelLocalization;

class DeviceController extends ReplogController
{
    const SESSION_KEY = 'device';

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $repairableDevices = Device::repairable()->get();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param Device $device
     * @param $locationCode
     * @param string $step
     * @return \Illuminate\Http\RedirectResponse|\Inertia\Response
     */
    public function create(Device $device, $locationCode = null, $step = '0')
    {
        if ($step === '0' && !$locationCode) {
            Session::forget($this::SESSION_KEY);

            return Inertia::render('Device/Create/Step0', [
                'title' => trans('messages.home_register_device'),
                'locale' => App::getLocale(),
                'mapbox' => config('mapbox'),
            ]);
        }
        if ($step === '1' && $locationCode) {
            $organisation = OrganisationRepository::findBySlug($locationCode, app()->getLocale())->first();
            if (!$organisation) {
                return redirect()->route('device_create');
            }

            $slug = Session::get($this::SESSION_KEY);
            if ($slug) {
                $device = DeviceRepository::getBySlug($slug)->withoutGlobalScopes()->first();
            }

            // WithoutGlocalScopes will remove the TempScope (only show fully registered devices)
            return Inertia::render('Device/Create/Step1', [
                'device_types' => $this->getDeviceTypeOptions(),
                'device_types_mobile' => $this->getDeviceTypesMobile(),
                'data' => $device,
                'image_general' => $device->getMedia(ImageCategory::IMAGE_GENERAL),
                'images_defect' => $device->getMedia(ImageCategory::IMAGE_DEFECT),
                'images_barcode' => $device->getMedia(ImageCategory::IMAGE_BARCODE),
                'eventKey' => request()->query('event'),
                'location' => $locationCode,

                'title' => trans('messages.home_register_device'),
            ]);
        }

        if ($step === '2' && $locationCode) {
            $slug = Session::get($this::SESSION_KEY);
            // WithoutGlocalScopes will remove the TempScope (only show fully registered devices)
            $device = DeviceRepository::getBySlug($slug)->withoutGlobalScopes()->first();
            if (!$this->canBeInStep($device, 1)) {
                return redirect()->route('device_create', [
                    'step' => 1,
                    'locationCode' => $locationCode,
                ]);
            }

            $organisation = OrganisationRepository::findBySlug($locationCode, app()->getLocale())->first();
            if (!$organisation) {
                return redirect()->route('device_create');
            }

            $events = [];
            $user = \auth()->user();
            $isAdmin = false;
            if ($user) {
                $person = PersonRepository::getByUser(\auth()->user())->first();
                $isAdmin = (EmployeeOrganisationRepository::isEntityAdmin($person) || EmployeeOrganisationRepository::isEventOrganizer($person));
            }

            if ($isAdmin) {
                $eventEntries = EventRepository::getFuturePlusPastDays($organisation->uuid, 30)->get();
            } else {
                $eventEntries = EventRepository::getFuture($organisation->uuid)->get();
            }


            if ($eventEntries) {
                foreach ($eventEntries as $eventEntry) {
                    $events[] = [
                        'text' => $eventEntry->getDropdownTitle(),
                        'value' => $eventEntry->id,
                        'isFull' => $eventEntry->hasMaxAmountRegistration(),
                    ];
                }
            }

            return Inertia::render('Device/Create/Step2', [
                'organisation' => $organisation,
                'events' => $events,
                'event' => optional($device->event)->id ?? null,
                'location' => $locationCode,
                'title' => trans('messages.home_register_device'),
            ]);
        }
    }

    public function storeStep0(Request $request)
    {
        $locationCode = $request->get('location');
        if (!$locationCode) {
            back()->with('validation.location');
        }

        return redirect()->route('device_create', [
            'step' => 1,
            'locationCode' => $locationCode,
        ]);
    }

    public function storeStep1(DeviceStep1Request $request, $locationCode)
    {
        try {
            $data = $request->validated();

            $slug = Session::get($this::SESSION_KEY);
            if ($slug) {
                $device = DeviceRepository::getBySlug($slug)->withoutGlobalScopes()->first();
            } else {
                $device = new Device();
            }

            $device->fill($data);

            if ($data['eventKey']) {
                $event = Event::query()->where('slug', $data['eventKey'])->first();
                if ($event) {
                    $device->event()->associate($event);
                }
            }

            //Save object
            $device->setTemp(true);
            $device->save();
            $device->refresh();

            if ($request->image_general) {
                $device->syncFromMediaLibraryRequest($request->image_general)->toMediaCollection(ImageCategory::IMAGE_GENERAL);
            }
            if ($request->images_defect) {
                $device->syncFromMediaLibraryRequest($request->images_defect)->toMediaCollection(ImageCategory::IMAGE_DEFECT);
            }
            if ($request->images_barcode) {
                $device->syncFromMediaLibraryRequest($request->images_barcode)->toMediaCollection(ImageCategory::IMAGE_BARCODE);
            }

            Session::put(self::SESSION_KEY, $device->slug);

            return redirect()->route('device_create', [
                'step' => 2,
                'locationCode' => $locationCode,
            ]);
        } catch (Exception $e) {
            DB::rollBack();
            report($e);

            return back()->withInput()->with(['errors' => trans('messages.something_went_wrong')]);
        }

    }

    public function storeStep2(DeviceStep2Request $request, $locationCode, MailService $mailService)
    {
        $slug = Session::get($this::SESSION_KEY);
        /** @var Device $device */
        $device = DeviceRepository::getBySlug($slug)->withoutGlobalScopes()->first();
        if (!$this->canBeInStep($device, 1)) {
            return redirect()->route('device_create', [
                'step' => 0,
                'locationCode' => $locationCode,
            ]);
        }

        try {
            DB::beginTransaction();
            $device->fill($request->post());

            $organisation = OrganisationRepository::findByCode($locationCode)->firstOrFail();
            $device->organisation()->associate($organisation);

            if ($request->input('register_type') === 'event' && $request->input('event')) {
                $event = EventRepository::find($request->input('event'))->firstOrFail();
                $device->event()->associate($event);
            }

            $device->setTemp(false);
            $device->locale = app()->getLocale();
            $device->save();

            DB::commit();

            if (!$device->event) {
                $mailService->sendDeviceRegisteredMail($device);
            } else {
                $mailService->sendDeviceEventRegisteredMail($device);
                //REPLOG-695 Turn off mail on event register
                //$mailService->sendMailRegisteredEvent($device);
            }


            Session::forget($this::SESSION_KEY);

            return redirect()->route('device_confirmation', ['slug' => $device->slug]);
        } catch (Exception $e) {
            DB::rollBack();
            report($e);

            return back()->withInput()->withErrors(['errors' => trans('messages.something_went_wrong')]);
        }
    }

    /**
     * Confirmation page after device has been created
     *
     * @param \App\Models\Page $page
     * @return \Inertia\Response
     */
    public function confirmation(Page $page, $slug)
    {
        $page = $page->getDeviceCreatedContent(Tenant::REPLOG);
        $device = Device::query()->where('slug', $slug)->first();

        return Inertia::render('Device/Create/Confirmation', [
            'title' => $page->title,
            'device' => $device,
            'body' => $page->body,
            'flexible' => $page->flexible,
        ]);
    }

    /**
     * Show the repaired device with all of it's logs
     *
     * @param $slug
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|\Inertia\Response
     */
    public function show($slug)
    {
        $device = DeviceRepository::getBySlug($slug)
                                  ->with('repairLog', 'repairLogs.completedRepairStatus', 'completedRepairStatus')
                                  ->firstOrFail();

        /** @var Device $device */
        if (!$device->repairLog->isFixed()) {
            return redirect('/')->with(['warning' => trans('messages.warning_device_not_repaired')]);
        }

        return Inertia::render('Device/Detail/DeviceDetail', [
            'device' => $device,
            'title' => $device->getName(),
        ]);
    }

    /**
     * Check if registering a device can be in the specified step
     *
     * @param $device
     * @param $step
     * @return bool
     */
    private function canBeInStep($device, $step): bool
    {
        if ($step === 0) {
            return true;
        }

        if ($step === 1) {
            /** @var Device $device */
            return isset($device, $device->deviceType, $device->brand_name, $device->issue_description);
            //return isset($device['device_type_id'], $device['brand_name'], $device['issue_description']);
        }

        return false;
    }

    public function addNote(DeviceNoteRequest $request, $slug): RedirectResponse
    {
        try {
            $data = $request->validated();
            /** @var Device $device */
            $device = DeviceRepository::getBySlug($slug)->firstOrFail();
            $person = PersonRepository::getByUser(Auth::user())->first();

            $currentDeviceNotes = $device->notes()->pluck('id')->all();
            if (isset($currentDeviceNotes)) {
                $currentDeviceNotes = collect($currentDeviceNotes);
            }
            DB::beginTransaction();
            $savedNoteIds = [];
            if (isset($data['device_notes'])) {
                foreach ($data['device_notes'] as $note) {
                    if (!isset($note['id'])) {
                        $device->addNote($note['content'], $person);
                    } else {
                        $savedNoteIds[] = $note['id'];
                        $device->editNote($note['id'], $note['content'], $person);
                    }
                }
            }


            $savedNoteIds = collect($savedNoteIds);
            $diff = $currentDeviceNotes->diff($savedNoteIds);
            if ($diff) {
                $oldNotes = $device->notes()->whereIn('id', $diff)->get();
                if ($oldNotes) {
                    foreach ($oldNotes as $oldNote) {
                        $oldNote->delete();
                    }
                }
            }

            DB::commit();

            return redirect()->route('device_show', ['slug' => $slug])
                             ->with('success', trans('messages.success_device_notes_edited'));
        } catch (\Throwable $e) {
            report($e);
            DB::rollBack();

            return back()->with('warning', trans('messages.error_submit_log'));
        }
    }

    /**
     * Return the guidance url to de repairConnects page
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \Mcamara\LaravelLocalization\Exceptions\SupportedLocalesNotDefined
     * @throws \Mcamara\LaravelLocalization\Exceptions\UnsupportedLocaleException
     */
    public function redirectCreate(Request $request)
    {
        $uuid = $request->get('uuid');
        $location = Location::query()->where('uuid', $uuid)->firstOrFail();
        $organisation = $location->organisation;
        $laravelLocalized = new LaravelLocalization();
        $laravelLocalized->setLocale(app()->getLocale());
        $laravelLocalized->setBaseUrl('https://repairconnects.org/');
        $url = $laravelLocalized->getURLFromRouteNameTranslated(app()->getLocale(), 'routes.device_create', [
            'locationCode' => $organisation->slug,
            'step' => 2,
        ]);

        return redirect($url);
    }
}
