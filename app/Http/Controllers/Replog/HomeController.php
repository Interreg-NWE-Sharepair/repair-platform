<?php

namespace App\Http\Controllers\Replog;

use App\Facades\EmployeeRepository;
use App\Facades\LocationRepository;
use App\Models\Device;
use Illuminate\Support\Facades\App;
use Inertia\Inertia;

class HomeController extends ReplogController
{
    /**
     * Show the application dashboard.
     */
    public function index()
    {
        $locale = App::getLocale();

        $repairers = EmployeeRepository::countByOrganisation();
        $locations = LocationRepository::getAvailableOrganisations($locale)->get();

        $locations = $locations->sortByDesc('id')->unique('organisation_id')->sortBy('id')->sortBy('is_virtual')->take(3)->flatten();

        $devices = Device::fixed()->count();

        return Inertia::render('Home', [
            'stats' => [
                'repairers' => $repairers,
                'devices' => $devices,
            ],
            'title' => 'Home',
            'mapbox' => config('mapbox'),
            'locations' => $locations,
        ]);
    }
}
