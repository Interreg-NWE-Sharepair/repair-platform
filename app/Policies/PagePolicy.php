<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class PagePolicy extends AbstractPolicy
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
        if ($this->isGuidanceTool($user)) {
            return true;
        }

        return $this->checkPermissions($user, [
            'nova-view',
            'nova-guidance',
        ]);
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param \App\Models\User $user
     * @return mixed
     */
    public function view(User $user)
    {
        if ($this->isGuidanceTool($user)) {
            return true;
        }

        return $this->checkPermissions($user, [
            'nova-view',
            'nova-guidance',
        ]);
    }

    /**
     * Determine whether the user can create models.
     *
     * @param \App\Models\User $user
     * @return mixed
     */
    public function create(User $user)
    {
        //Only logged in with Google Statik can create pages. (role is assigned to user itself)
        return $this->isStatik($user);
        //return $this->checkPermissions($user, ['nova-admin']);
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param \App\Models\User $user
     * @return mixed
     */
    public function update(User $user)
    {
        if ($this->isGuidanceTool($user)) {
            return true;
        }

        return $this->checkPermissions($user, [
            'nova-view',
            'nova-guidance',
        ]);
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param \App\Models\User $user
     * @return mixed
     */
    public function delete(User $user)
    {
        return $this->isStatik($user);
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param \App\Models\User $user
     * @return mixed
     */
    public function restore(User $user)
    {
        return $this->checkPermissions($user, ['nova-admin']);
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param \App\Models\User $user
     * @return mixed
     */
    public function forceDelete(User $user)
    {
        return $this->isStatik($user);
    }
}
