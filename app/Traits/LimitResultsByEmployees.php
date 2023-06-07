<?php

namespace App\Traits;

use App\Models\Organisation;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Laravel\Nova\Http\Requests\NovaRequest;

trait LimitResultsByEmployees
{
    public static function indexQuery(NovaRequest $request, $query)
    {
        $user = $request->user();

        return self::hasSameOrganisation($query, $user, [
            'entity-admin',
            'event-organizer',
        ]);
    }

    private static function hasSameOrganisation(Builder $query, User $user, $roles)
    {
        if ($user->can('nova-admin') || $user->hasRole('statik')) {
            return $query;
        }

        $employees = $user->employees;
        $organisationIds = [];
        $isAdmin = false;
        foreach ($employees as $employee) {
            if ($employee->hasRole('admin')) {
                $isAdmin = true;
            } else {
                if ($employee->hasRole($roles)) {
                    $organisationIds[] = $employee->organisation->id;
                }
            }
        }

        $query = self::filterQueryByOrganisations($query, $organisationIds);

        return $query;
    }

    public static function filterQueryByOrganisations(Builder $query, array $organisationIds)
    {
        $modelClass = self::$model;

        if ($modelClass === Organisation::class) {
            return $query->whereIn('id', $organisationIds);
        }

        if (method_exists($modelClass, 'organisations')) {
            return $query->whereHas('organisations', function ($q) use ($organisationIds) {
                $q->whereIn('organisations.id', $organisationIds);
            });
        }

        if (method_exists($modelClass, 'organisation')) {
            return $query->whereHas('organisation', function ($q) use ($organisationIds) {
                $q->whereIn('organisations.id', $organisationIds);
            });
        }

        if (method_exists($modelClass, 'locations')) {
            return $query->whereHas('locations', function ($q) use ($organisationIds) {
                $q->whereIn('organisation_id', $organisationIds);
            });
        }

        if (method_exists($modelClass, 'location')) {
            return $query->whereHas('location', function ($q) use ($organisationIds) {
                $q->whereIn('organisation_id', $organisationIds);
            });
        }

        return $query;
    }
}
