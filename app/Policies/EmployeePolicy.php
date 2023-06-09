<?php

namespace App\Policies;

use App\Models\Employee;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class EmployeePolicy extends AbstractPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param \App\Models\User $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        return $this->checkPermissions($user, [
            'nova-view-entity',
            'nova-view',
            'nova-events',
        ]);
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param \App\Models\User $user
     * @param \App\Models\Employee $employee
     * @return mixed
     */
    public function view(User $user, Employee $employee)
    {
        return $this->checkPermissions($user, [
                'nova-view',
                'nova-view-entity',
            ]) || $this->isEmployeeOfModel($user, $employee, ['entity-admin']);
    }

    /**
     * Determine whether the user can create models.
     *
     * @param \App\Models\User $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $this->checkPermissions($user, ['nova-view']);
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param \App\Models\User $user
     * @return mixed
     */
    public function update(User $user, Employee $employee)
    {
        return $this->checkPermissions($user, ['nova-view']) || $this->isEmployeeOfModel($user, $employee, ['entity-admin']);
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param \App\Models\User $user
     * @return mixed
     */
    public function delete(User $user)
    {
        return $this->checkPermissions($user, ['nova-view']);
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param \App\Models\User $user
     * @return mixed
     */
    public function restore(User $user)
    {
        return $this->checkPermissions($user, ['nova-view']);
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param \App\Models\User $user
     * @return mixed
     */
    public function forceDelete(User $user)
    {
        return $this->checkPermissions($user, ['nova-admin']);
    }
}
