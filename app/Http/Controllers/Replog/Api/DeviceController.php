<?php

namespace App\Http\Controllers\Replog\Api;

use App\Facades\DeviceRepository;
use App\Facades\EmployeeRepository;
use App\Facades\PersonRepository;
use App\Http\Controllers\Replog\ReplogController;
use App\Models\Device;
use App\Models\Event;
use App\Models\Organisation;
use App\Models\RepairLog;
use Illuminate\Support\Facades\Auth;

class DeviceController extends ReplogController
{
    public function getFixedDevices($json = true)
    {
        $employee = EmployeeRepository::getByUser(Auth::user())->firstOrFail();
        $organisation = $employee->organisation;
        $devices = Device::query()->fixed()->organisation($organisation)->orderBy('created_at')
                         ->with('repairLogs', 'repairLogs.repairNotes', 'repairLogs.repairLinks', 'repairLogs.media', 'event')
                         ->get();

        if ($json) {
            return response($devices);
        }

        return $devices->toArray();
    }

    public function getRepairedDevices()
    {
        $query = DeviceRepository::getActive(null, true);
        $devices = DeviceRepository::filter($query, true);

        $devices->reorder('devices.created_at', 'desc');

        return $devices->paginate(12);
    }

    public function getRepairerFixed()
    {
        $employee = EmployeeRepository::getByUser(Auth::user())->firstOrFail();
        $organisation = $employee->organisation;

        $devices = Device::query()->fixed($employee->person)->organisation($organisation)->orderBy('created_at');

        return $devices->paginate(12);
    }

    public function getFixedDevicesRepairerLocation(Organisation $organisation)
    {
        $person = PersonRepository::getByUser(auth()->user())->firstOrFail();

        //$devices = Device::query()->fixed($person)->organisation($uuid)->orderBy('created_at');
        $devices = DeviceRepository::getFixedPersonOrganisation($person, $organisation->uuid)->orderBy('created_at');

        return $devices->paginate(12);
    }

    public function getActiveDevices(Organisation $organisation)
    {
        $devices = DeviceRepository::getActive($organisation->uuid);
        $devices = DeviceRepository::filter($devices);

        //dd($devices->toSql());
        return $devices->paginate(12);
    }

    public function getLocationDeviceOverview(Organisation $organisation)
    {
        $devices = DeviceRepository::getActive($organisation->uuid)->whereIn('latest_status', [
            RepairLog::STATUS_OPEN,
            RepairLog::STATUS_REOPENED,
        ])->orderBy('created_at')->limit(3);

        return $devices->paginate(3);
    }

    public function getPersonalDevices()
    {
        $person = PersonRepository::getByUser(auth()->user())->firstOrFail();
        $devices = DeviceRepository::getPersonalDevices($person);

        return $devices->paginate(15);
    }

    public function getRepairerDevices(Organisation $organisation)
    {
        $person = PersonRepository::getByUser(auth()->user())->firstOrFail();
        $devices = DeviceRepository::getLastRepairStatus([RepairLog::STATUS_IN_REPAIR], $person, $organisation);

        return $devices->paginate(5);
    }

    public function getToRepairDevices(Organisation $organisation)
    {

        return DeviceRepository::getLastNewDevices($organisation->uuid, 5)->paginate(5);
    }

    public function getEventDevices(Event $event)
    {
        $request = request();
        $searchTerm = $request->query('search');
        $devices = Device::query()->event($event->id)->whereNull('event_follow_up_id');
        $devices = $devices->whereLike($devices, [
            'id',
            'brand_name',
            'model_name',
            'first_name',
            'last_name',
        ], $searchTerm)->get();

        $dataList = [];
        if ($devices) {
            foreach ($devices as $index => $device) {
                /** @var Device $device */
                $dataList[$index] = [
                    'value' => $device->id,
                    'name' => '#' . $device->id . ' ' . $device->getFullName() . ' (' . $device->getName() .')',
                ];
            }
        }

        return response()->json($dataList);
    }
}
