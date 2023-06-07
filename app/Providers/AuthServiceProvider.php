<?php

namespace App\Providers;

use App\Models\ActivitySector;
use App\Models\CommonDeviceTypeIssue;
use App\Models\CompletedRepairStatus;
use App\Models\Contact;
use App\Models\Device;
use App\Models\DeviceType;
use App\Models\Employee;
use App\Models\Event;
use App\Models\Location;
use App\Models\Organisation;
use App\Models\OrganisationRequest;
use App\Models\OrganisationType;
use App\Models\Page;
use App\Models\RepairBarrier;
use App\Models\RepairGuidanceFormLog;
use App\Models\RepairLog;
use App\Models\RepairTutorial;
use App\Models\User;
use App\Policies\ActivitySectorPolicy;
use App\Policies\CommonDeviceTypeIssuePolicy;
use App\Policies\CompletedRepairStatusPolicy;
use App\Policies\ContactPolicy;
use App\Policies\DevicePolicy;
use App\Policies\DeviceTypePolicy;
use App\Policies\EmployeePolicy;
use App\Policies\EventPolicy;
use App\Policies\LocationPolicy;
use App\Policies\OrganisationPolicy;
use App\Policies\OrganisationRequestPolicy;
use App\Policies\OrganisationTypePolicy;
use App\Policies\PagePolicy;
use App\Policies\RepairBarrierPolicy;
use App\Policies\RepairGuidanceFormLogPolicy;
use App\Policies\RepairLogPolicy;
use App\Policies\RepairTutorialPolicy;
use App\Policies\UserPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        Contact::class => ContactPolicy::class,
        OrganisationRequest::class => OrganisationRequestPolicy::class,
        Device::class => DevicePolicy::class,
        DeviceType::class => DeviceTypePolicy::class,
        Location::class => LocationPolicy::class,
        Organisation::class => OrganisationPolicy::class,
        Page::class => PagePolicy::class,
        RepairBarrier::class => RepairBarrierPolicy::class,
        RepairLog::class => RepairLogPolicy::class,
        CompletedRepairStatus::class => CompletedRepairStatusPolicy::class,
        User::class => UserPolicy::class,
        Event::class => EventPolicy::class,
        OrganisationType::class => OrganisationTypePolicy::class,
        Employee::class => EmployeePolicy::class,
        ActivitySector::class => ActivitySectorPolicy::class,
        CommonDeviceTypeIssue::class => CommonDeviceTypeIssuePolicy::class,
        RepairTutorial::class => RepairTutorialPolicy::class,
        RepairGuidanceFormLog::class,
        RepairGuidanceFormLogPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();
        //
    }
}
