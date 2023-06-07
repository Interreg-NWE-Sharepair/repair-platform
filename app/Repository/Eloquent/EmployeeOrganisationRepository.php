<?php

namespace App\Repository\Eloquent;

use App\Models\Employee;
use App\Models\Organisation;
use App\Models\Person;
use App\Models\Role;
use App\Models\User;
use App\Repository\EmployeeOrganisationRepositoryInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class EmployeeOrganisationRepository extends BaseRepository implements EmployeeOrganisationRepositoryInterface
{
    /**
     * EmployeeOrganisationRepository constructor.
     *
     * @param Employee $model
     */
    public function __construct(Employee $model)
    {
        parent::__construct($model);
    }

    public function getOrganisations(Person $person): Collection
    {
        $organisations = [];

        $employeeOrganisations = $this->model::query()->whereHas('person', function (Builder $q) use ($person) {
            $q->where('id', $person->id);
        })->whereHas('organisation', function (Builder $q) {
            $q->where('is_rc_active', 1);
        })->get();

        if ($employeeOrganisations) {
            foreach ($employeeOrganisations as $employeeOrganisation) {
                $organisations[] = $employeeOrganisation->organisation;
            }
        }

        return collect($organisations);
    }

    /**
     * @param \App\Models\Organisation $organisation
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function getRepairersByOrganisation(Organisation $organisation): Builder
    {
        return $this->model->role([Role::REPAIRER])->with([
            'person.user' => function ($q) {
                $q->where('email', 'NOT LIKE', '%@statik.be%');
                $q->whereNotNull('email_verified_at');
            },
        ])->whereHas('person')->whereHas('organisation', function ($q) use ($organisation) {
            $q->where('id', $organisation->id);
        });
    }

    public function canViewOrganisation(User $user, Organisation $organisation): bool
    {
        return $this->model->role([
            Role::REPAIRER,
            Role::EVENT_ORGANIZER,
            Role::ENTITY_ADMIN,
        ])->user($user)->whereHas('organisation', function ($q) use ($organisation) {
            $q->where('id', $organisation->id);
        })->exists();
    }

    public function isRepairerOrganisation(User $user, Organisation $organisation): bool
    {
        return $this->model->role([Role::REPAIRER])->user($user)->whereHas('organisation', function ($q) use (
            $organisation
        ) {
            $q->where('id', $organisation->id);
        })->exists();
    }

    public function isEventOrganizerOrganisation(User $user, Organisation $organisation): bool
    {
        return $this->model->role([Role::EVENT_ORGANIZER])->user($user)->organisation($organisation)->exists();
    }

    public function isEntityAdminOrganisation(User $user, Organisation $organisation): bool
    {
        return $this->model->role([Role::ENTITY_ADMIN])->user($user)->whereHas('organisation', function ($q) use (
            $organisation
        ) {
            $q->where('id', $organisation->id);
        })->exists();
    }

    public function isEventOrganizer(Person $person): bool
    {
        return $this->model::query()->whereHas('person', function ($q) use ($person) {
            $q->where('people.id', $person->id);
        })->role([Role::EVENT_ORGANIZER])->exists();
    }

    public function isEntityAdmin(Person $person): bool
    {
        return $this->model::query()->whereHas('person', function ($q) use ($person) {
            $q->where('people.id', $person->id);
        })->role([Role::ENTITY_ADMIN])->exists();
    }

    public function isAdmin(Person $person): bool
    {
        return $this->model::query()->whereHas('person', function ($q) use ($person) {
            $q->where('people.id', $person->id);
        })->role([
            Role::ADMIN,
            Role::STATIK,
        ])->exists();
    }

    public function isNovaAdmin(Person $person): bool
    {
        return $this->model::query()->whereHas('person', function ($q) use ($person) {
            $q->where('people.id', $person->id);
        })->role([
            Role::ADMIN,
            Role::STATIK,
            Role::ENTITY_ADMIN,
            Role::EVENT_ORGANIZER,
        ])->exists();
    }

    public function isRepairer(Person $person): bool
    {
        return $this->model::query()->with('organisation.media')->whereHas('person', function ($q) use ($person) {
            $q->where('people.id', $person->id);
        })->where('employee_type', Employee::TYPE_REPAIRER)->role(['repairer'])->exists();
    }

    public function getRolesPerOrganisation(Person $person): Collection
    {
        $organisations = [];
        $employeeOrganisations = $this->model::query()->with('roles')->whereHas('person', function (Builder $q) use (
            $person
        ) {
            $q->where('id', $person->id);
        })->whereHas('organisation', function (Builder $q) {
            $q->where('is_rc_active', 1);
        })->get();

        foreach ($employeeOrganisations as $employeeOrganisation) {
            /** @var Employee $employeeOrganisation */
            $roleNames = $employeeOrganisation->getRoleNames()->toArray();
            foreach ($roleNames as $index => $roleName) {
                $roleNames[$index] = trans("messages.role_$roleName");
            }
            $organisations[$employeeOrganisation->organisation->uuid] = implode(', ', $roleNames);
        }

        return collect($organisations);
    }
}
