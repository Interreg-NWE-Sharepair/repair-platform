<?php

namespace App\Nova\Actions;

use App\Models\Organisation;
use App\Repository\Api\RestartersRepository;
use App\Repository\RestartersRepositoryInterface;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;
use Laravel\Nova\Fields\Select;

class LinkToRestarters extends Action
{
    use InteractsWithQueue, Queueable;

    /**
     * @var RestartersRepository
     */
    private $restarterRepository;

    public function __construct()
    {
        $this->restarterRepository = resolve(RestartersRepositoryInterface::class);
        $this->confirmText = __('Are you sure you want to link this organisation? The restarters data will be synced and your old data will be lost.');
    }

    public function actionClass()
    {
        return 'bg-danger text-white';
    }

    /**
     * Perform the action on the given models.
     *
     * @param  \Laravel\Nova\Fields\ActionFields  $fields
     * @param  \Illuminate\Support\Collection  $models
     * @return mixed
     */
    public function handle(ActionFields $fields, Collection $models)
    {
        $restartersId = $fields->get('restarters_id');
        if (!$restartersId){
            return Action::danger('Please select a restarters group.');
        }

        if (count($models) !== 1){
            return Action::danger('Only one organisation can be added to a restarters group.');
        }

        /** @var \App\Models\Organisation $model */
        foreach ($models as $model){
            try {
                DB::beginTransaction();
                $model->restarters_id = $restartersId;
                $this->restarterRepository->syncOrganisationData($model);
                DB::commit();
            } catch (\Throwable $throwable){
                DB::rollBack();
                throw $throwable;
            }
        }

        return Action::message('The organisation is now linked to its restarters counter part.');
    }

    /**
     * Get the fields available on the action.
     *
     * @return array
     */
    public function fields()
    {
        $restarterGroups = $this->restarterRepository->getGroups();

        $restarterOptions = [];
        $organisations = Organisation::fromRestarters()->get();

        foreach ($restarterGroups as $group){
            $groupName = $group['name'];
            $groupId = $group['id'];

            $groupHasOrganisations = $organisations->contains(function ($value, $key) use ($groupId) {
                return $value->restarters_id == $groupId;
            });

            if (!$groupHasOrganisations){
                $restarterOptions[$groupId] = "{$groupName} (ID: {$groupId})";
            }
        }

        return [
            Select::make('Restarters group', 'restarters_id')
                ->options($restarterOptions)
                ->required()
                ->help(__('Are you sure you want to link this organisation? The restarters data will prioritise over some of your current data.'))
        ];
    }
}
