<?php

namespace App\Http\Controllers\Replog\Repairer\Device;

use App\Http\Controllers\Replog\ReplogController;
use Inertia\Inertia;

class FixedOverviewController extends ReplogController
{
    public function index()
    {
        $deviceFilters = $this->getDeviceFilterOptions();

        //Data is passed through API call data()
        return Inertia::render('Repairer/Device/FixedOverview', [
            'devices' => null,
            'deviceFilters' => $deviceFilters,
            'history' => true,
            'title' => trans('messages.repairer_fixed_overview_title'),
        ]);
    }
}
