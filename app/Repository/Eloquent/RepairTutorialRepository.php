<?php

namespace App\Repository\Eloquent;

use App\Models\RepairTutorial;
use App\Repository\RepairTutorialRepositoryInterface;
use Illuminate\Database\Eloquent\Builder;

class RepairTutorialRepository extends BaseRepository implements RepairTutorialRepositoryInterface
{
    /**
     * @param \App\Models\RepairTutorial $model
     */
    public function __construct(RepairTutorial $model)
    {
        parent::__construct($model);
    }

    public function getCommonDeviceTypeTutorials($locale, $deviceType, $commonIssues, $other = false): Builder
    {
        $issueIds = [];
        if ($commonIssues) {
            foreach ($commonIssues as $issue) {
                $issueIds[] = $issue->id;
            }
        }

        return $this->model::query()->isLocalized($locale)->whereHas('commonDeviceTypeIssue', function ($q) use (
            $issueIds,
            $other
        ) {
            if (!$other && $issueIds) {
                $q->whereIn('id', $issueIds);
            } elseif ($issueIds) {
                $q->whereNotIn('id', $issueIds);
            }
        })->whereHas('deviceType', function ($q) use ($deviceType) {
            $q->where('id', $deviceType->id);
        });
    }

    public function getDeviceTypeTutorials($locale, $deviceType): Builder
    {
        return $this->model::query()->isLocalized($locale)->whereHas('deviceType', function ($q) use ($deviceType) {
            $q->where('id', $deviceType->id);
        })->whereDoesntHave('commonDeviceTypeIssue');
    }
}
