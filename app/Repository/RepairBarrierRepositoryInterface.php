<?php

namespace App\Repository;

use Illuminate\Database\Eloquent\Builder;

/**
 * @see \App\Repository\Eloquent\RepairBarrierRepositoryRepository
 */
interface RepairBarrierRepositoryInterface
{
    public function repairArchiveReasons(): Builder;

    public function repairStatusesClosed(): Builder;
}
