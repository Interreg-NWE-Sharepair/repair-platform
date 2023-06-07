<?php

namespace App\Policies;

use App\Models\Event;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class EventPolicy extends AbstractPolicy
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
     * @param \App\Models\Event $event
     * @return mixed
     */
    public function view(User $user, Event $event)
    {
        return $this->checkPermissions($user, ['nova-view']) || $this->checkRoles($user, [
                'entity-admin',
                'event-organizer',
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
        return $this->checkPermissions($user, ['nova-view']) || $this->checkRoles($user, [
                'entity-admin',
                'event-organizer',
            ]);
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param \App\Models\User $user
     * @return mixed
     */
    public function update(User $user, Event $event)
    {
        return $this->checkPermissions($user, [
                'nova-view',
                'nova-view-entity',
            ]) || $this->checkRoles($user, [
                'entity-admin',
                'event-organizer',
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
        return $this->checkPermissions($user, [
                'nova-view',
                'nova-view-entity',
            ]) || $this->checkRoles($user, [
                'entity-admin',
                'event-organizer',
            ]);
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
