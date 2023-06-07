<?php

namespace App\Facades;

use App\Repository\RepairTutorialRepositoryInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Facade;

/**
 * @method static Builder getCommonDeviceTypeTutorials($locale, $deviceType, $commonIssue, $other = false)
 * @method static Builder getDeviceTypeTutorials($locale, $deviceType)
 *
 * @see \App\Repository\RepairTutorialRepositoryInterface
 *
 */
class RepairTutorialRepository extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return RepairTutorialRepositoryInterface::class;
    }
}
