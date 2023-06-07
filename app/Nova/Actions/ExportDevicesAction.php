<?php

namespace App\Nova\Actions;

use App\Facades\EmployeeRepository;
use App\Facades\OrganisationRepository;
use App\Models\Device;
use App\Models\Role;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Maatwebsite\LaravelNovaExcel\Actions\DownloadExcel;

class ExportDevicesAction extends DownloadExcel
{
    use InteractsWithQueue, Queueable;

    public function query()
    {
        if ($this->request->all()['resources'] == 'all') {
            $organisations = $this->getOrganisations();

            return Device::query()->with('repairLog')->whereHas('organisation', function ($q) use ($organisations) {
                $q->whereIn('id', $organisations);
            });
        } else {
            $deviceIds = explode(',', $this->request->all()['resources']);

            return Device::query()->whereIn('id', $deviceIds);
        }
    }

    /**
     * Get the fields available on the action.
     *
     * @return array
     */
    public function fields()
    {
        return [];
    }

    /**
     * @param Device $device
     * @return array
     */
    public function map($device): array
    {
        /** @var \App\Models\RepairLog $repairLog */
        $repairLog = $device->repairLog;
        $status = $device->latest_status;

        return [
            $device->id,
            $device->brand_name,
            $device->model_name,
            $device->deviceType->name ?? 'No device type selected',
            $status ? __("messages.status_{$status}") : 'No repair status found',
            $device->completedRepairStatus->name ?? null,
            $device->device_description,
            $device->issue_description,
            $device->organisation->name,
            $device->first_name,
            $device->last_name,
            $device->email,
            $device->telephone,
            $device->created_at->isoFormat('DD MMMM YYYY'),
            $device->updated_at->isoFormat('DD MMMM YYYY'),
            $device->event ? $device->event->locale_name . ' (' . $device->event->date_formatted . ')' : '-',
            $device->slug,
            $repairLog ? optional($repairLog->person)->full_name : 'No repairer found',
            $repairLog && $repairLog->repairNotes->isNotEmpty() ? $repairLog->repairNotes->implode('content', ', ') : 'No notes found',
            $repairLog ? strip_tags($repairLog->diagnosis) : 'No diagnosis found',
            $repairLog ? strip_tags($repairLog->root_cause) : 'No root cause found',
            $repairLog ? strip_tags($repairLog->used_materials) : 'No used materials found',
            $repairLog ? strip_tags($repairLog->used_links) : 'No used links found',
            $repairLog && $repairLog->repairBarriers->isNotEmpty() ? $repairLog->repairBarriers->implode('name', ', ') : 'No repair barriers found',
            $repairLog->reminder_mail_sent ?? 'No reminder mail send',
        ];
    }

    public function headings(): array
    {
        return [
            'Id',
            'Brand name',
            'Model name',
            'Device type',
            'Repair status',
            'Completed status',
            'Device description',
            'Problem description',
            'Organisation',
            'First name',
            'Last name',
            'Email',
            'Telephone',
            'Date creation',
            'Date modified',
            'Event',
            'Slug',
            'Repairer name',
            'Notes',
            'Diagnosis',
            'Root Cause',
            'Used materials',
            'Used links',
            'Repair barriers',
            'Reminder mail send',
        ];
    }

    public function name()
    {
        return 'Exporteer geselecteerde toestellen';
    }

    private function getOrganisations()
    {
        $user = $this->request->user();
        $employees = EmployeeRepository::getByUser($user)->get();

        $organisationsArray = [];

        if ($employees && !$user->hasRole([
                Role::STATIK,
                Role::ADMIN,
            ])) {
            foreach ($employees as $employee) {
                $organisation = $employee->organisation;
                $organisationsArray[] = $organisation->id;
            }
        } else {
            $organisations = OrganisationRepository::all();
            foreach ($organisations as $organisation) {
                $organisationsArray[] = $organisation->id;
            }
        }

        return $organisationsArray;
    }
}
