<?php

namespace App\Nova\Actions;

use App\Models\RepairLog;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Collection;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;

class UserDeleteAction extends Action
{
    use InteractsWithQueue, Queueable;

    /**
     * Perform the action on the given models.
     *
     * @param \Laravel\Nova\Fields\ActionFields $fields
     * @param \Illuminate\Support\Collection $models
     * @return mixed
     */
    public function handle(ActionFields $fields, Collection $models)
    {
        //$people = [];
        foreach ($models as $model) {
            /** @var \App\Models\User  $model */
            $person =  $model->person;
            $hasOpenLogs = RepairLog::query()->where('person_id', $person->id)->whereNotIn('status', [ RepairLog::STATUS_COMPLETED, RepairLog::STATUS_REOPENED])->exists();
            if ($hasOpenLogs) {
                return Action::danger("$person->full_name still has linked unrepaired devices. Unlink those first.");
            }
            $model->delete();
            return Action::message("$person->full_name has been deleted.");
        }
    }

    /**
     * Get the fields available on the action.
     *
     * @return array
     */
    public function fields()
    {
        return [];
    }

    public function name()
    {
        return 'Delete User';
    }

    public function confirmButtonText($text)
    {
        return 'Delete User';
    }
}
