<?php

namespace App\Repository;

use App\Models\Device;
use App\Models\Person;
use App\Models\RepairLog;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

/**
 * @see \App\Repository\Eloquent\RepairLogRepository
 */
interface RepairLogRepositoryInterface
{
    public function all(): Collection;

    public function findByUuid($uuid): RepairLog;

    public function hasDevicesAssigned(Person $person, array $repairStatuses): Builder;

    public function hasMaxAmountDevices(Person $person): bool;

    public function createLog(Device $device, Person $person, RepairLog $oldLog = null, $data = null): RepairLog;

    public function updateDevice(RepairLog $repairLog, $data): void;

    public function reopenDevice(RepairLog $repairLog, $data): void;

    public function updateLog(RepairLog $repairLog, $data, $isAdmin = null): void;

    public function closeLog(RepairLog $repairLog, $data): void;
}
