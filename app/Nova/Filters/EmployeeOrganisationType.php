<?php

namespace App\Nova\Filters;

use App\Facades\EmployeeRepository;
use App\Facades\OrganisationRepository;
use App\Models\Role;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Laravel\Nova\Filters\Filter;

class EmployeeOrganisationType extends Filter
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
        return $query->whereHas('employees', function (Builder $q) use ($value) {
            $q->whereHas('organisation', function (Builder $q) use ($value) {
                $q->where('id', $value);
            });
        });

        return $query;
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
