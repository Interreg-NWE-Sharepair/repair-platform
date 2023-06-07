<?php

namespace App\Nova\Actions;

use App\Repository\RestartersRepositoryInterface;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;

class SyncDataFromRestarters extends Action
{
    use InteractsWithQueue, Queueable;

    /**
     * @var RestartersRepositoryInterface
     */
    private $restarterRepository;

    public function __construct()
    {
        $this->restarterRepository = resolve(RestartersRepositoryInterface::class);
        $this->confirmText = __('Are you sure you want to sync this organisation? The restarters data will prioritise over some of your current data.');
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
        /** @var \App\Models\Organisation $model */
        foreach ($models as $model){
            try {
                DB::beginTransaction();
                $this->restarterRepository->syncOrganisationData($model);
                DB::commit();
            } catch (\Throwable $throwable){
                DB::rollBack();
                throw $throwable;
            }
        }

        return Action::message('The selected organisations are now synced to its restarters counter part.');
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
}
