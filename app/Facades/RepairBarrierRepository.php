<?php

namespace App\Facades;

use App\Repository\RepairBarrierRepositoryInterface;
use Illuminate\Support\Facades\Facade;

/**
 * @method static Builder repairArchiveReasons();
 * @method static Builder repairStatusesClosed();
 *
 * @see \App\Repository\RepairBarrierRepositoryInterface
 */
class RepairBarrierRepository extends Facade
{
    protected static function getFacadeAccessor()
    {
        return RepairBarrierRepositoryInterface::class;
    }
}
