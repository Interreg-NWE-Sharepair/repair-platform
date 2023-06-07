<?php

namespace App\Http\Controllers\Replog;

use App\Facades\EmployeeOrganisationRepository;
use App\Facades\EmployeeRepository;
use App\Facades\EventRepository;
use App\Facades\RepairBarrierRepository;
use App\Http\Controllers\Controller;
use App\Models\CompletedRepairStatus;
use App\Models\Device;
use App\Models\DeviceType;
use App\Models\Organisation;
use App\Models\RepairLog;
use App\Models\Role;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ReplogController extends Controller
{
    /**
     * @return bool
     */
    protected function isAdmin($organisation = null): bool
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        if ($user && $user->hasRole([
                Role::STATIK,
            ]) && !$user->hasRole([Role::REPAIRER])) {

            return true;
        }

        //$employee = EmployeeRepository::getByUser(auth()->user())->first();
        $employee = EmployeeRepository::getByUser(auth()->user())->organisation($organisation)->first();

        return $employee && $employee->hasRole([
                Role::ADMIN,
                Role::ENTITY_ADMIN,
            ]) && !$employee->hasRole([
                Role::REPAIRER,
                Role::EVENT_ORGANIZER,
            ]);
    }

    /**
     * @param \App\Models\Organisation $organisation
     * @return bool
     */
    protected function isEventOrganizer(Organisation $organisation): bool
    {
        return EmployeeOrganisationRepository::isEventOrganizerOrganisation(auth()->user(), $organisation);
    }

    /**
     * @param \App\Models\Organisation $organisation
     * @return bool
     */
    protected function isEntityAdmin(Organisation $organisation): bool
    {
        return EmployeeOrganisationRepository::isEntityAdminOrganisation(auth()->user(), $organisation);
    }

    /**
     * Checks if device has a log and is fixed and or already bound to a repairer.
     *
     * @param \App\Models\Device $device
     * @return bool
     */
    public function isDeviceAvailable(Device $device): bool
    {
        $lastLog = $device->repairLog;

        return !($lastLog && in_array($lastLog->status, [
                RepairLog::STATUS_COMPLETED,
                RepairLog::STATUS_IN_REPAIR,
            ], true));
    }

    //Return the frontend statuses
    public function getStatusOptions(): array
    {
        return $this->getFrontendStatuses();
    }

    public function getRepairArchiveReasons()
    {
        return RepairBarrierRepository::repairArchiveReasons()->get();
    }

    public function getRepairBarriers()
    {
        return RepairBarrierRepository::repairStatusesClosed()->get();
    }

    public function getRepairStatusesCompleted()
    {
        return CompletedRepairStatus::visible()->get();
    }

    public function getRepairStatusClose()
    {
        return CompletedRepairStatus::visible()->where('code', 'archive')->get();
    }

    /**
     * Get all the device types sorted by parent
     *
     * @return array
     */
    public function getDeviceTypeOptions(): array
    {
        $deviceTypes = DeviceType::visible()->showOnRepair()->with('parent')->orderBy('id')->get();

        $deviceTypeOptions = [];
        //Add all parents to array
        foreach ($deviceTypes as $deviceType) {
            if (!$deviceType->parent && !isset($deviceTypeOptions[$deviceType->id])) {
                $deviceTypeOptions[$deviceType->id]['name'] = $deviceType->name;
                $deviceTypeOptions[$deviceType->id]['value'] = $deviceType->id;
                $deviceTypeOptions[$deviceType->id]['children'] = [];
            }
        }
        //Add all children to parent array
        foreach ($deviceTypes as $deviceType) {
            if ($deviceType->parent) {
                $deviceTypeOption = [
                    'name' => $deviceType->name,
                    'value' => $deviceType->id,
                ];

                if ($deviceTypeOptions[$deviceType->parent->id]) {
                    $deviceTypeOptions[$deviceType->parent->id]['children'][] = $deviceTypeOption;
                }
            }
        }

        foreach ($deviceTypeOptions as $index => $parentOptions) {
            $options = collect($parentOptions['children'])->sortBy('name')->values()->all();
            $parentOptions['children'] = $options;
            $deviceTypeOptions[$index] = $parentOptions;
        }

        //sort by key
        ksort($deviceTypeOptions);

        $orderedOptions = [];
        //Keep same order but with other index keys (0-4)
        $deviceTypeOptions = array_merge($orderedOptions, $deviceTypeOptions);

        return $deviceTypeOptions;
    }

    /**
     * Returns the frontend filter options
     *
     * @param null $organisation
     * @return array
     */
    public function getDeviceFilterOptions($organisation = null): array
    {
        $filters = [
            'deviceType' => $this->getDeviceTypeOptions(),
            'status' => $this->getStatusOptions(),
            'order' => [
                'asc' => trans('messages.devices_sort_date_asc'),
                'desc' => trans('messages.devices_sort_date_desc'),
                'new' => trans('messages.devices_sort_status'),
            ],
        ];

        $events = $organisation ? EventRepository::getAllForOrganisation($organisation->uuid)
                                                 ->orderBy('date_start')->get() : null;
        if ($events) {
            $today = Carbon::now()->setTime(0, 0);
            $eventArray = [
                0 => [
                    'name' => trans('messages.future_events'),
                    'children' => [],
                ],
                1 => [
                    'name' => trans('messages.past_events'),
                    'children' => [],
                ],
            ];
            foreach ($events as $event) {
                if ($event->date_start->greaterThanOrEqualTo($today)) {

                    $eventArray[0]['children'][] = [
                        'name' => $event->date_start->format('d/m/Y') . ' - ' . $event->name,
                        'value' => $event->id,
                    ];
                } else {
                    $eventArray[1]['children'][] = [
                        'name' => $event->date_start->format('d/m/Y') . ' - ' . $event->name,
                        'value' => $event->id,
                        'timestamp' => $event->date_start->getTimestamp()
                    ];
                }
            }
            
            if ($eventArray[1] && $eventArray[1]['children']) {
                usort($eventArray[1]['children'], function($item1, $item2) {
                    return $item2['timestamp'] <=> $item1['timestamp'];
                });
            }

            $filters['events'] = $eventArray;
        }

        return $filters;
    }
}
