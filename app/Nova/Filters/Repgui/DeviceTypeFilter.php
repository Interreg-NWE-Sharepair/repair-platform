<?php

namespace App\Nova\Filters\Repgui;

use App\Models\DeviceType;
use Illuminate\Http\Request;
use Laravel\Nova\Filters\Filter;

class DeviceTypeFilter extends Filter
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
            return $query->whereHas('deviceType', function ($query) use ($value) {
                $query->where('id', $value);
            });
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
        $deviceTypes = DeviceType::query()->showOnGuidance()->get();
        $types = [];
        foreach ($deviceTypes as $deviceType) {
            $types[$deviceType->name] = $deviceType->id;
        }

        return $types;
    }
}
