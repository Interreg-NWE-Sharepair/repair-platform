<?php

namespace App\Providers;

use App\Repository\Api\RestartersRepository;
use App\Repository\DeviceRepositoryInterface;
use App\Repository\Eloquent\BaseRepository;
use App\Repository\Eloquent\DeviceRepository;
use App\Repository\Eloquent\EmployeeOrganisationRepository;
use App\Repository\Eloquent\EmployeeRepository;
use App\Repository\Eloquent\EventRepository;
use App\Repository\Eloquent\LocationRepository;
use App\Repository\Eloquent\OrganisationRepository;
use App\Repository\Eloquent\PageRepository;
use App\Repository\Eloquent\PersonRepository;
use App\Repository\Eloquent\RepairBarrierRepositoryRepository;
use App\Repository\Eloquent\RepairLogRepository;
use App\Repository\Eloquent\RepairTutorialRepository;
use App\Repository\EloquentRepositoryInterface;
use App\Repository\EmployeeOrganisationRepositoryInterface;
use App\Repository\EmployeeRepositoryInterface;
use App\Repository\EventRepositoryInterface;
use App\Repository\LocationRepositoryInterface;
use App\Repository\OrganisationRepositoryInterface;
use App\Repository\PageRepositoryInterface;
use App\Repository\PersonRepositoryInterface;
use App\Repository\RepairBarrierRepositoryInterface;
use App\Repository\RepairLogRepositoryInterface;
use App\Repository\RestartersRepositoryInterface;
use App\Repository\UserLocationRepositoryInterface;
use App\Repository\UserRepositoryInterface;
use App\Repository\RepairTutorialRepositoryInterface;
use Illuminate\Support\ServiceProvider;

/**
 * Class RepositoryServiceProvider
 */
class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(EloquentRepositoryInterface::class, BaseRepository::class);
        $this->app->bind(EmployeeOrganisationRepositoryInterface::class, EmployeeOrganisationRepository::class);
        $this->app->bind(LocationRepositoryInterface::class, LocationRepository::class);
        $this->app->bind(EmployeeRepositoryInterface::class, EmployeeRepository::class);
        $this->app->bind(RepairLogRepositoryInterface::class, RepairLogRepository::class);
        $this->app->bind(EventRepositoryInterface::class, EventRepository::class);
        $this->app->bind(PageRepositoryInterface::class, PageRepository::class);
        $this->app->bind(DeviceRepositoryInterface::class, DeviceRepository::class);
        $this->app->bind(PersonRepositoryInterface::class, PersonRepository::class);
        $this->app->bind(OrganisationRepositoryInterface::class, OrganisationRepository::class);
        $this->app->bind(RepairBarrierRepositoryInterface::class, RepairBarrierRepositoryRepository::class);
        $this->app->bind(RepairTutorialRepositoryInterface::class, RepairTutorialRepository::class);
        $this->app->bind(RestartersRepositoryInterface::class, RestartersRepository::class);
    }
}
