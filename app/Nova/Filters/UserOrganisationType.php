<?php

namespace App\Nova\Filters;

use App\Facades\EmployeeRepository;
use App\Facades\OrganisationRepository;
use App\Models\Role;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Laravel\Nova\Filters\Filter;

class UserOrganisationType extends Filter
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

        $hasUserTypeFilter = false;
        foreach ($decodedFilters as $filter) {
            if ($filter['class'] === UserType::class) {
                if ($filter['value']) {
                    $hasUserTypeFilter = true;
                }
            }
        }

        /**
         * CHECK USERTYPE FILTER WHEN USERTYPE IS ALSO USED BECAUSE THESE 2 WON'T WORK TOGETHER... FUCKING NOVA
         */
        if ($hasUserTypeFilter) {
            return $query;
        }

        return $query->whereHas('person', function (Builder $q) use ($value) {
            $q->whereHas('employees', function (Builder $q) use ($value) {
                $q->whereHas('organisation', function ($q) use ($value) {
                    $q->where('id', $value);
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
        $user = $request->user();
        $employees = EmployeeRepository::getByUser($user)->get();

        $organisationsArray = [];

        if ($employees && !$user->hasRole([
                Role::STATIK,
                Role::ADMIN,
            ])) {
            foreach ($employees as $employee) {
                $organisation = $employee->organisation;
                $organisationsArray[$organisation->name] = $organisation->id;
            }
        } else {
            $organisations = OrganisationRepository::getAvailable(null, null, true)->get();
            foreach ($organisations as $organisation) {
                $organisationsArray[$organisation->name] = $organisation->id;
            }
        }

        ksort($organisationsArray);

        return $organisationsArray;
    }
}
