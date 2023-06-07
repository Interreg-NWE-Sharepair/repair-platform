<?php

namespace App\Http\Controllers\Replog\Repairer\Device;

use App\Facades\EmployeeOrganisationRepository;
use App\Facades\PersonRepository;
use App\Facades\RepairLogRepository;
use App\Http\Controllers\Replog\ReplogController;
use App\Models\RepairLog;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class DashboardController extends ReplogController
{
    /**
     * Shows the locations you're a repairer at + all the devices you're working on + available for that location
     *
     * @return \Inertia\Response
     * @throws \Illuminate\Auth\AuthenticationException
     */
    public function index()
    {
        $user = Auth::user();
        /** @var \App\Models\Person $person */
        $person = PersonRepository::getByUser($user)->firstOrFail();
        $isRepairer = EmployeeOrganisationRepository::isRepairer($person);
        if (!$isRepairer) {
            throw new AuthenticationException();
        }

        $hasDevices = RepairLogRepository::hasDevicesAssigned($person, [RepairLog::STATUS_IN_REPAIR])->count();

        $organisations = EmployeeOrganisationRepository::getOrganisations($person);

        $statuses = $this->getStatusOptions();

        return Inertia::render('Repairer/Device/Dashboard', [
            'hasDevices' => (bool) $hasDevices,
            'statuses' => $statuses,
            'organisations' => $organisations->isNotEmpty() ? $organisations : null,
            'title' => trans('messages.my_devices'),
        ]);
    }
}
