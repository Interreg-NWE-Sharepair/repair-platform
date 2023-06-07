<?php

namespace App\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\BelongsToMany;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;
use Titasgailius\SearchRelations\SearchesRelations;

class RepairLog extends Resource
{
    use SearchesRelations;

    public static $group = 'Management';

    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = 'App\Models\RepairLog';

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'id';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id',
        'status'
    ];

    public static $searchRelations = [
        'organisation' => ['name'],
        'device' => ['brand_name', 'model_name']
    ];

    /**
     * Get the fields displayed by the resource.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function fields(Request $request)
    {
        return [
            BelongsTo::make('Device', 'device', Device::class),
            Select::make('Status', 'status')->options(array_combine(\App\Models\RepairLog::STATUSES, \App\Models\RepairLog::STATUSES))->rules('required')->sortable(),
            Text::make('Device completed status', 'device_completed_status')->readonly()->hideFromIndex(),
            HasMany::make('RepairLogLink', 'repairLinks', RepairLogLink::class),
            HasMany::make('RepairLogNote', 'repairNotes', RepairLogNote::class),
            BelongsToMany::make('RepairBarrier', 'repairBarriers', RepairBarrier::class),
            BelongsTo::make('Organisation', 'organisation', Organisation::class)
        ];
    }

    /**
     * Get the cards available for the request.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function cards(Request $request)
    {
        return [];
    }

    /**
     * Get the filters available for the resource.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function filters(Request $request)
    {
        return [];
    }

    /**
     * Get the lenses available for the resource.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function lenses(Request $request)
    {
        return [];
    }

    /**
     * Get the actions available for the resource.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function actions(Request $request)
    {
        return [];
    }
}
