<?php

namespace App\Nova\Filters;

use App\Models\OrganisationType;
use Illuminate\Http\Request;
use Laravel\Nova\Filters\Filter;

class   OrganisationsForLocationsFilter extends Filter
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
        return $query->whereHas('organisation', function ($q) use ($value) {
            $q->whereHas('OrganisationType', function ($q) use ($value) {
                $q->where('id', $value);
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
        $organisationTypes = OrganisationType::all();
        $types = [];
        foreach ($organisationTypes as $organisationType) {
            $types[$organisationType->name] = $organisationType->id;
        }

        return $types;
    }

    public function name()
    {
        return 'Repair organisation types';
    }
}
