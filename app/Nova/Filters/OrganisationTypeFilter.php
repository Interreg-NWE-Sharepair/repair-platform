<?php

namespace App\Nova\Filters;

use App\Models\OrganisationType;
use Illuminate\Http\Request;
use Laravel\Nova\Filters\Filter;

class OrganisationTypeFilter extends Filter
{
    /**
     * The filter's component.
     *
     * @var string
     */
    public $component = 'select-filter';

    public $name = "Organisation type";

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
        return $query->whereHas('OrganisationType', function ($q) use ($value) {
            $q->where('id', $value);
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

        ksort($types);

        return $types;
    }
}
