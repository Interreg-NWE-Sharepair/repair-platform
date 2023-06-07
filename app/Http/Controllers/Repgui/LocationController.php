<?php

namespace App\Http\Controllers\Repgui;

use App\Facades\EmployeeOrganisationRepository;
use App\Models\Device;
use App\Models\Location;
use Artesaos\SEOTools\Facades\SEOMeta;

class LocationController extends RepguiController
{
    public function index()
    {
        SEOMeta::setTitle(trans('repgui.repair_it_map'))->setDescription(trans('repgui.repair_it_map_text'));

        return view('repgui.pages.locations.index');
    }

    public function redirect(Location $location)
    {
        return redirect()->route('locations_show', [$location->slug]);
    }

    public function show(Location $location)
    {
        SEOMeta::setTitle($location->name)->setDescription($location->description);

        $amountRepairers = EmployeeOrganisationRepository::getRepairersByOrganisation($location->organisation)->count();
        $amountDevices = Device::fixed()->organisation($location->organisation->uuid)->count();

        return view('repgui.pages.locations.location', compact([
            'location',
            'amountRepairers',
            'amountDevices',
        ]));
    }
}
