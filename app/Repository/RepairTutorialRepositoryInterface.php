<?php

namespace App\Repository;

use Illuminate\Database\Eloquent\Builder;

/**
 * @see \App\Repository\Eloquent\RepairTutorialRepository
 */
interface RepairTutorialRepositoryInterface
{
    public function getCommonDeviceTypeTutorials($locale, $deviceType, $commonIssue, $other = false): Builder;

    public function getDeviceTypeTutorials($locale, $deviceType): Builder;
}
