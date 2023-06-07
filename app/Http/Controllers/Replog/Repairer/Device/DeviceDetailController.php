<?php

namespace App\Http\Controllers\Replog\Repairer\Device;

use App\Facades\DeviceRepository;
use App\Facades\EmployeeOrganisationRepository;
use App\Facades\EmployeeRepository;
use App\Facades\EventRepository;
use App\Facades\PersonRepository;
use App\Facades\RepairLogRepository;
use App\Http\Controllers\Replog\ReplogController;
use App\Http\Services\MailService;
use App\Models\Device;
use App\Models\DeviceType;
use App\Models\Employee;
use App\Models\Event;
use App\Models\ImageCategory;
use App\Models\RepairLog;
use Exception;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class DeviceDetailController extends ReplogController
{
    public function show($slug)
    {
        $user = auth()->user();
        /** @var \App\Models\Employee $employee */
        $person = PersonRepository::getByUser($user)->firstOrFail();

        /** @var \App\Models\Device $device * */
        $device = DeviceRepository::getBySlug($slug)->with([
            'repairLogs.completedRepairStatus',
            'completedRepairStatus',
            'organisation',
            'event',
        ])->firstOrFail();

        $organisation = $device->organisation;

        /** @var RepairLog $lastLog */
        $lastLog = $device->repairLog;
        $repairerStarted = false;
        $repairStatus = null;
        $canEdit = false;
        $editContact = false;
        $completedEdit = false;
        $canEditEvent = EmployeeOrganisationRepository::isEventOrganizerOrganisation($user, $organisation);
        $isRepairer = EmployeeOrganisationRepository::isRepairerOrganisation($user, $organisation);
        $isEntityAdmin = $this->isEntityAdmin($device->organisation);
        $status = $lastLog->status ?? null;

        if ($isEntityAdmin || $canEditEvent) {
            $canEdit = true;
        }

        if ($lastLog) {
            $repairStatus = __("messages.status_{$status}");
            $repairerStarted = $person && $lastLog->person && $person->id == $lastLog->person->id && $status === RepairLog::STATUS_IN_REPAIR;
        }

        $needsRepair = true;
        if ($lastLog && $status && in_array($status, [
                RepairLog::STATUS_COMPLETED,
            ], true)) {
            $needsRepair = false;
            if ($person && $lastLog->person && $person->id == $lastLog->person->id) {
                $canEdit = true;
            }
        } elseif ($lastLog && $status === RepairLog::STATUS_IN_REPAIR && ($person && optional($lastLog->person)->id == $person->id)) {
            $canEdit = true;
            $canEditEvent = true;
        } elseif ($person && $isRepairer && ($device->latest_status == RepairLog::STATUS_OPEN || $device->latest_status == RepairLog::STATUS_REOPENED)) {
            $canEdit = true;
        }


        if ($isEntityAdmin || $canEditEvent || ($lastLog && optional($lastLog->person)->id === $person->id)) {
            $editContact = true;
            if (request()->query('edit') === 'true') {

                $completedEdit = true;
            }
        }


        $deviceTypesRaw = DeviceType::visible()->showOnRepair()->get();
        $deviceTypes = [];
        foreach ($deviceTypesRaw as $index => $deviceType) {
            $deviceTypes[$index]['name'] = $deviceType->name;
            $deviceTypes[$index]['value'] = $deviceType->id;
        }

        $hasEvent = false;
        $repairerArray = [];
        if ($device->event || $isEntityAdmin) {
            $employees = EmployeeOrganisationRepository::getRepairersByOrganisation($organisation)->get();
            foreach ($employees as $index => $item) {
                $repairerArray[$index]['name'] = $item->person->full_name;
                $repairerArray[$index]['value'] = $item->id;
            }
            $hasEvent = true;
        }

        $events = [];
        $eventEntries = EventRepository::getAllForOrganisation($organisation->uuid)->orderBy('date_start', 'desc')
                                       ->get();
        if ($eventEntries) {
            foreach ($eventEntries as $eventEntry) {
                $events[] = [
                    'name' => $eventEntry->getDropdownTitle(),
                    'value' => $eventEntry->id,
                    'isFull' => $eventEntry->hasMaxAmountRegistration(),
                ];
            }
        }

        return Inertia::render('Device/Detail/DeviceDetail', [
            'device' => $device,
            'deviceTypes' => $deviceTypes,
            'repairerStarted' => $repairerStarted,
            'needsRepair' => $needsRepair,
            'repairStatus' => $repairStatus,
            'canEdit' => $canEdit,
            'canEditEvent' => $canEditEvent,
            'repairStatusesCompleted' => $this->getRepairStatusesCompleted(),
            'repairStatusesClose' => $this->getRepairStatusClose(),
            'repairBarriers' => $this->getRepairBarriers(),
            'repairArchiveReasons' => $this->getRepairArchiveReasons(),
            'title' => $device->getName(),
            'eventAdmin' => $this->isEventOrganizer($device->organisation),
            'repairers' => $repairerArray,
            'hasEvent' => $hasEvent,
            'futureEvents' => $events ?? null,
            'entityAdmin' => $isEntityAdmin,
            'editContact' => $editContact,
            'completedEdit' => $completedEdit,
            'isLogRepairer' => ($lastLog && optional($lastLog->person)->id === $person->id),
            'image_general' => $device->getMedia(ImageCategory::IMAGE_GENERAL),
            'images_defect' => $device->getMedia(ImageCategory::IMAGE_DEFECT),
            'images_barcode' => $device->getMedia(ImageCategory::IMAGE_BARCODE),
            'images_repair' => $lastLog?->getMedia(ImageCategory::IMAGE_REPAIR),
        ]);
    }

    /**
     * Give the confirm repair screen for the repairer. (after form submit, the repair log will get status in_repair
     *
     * @param $slug
     * @return \Illuminate\Http\RedirectResponse|\Inertia\Response
     */
    public function deviceSelectRepair($slug)
    {
        /** @var \App\Models\Device $device * */
        $device = DeviceRepository::getBySlug($slug)->firstOrFail();

        if ($this->isAdmin($device->organisation)) {
            return redirect()->route('repairer_dashboard')->with('error', trans('messages.warning_logged_in_admin'));
        }

        if (!$this->isDeviceAvailable($device)) {
            return redirect()->route('device_show', ['slug' => $slug])
                             ->with('warning', trans('messages.warning_device_not_available'));
        }

        if ($device->event) {
            return Inertia::render('Device/Detail/DeviceSelectRepairEvent', [
                'device' => $device,
                'event' => $device->event,
                'title' => trans('messages.device_select_repair_title', [
                    'model' => $device->model_name,
                    'brand' => $device->brand_name,
                ]),
            ]);
        }

        return Inertia::render('Device/Detail/DeviceSelectRepair', [
            'device' => $device,
            'title' => trans('messages.device_select_repair_title', [
                'model' => $device->model_name,
                'brand' => $device->brand_name,
            ]),
        ]);
    }

    /**
     * This will add the repairer to the device and give the status in_repair
     *
     * @param \App\Http\Services\MailService $mailService
     * @param $slug
     * @return \Illuminate\Http\RedirectResponse
     */
    public function deviceConfirmSelectRepair(MailService $mailService, $slug)
    {
        /** @var \App\Models\Device $device * */
        $device = DeviceRepository::getBySlug($slug)->firstOrFail();
        $person = PersonRepository::getByUser(auth()->user())->with('user')->firstOrFail();

        if (!$this->isDeviceAvailable($device)) {
            return redirect()->route('device_show', ['slug' => $slug])
                             ->with('warning', trans('messages.warning_device_not_available'));
        }
        try {
            if (RepairLogRepository::hasMaxAmountDevices($person)) {
                return redirect()->route('device_show', ['slug' => $slug])
                                 ->with('warning', trans('messages.devices_selected_max', ['amount' => RepairLog::MAX_ASSIGNED_LOGS]));
            }

            DB::beginTransaction();
            $oldLog = $device->repairLog;
            RepairLogRepository::createLog($device, $person, $oldLog);
            //Send mail to repairer and send mail to person of device only when device is not linked to an event
            $mailService->sendMailSelectedForRepair($device, $person, $sendToDeviceOwner = !$device->event);

            DB::commit();
        } catch (ClientException $e) {
            DB::rollBack();
            report($e);

            return redirect()->route('device_show', ['slug' => $slug])
                             ->with('warning', trans('messages.error_sending_mail_log_creation'));
        } catch (Exception $e) {
            report($e);

            return redirect()->route('device_show', ['slug' => $slug])
                             ->with('warning', trans('messages.log_could_not_be_created'));
        }

        return redirect()->route('device_show', ['slug' => $slug])
                         ->with('success', trans('messages.succes_device_started'));
    }

    public function assignEvent(MailService $mailService, $slug)
    {
        try {
            DB::beginTransaction();
            $eventId = request()->input('event_select');
            /** @var Device $device */
            $device = DeviceRepository::getBySlug($slug)->firstOrFail();
            $event = EventRepository::find($eventId)->firstOrFail();
            $device->event()->associate($eventId);
            $device->save();
            DB::commit();

            //$mailService->sendMailLinkedEvent($device, $event);

            return redirect()->route('device_show', ['slug' => $device->slug])
                             ->with('success', trans('messages.device_event_linked', [
                                 'event' => $event->name,
                                 'device' => $device->brand_name . ' ' . $device->model_name,
                             ]));
        } catch (Exception $e) {
            DB::rollBack();
            report($e);

            return redirect()->route('device_show', ['slug' => $device->slug])->with('error', trans('error_form'));
        }
    }

    public function unlinkEvent(MailService $mailService, Device $device)
    {
        $event = $device->event;
        if (!$event) {
            return redirect()->route('device_show', ['slug' => $device->slug]);
        }

        try {
            DB::beginTransaction();
            $device->event()->dissociate();
            $device->event_follow_up_id = null;
            $device->save();
            DB::commit();

            $mailService->sendMailUnlinkedEvent($device, $event);
        } catch (Exception $e) {
            DB::rollBack();
            report($e);

            return redirect()->route('device_show', ['slug' => $device->slug])->with('error', trans('error_form'));
        }

        return redirect()->route('device_show', ['slug' => $device->slug])
                         ->with('success', trans('messages.device_event_unlinked', ['event' => $event->locale_name]));
    }

    public function linkEventFollowUp(Request $request, Event $event)
    {
        try {
            DB::beginTransaction();

            $deviceId = $request->input('model');
            $device = Device::query()->where('id', $deviceId)->event($event->id)->first();

            if ($device->event_follow_up_id) {
                return redirect()->route('event_show', ['slug' => $event->slug])
                                 ->with('warning', trans('messages.device_event_already_linked', ['event' => $event->locale_name]));
            }

            $followUpId = Device::query()->whereHas('event', function (Builder $q) use ($event) {
                $q->where('id', $event->id);
            })->whereNotNull('event_follow_up_id')->orderByDesc('event_follow_up_id')->pluck('event_follow_up_id')
                                ->first();

            if (!$followUpId) {
                $device->event_follow_up_id = 1;
            } else {
                $followUpId++;
                $device->event_follow_up_id = $followUpId;
            }

            $device->save();
            DB::commit();

            return redirect()->route('event_show', ['slug' => $event->slug])
                             ->with('success', trans('messages.device_event_linked', ['event' => $event->locale_name, 'device' => $device->getName()]));
        } catch (\Throwable $e) {
            report($e);
            DB::rollBack();

            return redirect()->route('event_show', ['slug' => $event->slug])->with('error', trans('error_form'));
        }
    }

    public function unlinkEventFollowUp(Device $device, $detail = null)
    {
        try {
            $event = $device->event;
            if (!$event) {
                return redirect()->route('device_show', ['slug' => $device->slug]);
            }

            DB::beginTransaction();
            $device->event_follow_up_id = null;
            $device->save();
            DB::commit();


            if ($detail) {
                return redirect()->route('device_show', ['slug' => $device->slug])
                                 ->with('success', trans('messages.device_event_unlinked', ['event' => $event->locale_name]));
            }

            return redirect()->route('event_show', ['slug' => $event->slug])
                             ->with('success', trans('messages.device_event_unlinked', ['event' => $event->locale_name]));
        } catch (\Throwable $e) {
            DB::rollBack();

            if ($detail) {
                return redirect()->route('device_show', ['slug' => $device->slug])->with('error', trans('error_form'));
            }
            return redirect()->route('event_show', ['slug' => $event->slug])->with('error', trans('error_form'));
        }
    }

    public function addDeviceToEventFollowUpViaDetail(Device $device)
    {
        DB::beginTransaction();

        $event = $device->event;

        $followUpId = Device::query()->whereHas('event', function (Builder $q) use ($event) {
            $q->where('id', $event->id);
        })->whereNotNull('event_follow_up_id')->orderByDesc('event_follow_up_id')->pluck('event_follow_up_id')->first();

        if (!$followUpId) {
            $device->event_follow_up_id = 1;
        } else {
            $followUpId++;
            $device->event_follow_up_id = $followUpId;
        }

        $device->save();
        DB::commit();

        return redirect()->route('device_show', ['slug' => $device->slug])->with('success', trans('messages.device_event_linked', ['event' => $event->locale_name, 'device' => $device->getName()]));;
    }


    public function assignRepairer(MailService $mailService, $slug)
    {
        try {
            DB::beginTransaction();
            $employeeId = request()->input('repairer_select');
            /** @var Device $device */
            $device = DeviceRepository::getBySlug($slug)->firstOrFail();
            /** @var Employee $employee */
            $employee = EmployeeRepository::getById($employeeId)->firstOrFail();
            $person = $employee->person;

            $oldLog = $device->repairLog;

            RepairLogRepository::createLog($device, $person, $oldLog);
            DB::commit();

            $device->refresh();

            $mailService->sendMailSelectedForRepair($device, $person, $sendToDeviceOwner = !$device->event);

            return redirect()->route('device_show', ['slug' => $device->slug])
                             ->with('success', trans('messages.device_repairer_linked'));

        } catch (Exception $e) {
            DB::rollBack();
            report($e);

            return redirect()->route('device_show', ['slug' => $device->slug])->with('error', trans('error_form'));
        }
    }
}
