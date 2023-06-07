<?php

namespace App\Nova\Actions;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Collection;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;

class DeActivatePerson extends Action
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
        foreach ($models as $model) {
            /** @var \App\Models\Person $model */
            if ($model->user->hasVerifiedEmail()) {
                $model->user->email_verified_at = null;
                $model->user->ignore_automated_emails = 1;
                $model->user->save();
            }
        }

        return Action::message(trans('nova.repairer_deactivated'));
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
        $label = 'Deactivate access?';

        return $label;
    }
}
