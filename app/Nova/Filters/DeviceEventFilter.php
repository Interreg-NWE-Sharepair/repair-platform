<?php

namespace App\Nova\Filters;

use App\Facades\EmployeeRepository;
use App\Facades\OrganisationRepository;
use App\Models\Event;
use App\Models\Role;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Laravel\Nova\Filters\Filter;

class DeviceEventFilter extends Filter
{
    /**
     * The filter's component.
     *
     * @var string
     */
    public $component = 'select-filter';

    /**
     * Apply the filter to the given query.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param mixed $value
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function apply(Request $request, $query, $value)
    {
        return $query->whereHas('event', function ($q) use ($value) {
            $q->where('id', $value);
        });
    }

    /**
     * Get the filter's available options.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function options(Request $request)
    {
        $user = $request->user();
        $employees = EmployeeRepository::getByUser($user)->get();

        $organisationsArray = [];
        $eventsArray = [];

        if ($employees && !$user->hasRole([
                Role::STATIK,
                Role::ADMIN,
                Role::ENTITY_ADMIN,
            ])) {
            foreach ($employees as $employee) {
                $organisation = $employee->organisation;
                $organisationsArray[$organisation->name] = $organisation->id;
            }
        } else {
            $organisations = OrganisationRepository::all();
            foreach ($organisations as $organisation) {
                $organisationsArray[$organisation->name] = $organisation->id;
            }
        }

        if ($organisationsArray) {
            $events = Event::query()->whereHas('organisation', function ($q) use ($organisationsArray) {
                $q->whereIn('id', $organisationsArray);
            })->where('date_start', '>=', Carbon::now()->subYear())->get();

            if ($events) {
                foreach ($events as $event) {
                    $eventName = $event->locale_name . ' (' . $event->date_formatted . ')';
                    $eventsArray[$eventName] = $event->id;
                }
            }
        } else {
            $events = Event::all();
            foreach ($events as $event) {
                $eventName = $event->locale_name . ' (' . $event->date_formatted . ')';
                $eventsArray[$eventName] = $event->id;
            }
        }

        return $eventsArray;
    }
}
