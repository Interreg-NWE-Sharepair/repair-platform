<?php

namespace App\Repository;

use App\Models\Organisation;
use App\Models\Person;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

/** @see \App\Repository\Eloquent\EmployeeOrganisationRepository */
interface EmployeeOrganisationRepositoryInterface
{
    public function getRepairersByOrganisation(Organisation $organisation): Builder;

    public function getOrganisations(Person $person): Collection;

    public function isRepairerOrganisation(User $user, Organisation $organisation): bool;

    public function canViewOrganisation(User $user, Organisation $organisation): bool;

    public function isEventOrganizerOrganisation(User $user, Organisation $organisation): bool;

    public function isEntityAdminOrganisation(User $user, Organisation $organisation): bool;

    public function isEventOrganizer(Person $person): bool;

    public function isEntityAdmin(Person $person): bool;

    public function isAdmin(Person $person): bool;

    public function isNovaAdmin(Person $person): bool;

    public function isRepairer(Person $person): bool;

    public function getRolesPerOrganisation(Person $person): Collection;
}
