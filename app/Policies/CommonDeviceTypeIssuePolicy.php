<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CommonDeviceTypeIssuePolicy extends AbstractPolicy
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

        return $this->checkPermissions($user, ['nova-guidance']);
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

        return $this->checkPermissions($user, ['nova-guidance']);
    }

    /**
     * Determine whether the user can create models.
     *
     * @param \App\Models\User $user
     * @return mixed
     */
    public function create(User $user)
    {
        if ($this->isGuidanceTool($user)) {
            return true;
        }

        return $this->checkPermissions($user, ['nova-guidance']);
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

        return $this->checkPermissions($user, ['nova-guidance']);
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param \App\Models\User $user
     * @return mixed
     */
    public function delete(User $user)
    {
        if ($this->isGuidanceTool($user)) {
            return true;
        }

        return $this->checkPermissions($user, ['nova-guidance']);
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param \App\Models\User $user
     * @return mixed
     */
    public function restore(User $user)
    {
        if ($this->isGuidanceTool($user)) {
            return true;
        }

        return $this->checkPermissions($user, ['nova-guidance']);
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
