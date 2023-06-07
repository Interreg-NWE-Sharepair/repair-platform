<?php

namespace App\Nova\Actions;

use App\Services\LocationSuggestionService;
use Illuminate\Database\Eloquent\Collection;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;

class ApproveLocationSuggestion extends Action
{
    public $showOnTableRow = true;

    public $showOnIndex = false;

    /**
     * @var \App\Services\LocationSuggestionService
     */
    private LocationSuggestionService $locationSuggestionService;

    public function __construct()
    {
        $this->locationSuggestionService = resolve(LocationSuggestionService::class);
    }

    public function handle(ActionFields $field, Collection $models)
    {
        if (count($models) > 1) {
            return Action::message(__('nova.action_prevent_multiple'));
        }

        /** @var \App\Models\LocationSuggestion $locationSuggestion */
        $locationSuggestion = $models->first();

        if ($locationSuggestion->is_approved) {
            return Action::danger(__('nova.action_prevent_multiple'));
        }

        $location = $this->locationSuggestionService->approveLocationSuggestion($locationSuggestion);

        return Action::redirect(config('nova.path') . "/resources/location/{$location->id}");
        //return Action::message(__('nova.location_suggestion_approved'));
    }

    public function name()
    {
        $label = trans('nova.approve_location_suggestion');

        return $label;
    }
}
