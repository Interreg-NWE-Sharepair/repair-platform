<?php

namespace App\Nova\Filters;

use App\Models\Role;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Laravel\Nova\Filters\Filter;

class UserType extends Filter
{
    /**
     * The filter's component.
     *
     * @var string
     */
    public $component = 'select-filter';

    /**
     * Apply the filter to the given query.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param mixed $value
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function apply(Request $request, $query, $value)
    {
        $queryFilters = $request->query('filters');
        $decodedFilters = decodeBase64Filter($queryFilters);

        if (!$decodedFilters) {
            return $query;
        }

        $organisationFilter = null;
        foreach ($decodedFilters as $filter) {
            if ($filter['class'] === UserOrganisationType::class) {
                $organisationFilter = $filter['value'];
            }
        }

        return $query->whereHas('person', function (Builder $q) use ($value, $organisationFilter) {
            $q->whereHas('employees', function (Builder $q) use ($value, $organisationFilter) {
                if ($organisationFilter) {
                    $q->where('organisation_id', $organisationFilter);
                }
                $q->whereHas('roles', function (Builder $q) use ($value) {
                    $q->where('role_id', $value);
                });
            });
        });
    }

    /**
     * Get the filter's available options.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function options(Request $request)
    {
        $roles = Role::all();
        $roleArray = [];
        foreach ($roles as $role) {
            if ($role->name === Role::STATIK) {
                continue;
            }
            $roleArray[$role->name] = $role->id;
        }

        return $roleArray;
    }
}
