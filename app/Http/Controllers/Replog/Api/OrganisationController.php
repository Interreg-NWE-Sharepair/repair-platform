<?php

namespace App\Http\Controllers\Replog\Api;

use App\Facades\EmployeeRepository;
use App\Facades\EventRepository;
use App\Facades\LocationRepository;
use App\Facades\OrganisationRepository;
use App\Http\Controllers\Replog\ReplogController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Str;

class OrganisationController extends ReplogController
{
    /**
     * API call to get all the locations available in current locale
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function getAvailableOrganisations(Request $request)
    {
        $locale = App::getLocale();
        $organisations = LocationRepository::getAvailableOrganisations($locale)->get();

        $organisations = $organisations->sortByDesc('id')->unique('organisation_id')->sortBy('id')->sortBy('is_virtual')->flatten();

        return response($organisations, 200);
    }

    /**
     * Get all the organisations in a certain range, based on the linked locations
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function search(Request $request)
    {
        $place = $request->input('place');
        $query = $request->query('query');
        if (!$place && !$query) {
            return response([]);
        }

        if ($place && !isset($place['center'])) {
            return response([]);
        }

        $locale = App::getLocale();

        /** @var \Illuminate\Database\Eloquent\Builder $locations */
        $locations = LocationRepository::getAvailableOrganisations($locale)->virtual(0)->visible();
        if ($place) {
            [
                $longitude,
                $latitude,
            ] = $place['center'];

        //Locations within 0 - 25 km
            $locations->geofence($latitude, $longitude, 0, 25)->orderBy('distance');
        }

        if (!is_null($query) && $query !== 'null') {
            $query = Str::lower($query);
            $locations->whereRaw("LOWER(locations.name) LIKE ?", ["%{$query}%"]);
        }

        $organisations = $locations->groupBy('organisations.id')->get();
        $organisations = $organisations->unique('organisation_id')->sortBy('distance')->sortBy('is_virtual')->flatten();


        $virtualOrganisations = LocationRepository::getAvailableOrganisations($locale)->visible()->virtual()->get();
        $virtualOrganisations = $virtualOrganisations->sortByDesc('id')->unique('organisation_id')->sortBy('is_virtual')->flatten();

        if ($virtualOrganisations) {
            foreach ($virtualOrganisations as $virtualOrganisation) {
                $organisations[] = $virtualOrganisation;
            }
        }

        return response($organisations);
    }

    public function getRepairersOrganisation($uuid, $pagination = 16)
    {
        /** @var \App\Models\Organisation $organisation */
        $organisation = OrganisationRepository::findByCode($uuid)->firstOrFail();

        $buildQuery = EmployeeRepository::getByOrganisation($organisation)/*->type(Employee::TYPE_REPAIRER)*/
        ;

        /** @var \Illuminate\Database\Eloquent\Builder $employees */
        $employees = EmployeeRepository::search($buildQuery, request());

        return response($employees->paginate($pagination));
    }

    public function getPastEventsOrganisation($uuid, $pagination = 10)
    {
        $events = EventRepository::getPast($uuid)->paginate($pagination);

        return response($events);
    }

    public function getFutureEventsOrganisation($uuid, $pagination = 4)
    {
        $events = EventRepository::getFuture($uuid)->paginate($pagination);

        return response($events);
    }
}
