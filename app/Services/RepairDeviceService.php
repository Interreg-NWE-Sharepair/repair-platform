<?php

namespace App\Services;

use App\Models\CompletedRepairStatus;
use App\Models\Device;
use App\Models\DeviceType;
use App\Models\Location;
use App\Models\RepairLog;
use Illuminate\Support\Facades\DB;
use Throwable;

class RepairDeviceService
{
    public function logNewCompletedDevice($data)
    {
        try {
            DB::beginTransaction();

            if (!empty($data['used_materials'])) {
                $data['is_using_spare_parts'] = true;
            }

            $device = new Device();
            $device->fill($data);

            $organisation = Location::where('uuid', $data['location'])->first()->organisation;
            $device->organisation()->associate($organisation);

            $deviceType = DeviceType::where('uuid', $data['device_type'])->first();
            $device->deviceType()->associate($deviceType);

            $device->save();

            $log = new RepairLog();
            $log->fill($data);

            $log->status = RepairLog::STATUS_COMPLETED;
            $log->completedRepairStatus()->associate(CompletedRepairStatus::where('code', $data['completed_status'])->first());

            $log->device()->associate($device);

            $log->save();

            DB::commit();
        } catch (Throwable $throwable) {
            DB::rollBack();
            throw $throwable;
        }

        return $log;
    }
}
