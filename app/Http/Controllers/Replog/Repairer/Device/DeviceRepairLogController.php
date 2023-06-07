<?php

namespace App\Http\Controllers\Replog\Repairer\Device;

use App\Facades\DeviceRepository;
use App\Facades\EmployeeOrganisationRepository;
use App\Facades\PersonRepository;
use App\Facades\RepairLogRepository;
use App\Http\Controllers\Replog\ReplogController;
use App\Http\Requests\DeviceCloseRequest;
use App\Http\Requests\DeviceContactRequest;
use App\Http\Requests\DeviceRepairRequest;
use App\Http\Requests\LogRepairedRequest;
use App\Http\Requests\RepairLogRequest;
use App\Http\Services\MailService;
use App\Models\CompletedRepairStatus;
use App\Models\Device;
use App\Models\ImageCategory;
use App\Models\RepairLog;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DeviceRepairLogController extends ReplogController
{
    public function updateLog(RepairLogRequest $request, $uuid): RedirectResponse
    {
        try {
            $repairLog = RepairLogRepository::findByUuid($uuid);
            $user = auth()->user();
            $device = $repairLog->device;
            $isEventOrganiser = EmployeeOrganisationRepository::isEventOrganizerOrganisation($user, $device->organisation);
            $isEntityAdmin = EmployeeOrganisationRepository::isEntityAdminOrganisation($user, $device->organisation);

            $canEdit = false;
            if ($isEventOrganiser || $isEntityAdmin) {
                $canEdit = true;
            }
            $repairLog = RepairLogRepository::findByUuid($uuid);
            $person = PersonRepository::getByUser(auth()->user())->firstOrFail();
            if (($person && $person->id !== $repairLog->person->id) && !$canEdit) {
                return back()->with('warning', trans('messages.not_repairer_of_log'));
            }

            DB::beginTransaction();
            RepairLogRepository::updateLog($repairLog, $request->post(), $canEdit);

            DB::commit();
            $repairLog->refresh();

            $repairLog->syncFromMediaLibraryRequest($request->images_repair)->toMediaCollection(ImageCategory::IMAGE_REPAIR);

            if ($repairLog->status === RepairLog::STATUS_COMPLETED) {
                return redirect()->route('device_show', ['slug' => $device->slug])->with('success', trans('messages.success.repair_complete'));
            }

            return redirect()->route('repairer_dashboard')->with('success', trans('messages.success.repair_complete'));
        } catch (Exception $e) {
            report($e);
            DB::rollBack();

            return back()->with('warning', trans('messages.error_submit_log'));
        }
    }

    public function closeLog(LogRepairedRequest $request, $uuid): RedirectResponse
    {
        try {
            $repairLog = RepairLogRepository::findByUuid($uuid);
            $user = auth()->user();
            $device = $repairLog->device;

            $person = PersonRepository::getByUser($user)->firstOrFail();
            $isEventOrganiser = EmployeeOrganisationRepository::isEventOrganizerOrganisation($user, $device->organisation);
            $isEntityAdmin = EmployeeOrganisationRepository::isEntityAdminOrganisation($user, $device->organisation);

            if (($person && $person->id !== $repairLog->person->id) && (!$isEventOrganiser || !$isEntityAdmin)) {
                return back()->with('warning', trans('messages.not_repairer_of_log'));
            }

            DB::beginTransaction();
            RepairLogRepository::closeLog($repairLog, $request->post());

            DB::commit();

            $repairLog->syncFromMediaLibraryRequest($request->images_repair)->toMediaCollection(ImageCategory::IMAGE_REPAIR);

            $repairLog = $repairLog->refresh();
            $device = $repairLog->device;
            $person = $repairLog->person;
            $device->refresh();

            if (in_array($repairLog->status, [
                RepairLog::STATUS_COMPLETED,
            ], true)) {
                $mailService = new MailService();
                $mailService->sendDeviceFixed($device, $person);
            }

            $slug = $device->slug;

            return redirect()->route('device_show', ['slug' => $slug])
                             ->with('success', trans('messages.success.repair_complete'));
        } catch (Exception $e) {
            report($e);
            DB::rollBack();

            return back()->with('warning', trans('messages.error_submit_log'));
        }
    }

    public function updateDevice(DeviceRepairRequest $request, $uuid): RedirectResponse
    {
        try {
            $repairLog = RepairLogRepository::findByUuid($uuid);
            $person = PersonRepository::getByUser(auth()->user())->firstOrFail();
            if ($person->id !== $repairLog->person->id) {
                return back()->with('warning', trans('messages.not_repairer_of_log'));
            }

            DB::beginTransaction();
            RepairLogRepository::updateDevice($repairLog, $request->post());
            DB::commit();

            if ($request->image_general) {
                $repairLog->device->syncFromMediaLibraryRequest($request->image_general)->toMediaCollection(ImageCategory::IMAGE_GENERAL);
            }
            if ($request->images_defect) {
                $repairLog->device->syncFromMediaLibraryRequest($request->images_defect)->toMediaCollection(ImageCategory::IMAGE_DEFECT);
            }
            if ($request->images_barcode) {
                $repairLog->device->syncFromMediaLibraryRequest($request->images_barcode)->toMediaCollection(ImageCategory::IMAGE_BARCODE);
            }

            $repairLog->device->refresh();
            $slug = $repairLog->device->slug;

            return redirect()->route('device_show', ['slug' => $slug])
                             ->with('success', trans('messages.success.repair_complete'));
        } catch (Exception $e) {
            report($e);
            DB::rollBack();

            return back()->with('warning', trans('messages.error_submit_log'));
        }
    }

    /**
     * Change the status of this device back to reopened
     *
     * @param \Illuminate\Http\Request $request
     * @param $uuid
     * @return \Illuminate\Http\RedirectResponse
     */
    public function reopenDevice(Request $request, $uuid): RedirectResponse
    {
        try {
            $repairLog = RepairLogRepository::findByUuid($uuid);
            $device = $repairLog->device;

            $user = auth()->user();

            $person = PersonRepository::getByUser($user)->firstOrFail();
            $isEventOrganiser = EmployeeOrganisationRepository::isEventOrganizerOrganisation($user, $device->organisation);
            $isEntityAdmin = EmployeeOrganisationRepository::isEntityAdminOrganisation($user, $device->organisation);

            if (($person && $person->id !== $repairLog->person->id) && (!$isEventOrganiser && !$isEntityAdmin)) {
                return back()->with('warning', trans('messages.not_repairer_of_log'));
            }

            DB::beginTransaction();

            if (!in_array(optional($device->completedRepairStatus)->code, [
                CompletedRepairStatus::STATUS_ARCHIVE,
                CompletedRepairStatus::STATUS_END_OF_LIFE,
            ], true)) {
                RepairLogRepository::reopenDevice($repairLog, $request->post());
                $repairLog->syncFromMediaLibraryRequest($request->images_repair)->toMediaCollection(ImageCategory::IMAGE_REPAIR);
            } else {
                $log = RepairLogRepository::createLog($device, $person, $repairLog, $request->post());
                $log->status = RepairLog::STATUS_REOPENED;
                $log->save();
                $log->refresh();

                $log->syncFromMediaLibraryRequest($request->images_repair)->toMediaCollection(ImageCategory::IMAGE_REPAIR);
            }

            DB::commit();

            $repairLog->refresh();

            if ($repairLog->status && ($repairLog->status === RepairLog::STATUS_OPEN) && (!$isEventOrganiser && !$isEntityAdmin)) {
                $mailService = new MailService();
                $mailService->sendDeviceReopenedMail($device, $person);
            }

            if ($isEventOrganiser || $isEntityAdmin) {
                return redirect()->route('device_show', ['slug' => $device->slug])
                                 ->with('success', trans('messages.success.repair_complete'));
            }

            return redirect()->route('repairer_dashboard')->with('success', trans('messages.success.repair_complete'));
        } catch (Exception $e) {
            report($e);
            DB::rollBack();

            return back()->with('warning', trans('messages.error_submit_log'));
        }
    }

    public function updateContact(DeviceContactRequest $request, $slug)
    {
        try {
            $device = DeviceRepository::getBySlug($slug)->firstOrFail();
            $data = $request->validated();

            $user = auth()->user();
            $person = PersonRepository::getByUser($user)->firstOrFail();
            $lastLog = $device->repairLog;

            $isEventOrganiser = EmployeeOrganisationRepository::isEventOrganizerOrganisation($user, $device->organisation);
            $isEntityAdmin = EmployeeOrganisationRepository::isEntityAdminOrganisation($user, $device->organisation);

            if (!$isEntityAdmin && !$isEventOrganiser && ($person->id !== $lastLog->person->id)) {
                return back()->with('warning', trans('messages.warning_no_rights_to_edit'));
            }

            DB::beginTransaction();
            DeviceRepository::updateContact($device, $data);
            DB::commit();

            return redirect()->route('device_show', ['slug' => $slug])
                             ->with('success', trans('messages.success_contact_updated', ['device' => $device->getName()]));
        } catch (Exception $e) {
            report($e);
            DB::rollBack();

            return back()->with('warning', trans('messages.error_submit_log'));
        }
    }

    public function updateRepairLogNotes(Request $request, $uuid)
    {
        try {
            $repairLog = RepairLogRepository::findByUuid($uuid);
            $device = $repairLog->device;

            $data = $request->validate(['notes.*' => 'nullable']);

            $user = auth()->user();

            $person = PersonRepository::getByUser($user)->firstOrFail();
            $isEventOrganiser = EmployeeOrganisationRepository::isEventOrganizerOrganisation($user, $device->organisation);
            $isEntityAdmin = EmployeeOrganisationRepository::isEntityAdminOrganisation($user, $device->organisation);

            if (!$isEntityAdmin || !$isEventOrganiser) {
                return back()->with('warning', trans('messages.warning_no_rights_to_edit'));
            }

            DB::beginTransaction();

            $currentRepairNotes = $repairLog->repairNotes()->pluck('id')->all();
            if (isset($currentRepairNotes)) {
                $currentRepairNotes = collect($currentRepairNotes);
            }
            $savedNoteIds = [];
            if (isset($data['notes'])) {
                foreach ($data['notes'] as $note) {
                    if (!isset($note['id'])) {
                        $repairLog->addNote($note['content'], $person);
                    } else {
                        $savedNoteIds[] = $note['id'];
                        $repairLog->editNote($note['id'], $note['content'], $person);
                    }
                }
            }

            $savedNoteIds = collect($savedNoteIds);
            $diff = $currentRepairNotes->diff($savedNoteIds);
            if ($diff) {
                $oldNotes = $repairLog->repairNotes()->whereIn('id', $diff)->get();
                if ($oldNotes) {
                    foreach ($oldNotes as $oldNote) {
                        $oldNote->delete();
                    }
                }
            }

            DB::commit();

            $repairLog->device->refresh();
            $slug = $repairLog->device->slug;

            return redirect()->route('device_show', ['slug' => $slug])
                             ->with('success', trans('messages.success.repair_complete'));
        } catch (Exception $e) {
            report($e);
            DB::rollBack();

            return back()->with('warning', trans('messages.error_submit_log'));
        }
    }

    public function closeDevice(DeviceCloseRequest $request, Device $device)
    {

        $data = $request->validated();
        $person = PersonRepository::getByUser(auth()->user())->firstOrFail();


        try {
            DB::beginTransaction();
            $oldRepairLog = $device->repairLog;
            $log = RepairLogRepository::createLog($device, $person, $oldRepairLog, $data);
            RepairLogRepository::closeLog($log, $data);
            DB::commit();

            $log = $log->refresh();


            if ($log->status === RepairLog::STATUS_COMPLETED) {
                $mailService = new MailService();
                $mailService->sendDeviceFixed($device, $person);
            }

            return redirect()->route('device_show', ['slug' => $device->slug])
                             ->with('success', trans('messages.success_device_closed'));
        } catch (\Throwable $e) {
            report($e);
            DB::rollBack();
        }

    }
}
