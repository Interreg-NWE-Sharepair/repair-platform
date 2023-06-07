<?php

namespace App\Http\Controllers\Replog\Organisation;

use App\Facades\DeviceRepository;
use App\Facades\EmployeeRepository;
use App\Facades\EventRepository;
use App\Http\Controllers\Replog\ReplogController;
use App\Http\Requests\OrganisationRequestRequest;
use App\Http\Services\MailService;
use App\Models\Device;
use App\Models\Organisation;
use App\Models\OrganisationRequest;
use App\Models\Page;
use App\Models\Tenant;
use Exception;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class OrganisationController extends ReplogController
{
    public function index()
    {
        return Inertia::render('Organisation/Overview', [
            'title' => trans('messages.organisation_overview_title'),
            'body' => trans('messages.organisation_overview_body'),
            'mapbox' => config('mapbox'),
        ]);
    }

    public function create()
    {
        return Inertia::render('Organisation/Create', [
            'data' => [
                'organisation_name' => null,
                'postal_code' => null,
                'municipality' => null,
                'email' => null,
            ],
            'title' => trans('messages.create_organisation_title'),
        ]);
    }

    public function store(OrganisationRequestRequest $request)
    {
        try {
            DB::beginTransaction();
            $data = $request->post();
            $organisationRequest = new OrganisationRequest($data);
            $organisationRequest->save();

            DB::commit();

            $mailService = new MailService();
            $mailService->sendOrganisationSuggestionMail($organisationRequest);

            return redirect(route('location_confirmation'))->with('success', trans('messages.success_organisation_request'));
        } catch (Exception $e) {
            DB::rollBack();
            report($e);

            return back()->withInput()->withErrors(['errors' => trans('messages.something_went_wrong')]);
        }
    }

    public function confirmation(Page $page)
    {
        $page = $page->getOrganisationConfirmationContent(Tenant::REPLOG);

        return Inertia::render('GenericContent', [
            'title' => $page->title,
            'body' => $page->body,
            'flexible' => $page->flexible,
        ]);
    }

    public function show(Organisation $organisation)
    {

        $repairers = EmployeeRepository::countByOrganisation($organisation);

        $devices = DeviceRepository::returnCount(DeviceRepository::queryByOrganisationAndStatus([$organisation->uuid], 'repaired'));


        $futureEvents = EventRepository::getFuture($organisation->uuid)->get();

        return Inertia::render('Organisation/OrganisationDetail', [
            'organisation' => $organisation,
            'repairers' => $repairers,
            'devices' => $devices,
            'title' => $organisation->name,
            'showOrganizer' => true,
            'events' => $futureEvents->isNotEmpty() ? $futureEvents : null,
        ]);
    }
}
