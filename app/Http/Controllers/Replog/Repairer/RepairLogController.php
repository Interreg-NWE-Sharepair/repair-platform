<?php

namespace App\Http\Controllers\Replog\Repairer;

use App\Http\Controllers\Replog\ReplogController;
use Inertia\Inertia;

class RepairLogController extends ReplogController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $deviceFilters = $this->getDeviceFilterOptions();
        unset($deviceFilters['status'], $deviceFilters['order']);

        return Inertia::render('Repairer/History/Overview', [
            'deviceFilters' => $deviceFilters,
            'history' => true,
            'title' => trans('messages.route_repair_history_overview'),
        ]);
    }
}
