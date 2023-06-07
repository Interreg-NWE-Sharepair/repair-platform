<?php

namespace App\Policies;

use App\Models\LocationSuggestion;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class LocationSuggestionPolicy extends AbstractPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param \App\Models\User $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny(User $user)
    {
        return $this->checkPermissions($user, ['nova-view']);
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param \App\Models\User $user
     * @param \App\Models\LocationSuggestion $locationSuggestion
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, LocationSuggestion $locationSuggestion)
    {
        return $this->checkPermissions($user, ['nova-view']);
    }

    /**
     * Determine whether the user can create models.
     *
     * @param \App\Models\User $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        return false;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param \App\Models\User $user
     * @param \App\Models\LocationSuggestion $locationSuggestion
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, LocationSuggestion $locationSuggestion)
    {
        return $this->checkPermissions($user, ['nova-admin']);
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param \App\Models\User $user
     * @param \App\Models\LocationSuggestion $locationSuggestion
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, LocationSuggestion $locationSuggestion)
    {
        return $this->checkPermissions($user, ['nova-admin']);
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param \App\Models\User $user
     * @param \App\Models\LocationSuggestion $locationSuggestion
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, LocationSuggestion $locationSuggestion)
    {
        return $this->checkPermissions($user, ['nova-admin']);
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param \App\Models\User $user
     * @param \App\Models\LocationSuggestion $locationSuggestion
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, LocationSuggestion $locationSuggestion)
    {
        return $this->checkPermissions($user, ['nova-admin']);
    }

    public function addDeviceType()
    {
        return false;
    }

    public function addActivitySector()
    {
        return false;
    }
}
