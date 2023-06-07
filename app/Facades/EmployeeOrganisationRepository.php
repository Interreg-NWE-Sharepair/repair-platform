<?php

namespace App\Facades;

use App\Models\Organisation;
use App\Models\Person;
use App\Models\User;
use App\Repository\EmployeeOrganisationRepositoryInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Facade;

/**
 * @method static Collection getOrganisations(Person $person)
 * @method static Builder getRepairersByOrganisation(Organisation $organisation)
 * @method static bool isRepairerOrganisation(User $user, Organisation $organisation)
 * @method static bool canViewOrganisation(User $user, Organisation $organisation)
 * @method static bool isEventOrganizerOrganisation(User $user, Organisation $organisation)
 * @method static bool isEntityAdminOrganisation(User $user, Organisation $organisation)
 * @method static bool isEventOrganizer(Person $person)
 * @method static bool isEntityAdmin(Person $person)
 * @method static bool isAdmin(Person $person)
 * @method static bool isNovaAdmin(Person $person)
 * @method static bool isRepairer(Person $person)
 * @method static Collection getRolesPerOrganisation(Person $person)
 *
 *
 * @see EmployeeOrganisationRepositoryInterface
 */
class EmployeeOrganisationRepository extends Facade
{
    protected static function getFacadeAccessor()
    {
        return EmployeeOrganisationRepositoryInterface::class;
    }
}
