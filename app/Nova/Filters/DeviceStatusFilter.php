<?php

namespace App\Nova\Filters;

use App\Models\RepairLog;
use Illuminate\Http\Request;
use Laravel\Nova\Filters\Filter;

class DeviceStatusFilter extends Filter
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
        $query = $this->filterDevices($query, $value);

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
        return [
            trans('messages.status_open') => RepairLog::STATUS_OPEN,
            trans('messages.status_reopened') => RepairLog::STATUS_REOPENED,
            trans('messages.status_in_repair') => RepairLog::STATUS_IN_REPAIR,
            trans('messages.status_completed') => RepairLog::STATUS_COMPLETED,
        ];
    }

    private function filterDevices($query, $value)
    {
        return $query->where('latest_status', $value);
    }
}
