<?php

namespace App\Http\Controllers\Replog\Organisation;

use App\Exceptions\RepairerOrganisationException;
use App\Facades\EmployeeOrganisationRepository;
use App\Facades\EmployeeRepository;
use App\Facades\EventRepository;
use App\Facades\OrganisationRepository;
use App\Facades\PersonRepository;
use App\Http\Controllers\Replog\ReplogController;
use App\Models\Employee;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class OrganisationDashboardController extends ReplogController
{
    /**
     * @param $organisation
     * @throws \App\Exceptions\RepairerOrganisationException
     */
    protected function canViewOrganisation($organisation): void
    {
        if (!EmployeeOrganisationRepository::canViewOrganisation(auth()->user(), $organisation)) {
            throw new RepairerOrganisationException($organisation);
        }
    }

    /**
     * @param $locationCode
     * @return \Inertia\Response
     * @throws \App\Exceptions\RepairerOrganisationException
     */
    public function index($locationCode)
    {
        $organisation = OrganisationRepository::findBySlug($locationCode, app()->getLocale())->firstOrFail();

        $this->canViewOrganisation($organisation);

        /*$employees = EmployeeOrganisationRepository::getRepairersByOrganisation($organisation)
                                                   ->type(Employee::TYPE_REPAIRER)->limit(5)->inRandomOrder()->get();*/

        $person = PersonRepository::getByUser(Auth::user())->first();

        $employee = EmployeeRepository::getByPerson($person)->first();

        $futureEvents = EventRepository::getFuture($organisation->uuid)->limit(3)->get();

        return Inertia::render('Organisation/GeneralOverview', [
            //'employees' => $employees,
            'repairer_base_info' => true,
            'events' => $futureEvents->isNotEmpty() ? $futureEvents : null,
            'organisation' => $organisation,
            'repairer' => (bool) $employee,
            'title' => $organisation->name,
        ]);
    }

    /**
     * @throws \App\Exceptions\RepairerOrganisationException
     */
    public function devices($locationCode)
    {
        $organisation = OrganisationRepository::findBySlug($locationCode, app()->getLocale())->firstOrFail();

        $this->canViewOrganisation($organisation);

        $deviceFilters = $this->getDeviceFilterOptions($organisation);

        //Data is passed through API call data()
        return Inertia::render('Organisation/DevicesOverview', [
            'deviceFilters' => $deviceFilters,
            'organisation' => $organisation,
            'title' => trans('messages.location_overview_devices_title', ['location' => $organisation->name]),
        ]);
    }

    /**
     * @throws \App\Exceptions\RepairerOrganisationException
     */
    public function repairerDeviceHistory($locationCode)
    {
        $organisation = OrganisationRepository::findBySlug($locationCode, app()->getLocale())->firstOrFail();

        $this->canViewOrganisation($organisation);

        return Inertia::render('Organisation/Repairer/FixedDevices', [
            'organisation' => $organisation,
            'title' => trans('messages.location_overview_devices_fixed_title', ['location' => $organisation->name]),
        ]);
    }

    /**
     * @throws \App\Exceptions\RepairerOrganisationException
     */
    public function repairers($locationCode)
    {
        /** @var \App\Models\Organisation $organisation */
        $organisation = OrganisationRepository::findBySlug($locationCode, app()->getLocale())->firstOrFail();

        $this->canViewOrganisation($organisation);

        return Inertia::render('Organisation/RepairersOverview', [
            'organisation' => $organisation,
            'title' => trans('messages.repairer_location_repairers_overview_title', ['location' => $organisation->name]),
        ]);
    }

    /**
     * @throws \App\Exceptions\RepairerOrganisationException
     */
    public function events($locationCode)
    {
        /** @var \App\Models\Organisation $organisation */
        $organisation = OrganisationRepository::findBySlug($locationCode, app()->getLocale())->firstOrFail();

        $this->canViewOrganisation($organisation);

        $futureEvents = EventRepository::getFuture($organisation->uuid)->get();
        $pastEvents = EventRepository::getPast($organisation->uuid)->limit(3)->get();

        /** @var \App\Models\Person $person */
        $person = PersonRepository::getByUser(Auth::user())->first();
        $attendingEvents = [];
        if ($person) {
            $attendingEvents = $person->getAttendingEvents($futureEvents);
        }

        $isRepairer = EmployeeRepository::getByPerson($person)->type(Employee::TYPE_REPAIRER)
                                        ->organisation($organisation)->exists();

        return Inertia::render('Organisation/Event/Overview', [
            'futureEvents' => $futureEvents->isNotEmpty() ? $futureEvents : null,
            'pastEvents' => $pastEvents->isNotEmpty() ? $pastEvents : null,
            'organisation' => $organisation,
            'repairerEvents' => $attendingEvents ?? [],
            'repairer' => (bool) $isRepairer,
            'title' => trans('messages.repairer_location_events_overview_title', ['location' => $organisation->name]),
        ]);
    }

    /**
     * @throws \App\Exceptions\RepairerOrganisationException
     */
    public function pastEvents($locationCode)
    {
        /** @var \App\Models\Organisation $organisation */
        $organisation = OrganisationRepository::findBySlug($locationCode, app()->getLocale())->firstOrFail();

        $this->canViewOrganisation($organisation);

        return Inertia::render('Organisation/Event/PastOverview', [
            'organisation' => $organisation,
            'title' => trans('messages.location_past_events_overview_title', ['location' => $organisation->name]),
        ]);
    }

    public function impact($locationCode)
    {
        /** @var \App\Models\Organisation $organisation */
        $organisation = OrganisationRepository::findBySlug($locationCode, app()->getLocale())->firstOrFail();

        $this->canViewOrganisation($organisation);

        return Inertia::render('Organisation/ImpactOverview', [
            'organisation' => $organisation,
            'title' => trans('messages.location_impact', ['location' => $organisation->name]),
        ]);
    }
}
