<?php

namespace App\Repository\Eloquent;

use App\Models\CompletedRepairStatus;
use App\Models\Device;
use App\Models\RepairBarrier;
use App\Repository\RepairBarrierRepositoryInterface;
use Illuminate\Database\Eloquent\Builder;

class RepairBarrierRepositoryRepository extends BaseRepository implements RepairBarrierRepositoryInterface
{
    public function __construct(RepairBarrier $model)
    {
        parent::__construct($model);
    }

    public function repairArchiveReasons(): Builder
    {
        return $this->model::visible()->byStatusCode(CompletedRepairStatus::STATUS_ARCHIVE);
    }

    public function repairStatusesClosed(): Builder
    {
        return $this->model::visible()->byStatusCode(CompletedRepairStatus::STATUS_END_OF_LIFE);
    }
}
