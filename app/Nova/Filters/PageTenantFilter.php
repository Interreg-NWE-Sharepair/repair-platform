<?php

namespace App\Nova\Filters;

use App\Models\Tenant;
use Illuminate\Http\Request;
use Laravel\Nova\Filters\Filter;

class PageTenantFilter extends Filter
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
        if ($value) {
            return $query->where('tenant_id', $value);
        }

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
        $options = [];
        $tenants = Tenant::query()->get();
        foreach ($tenants as $tenant) {
            $options[$tenant->name] = $tenant->id;
        }

        return $options;
    }

    public function name()
    {
        return 'Filter by Domain';
    }
}
