<?php

namespace App\Policies;

use App\Models\Organisation;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class AbstractPolicy
{
    const ROLE_STATIK = 'statik';

    const PERM_NOVA_ADMIN = 'nova-admin';

    public function checkPermissions(User $user, array $permissions)
    {
        if ($this->isSuperAdmin($user)) {
            return true;
        }

        $hasPermission = false;
        $employees = $user->employees;
        foreach ($employees as $employee) {
            $hasPermission = $employee->hasAnyPermission($permissions);

            if ($hasPermission) {
                break;
            }
        }

        return $hasPermission;
    }

    public function checkRoles(User $user, array $roles)
    {
        if ($this->isSuperAdmin($user)) {
            return true;
        }

        if ((app('currentTenant')->code === 'repgui') && $this->isGuidanceTool($user)) {
            return true;
        }

        $hasRole = false;
        $employees = $user->employees;
        foreach ($employees as $employee) {
            $hasRole = $employee->hasRole($roles);
            if ($hasRole) {
                break;
            }
        }

        return $hasRole;
    }

    public function isEmployeeOfModel(User $user, Model $model, $roles)
    {
        if ($this->isSuperAdmin($user)) {
            return true;
        }

        $organisationIds = $this->getOrganisationIdsByModel($model);

        if ($organisationIds) {
            $employees = $user->employees;

            foreach ($employees as $employee) {
                if ($employee->hasRole($roles) && in_array($employee->organisation->id, $organisationIds, true)) {
                    return true;
                }
            }
        }

        return false;
    }

    private function isSuperAdmin(User $user): bool
    {
        return $user->hasRole([self::ROLE_STATIK]) || $user->can([self::PERM_NOVA_ADMIN]);
    }

    public function isStatik(User $user): bool
    {
        return $user->hasRole([self::ROLE_STATIK]);
    }

    //Checks if this particular person is only a Guidance tool user
    public function isGuidanceTool(User $user): bool
    {
        return app('currentTenant')->code === 'repgui' && $user->hasPermissionTo('nova-guidance');
    }

    private function getOrganisationIdsByModel(Model $model)
    {
        $organisationIds = [];

        //Duplicates dont matter
        if (method_exists($model, 'organisation')) {
            $organisationIds[] = optional($model->organisation)->id;
        }

        if (method_exists($model, 'organisations')) {
            foreach ($model->organisations as $organisation) {
                $organisationIds[] = $organisation->id;
            }
        }

        if (method_exists($model, 'location')) {
            if ($model->location) {
                $organisationIds[] = optional($model->location->organisation)->id;
            }
        }

        if (method_exists($model, 'locations')) {
            foreach ($model->locations as $location) {
                $organisationIds[] = $location->organisation->id;
            }
        }

        if ($model instanceof Organisation) {
            $organisationIds[] = $model->id;
        }

        return $organisationIds;
    }
}
