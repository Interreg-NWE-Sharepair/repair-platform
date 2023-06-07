<?php

namespace App\Facades;

use App\Models\Device;
use App\Models\Person;
use App\Models\RepairLog;
use App\Repository\RepairLogRepositoryInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Facade;

/**
 * @method static Collection all()
 * @method static RepairLog findByUuid(string $uuid)
 * @method static Builder hasDevicesAssigned(Person $person, array $repairStatuses)
 * @method static bool hasMaxAmountDevices(Person $person)
 * @method static RepairLog createLog(Device $device, Person $person, RepairLog $old = null, $data = null)
 * @method static void updateDevice(RepairLog $repairLog, $data)
 * @method static void reopenDevice(RepairLog $repairLog, $data)
 * @method static void updateLog(RepairLog $repairLog, $data, $isAdmin = null)
 * @method static void closeLog(RepairLog $repairLog, $data)
 *
 * @see RepairLogRepositoryInterface
 */
class RepairLogRepository extends Facade
{
    protected static function getFacadeAccessor()
    {
        return RepairLogRepositoryInterface::class;
    }
}
