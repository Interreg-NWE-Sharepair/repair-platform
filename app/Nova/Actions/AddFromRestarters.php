<?php

namespace App\Nova\Actions;

use App\Models\Location;
use App\Models\Organisation;
use App\Repository\Api\RestartersRepository;
use App\Repository\RestartersRepositoryInterface;
use Brightspot\Nova\Tools\DetachedActions\DetachedAction;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;
use Laravel\Nova\Fields\Select;

class AddFromRestarters extends DetachedAction
{
    use InteractsWithQueue, Queueable;

    /**
     * @var RestartersRepository
     */
    private $restarterRepository;

    public function __construct()
    {
        $this->restarterRepository = resolve(RestartersRepositoryInterface::class);
    }


    /**
     * Perform the action on the given models.
     *
     * @param  \Laravel\Nova\Fields\ActionFields  $fields
     * @return mixed
     */
    public function handle(ActionFields $fields)
    {
        $restartersId = $fields->get('restarters_id');
        if (!$restartersId){
            return DetachedAction::danger('Please select a restarters group.');
        }

        try {
            DB::beginTransaction();
            $organisation = new Organisation();
            $organisation->restarters_id = $restartersId;
            $this->restarterRepository->syncOrganisationData($organisation);
            DB::commit();
        } catch (\Throwable $throwable){
            DB::rollBack();
            throw $throwable;
        }

        return DetachedAction::message('The organisation is now linked to its restarters counter part.');
    }

    public function label()
    {
        return parent::label();
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

            $groupHasOrganisation = $organisations->contains(function ($value, $key) use ($groupId) {
                return $value->restarters_id == $groupId;
            });

            if (!$groupHasOrganisation){
                $restarterOptions[$groupId] = "{$groupName} (ID: {$groupId})";
            }
        }

        return [
            Select::make('Restarters group', 'restarters_id')
                ->options($restarterOptions)
                ->required()
        ];
    }
}
