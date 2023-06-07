<?php

namespace App\Policies;

use App\Models\Location;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class LocationPolicy extends AbstractPolicy
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
            'nova-view',
            'nova-view-entity',
        ]);
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param \App\Models\User $user
     * @return mixed
     */
    public function view(User $user, Location $location)
    {
        return $this->checkPermissions($user, ['nova-view-entity']) && $this->isEmployeeOfModel($user, $location, ['entity-admin']);
        //if ($user->can('nova-view-entity') && $user->location->id === $location->id) {
        //    return true;
        //}

        //return $this->checkPermissions($user,['nova-view']);
    }

    /**
     * Determine whether the user can create models.
     *
     * @param \App\Models\User $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $this->checkPermissions($user, ['nova-view-entity']) || $this->checkRoles($user, ['entity-admin']);
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param \App\Models\User $user
     * @param \App\Models\Location $location
     * @return mixed
     */
    public function update(User $user, Location $location)
    {
        return $this->checkPermissions($user, ['nova-view-entity']) && $this->isEmployeeOfModel($user, $location, ['entity-admin']);
        //if ($user->can('nova-view-entity') && $user->location->id === $location->id) {
        //    return true;
        //}
        //
        //return $user->can('nova-view');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param \App\Models\User $user
     * @return mixed
     */
    public function delete(User $user)
    {
        return $this->checkPermissions($user, ['nova-admin']);
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
