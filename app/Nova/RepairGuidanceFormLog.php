<?php

namespace App\Nova;

use App\Nova\Actions\ExportGuidanceFormLogAction;
use App\Nova\Filters\Repgui\DeviceTypeFilter;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\BelongsToMany;
use Laravel\Nova\Fields\Text;
use Titasgailius\SearchRelations\SearchesRelations;

class RepairGuidanceFormLog extends Resource
{

    use SearchesRelations;

    public static $group = 'General';

    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\RepairGuidanceFormLog::class;

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
    ];

    /**
     * The relationship columns that should be searched.
     *
     * @var array
     */
    public static $searchRelations = [
        'commonDeviceTypeIssues' => ['issue'],
        'deviceType' => ['name']
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
            BelongsTo::make('Device type', 'deviceType')->displayUsing(function ($value) {
                return $value->name;
            }),
            Text::make('Brand name', 'brand_name'),
            Text::make('Model name', 'model_name'),
            Text::make('Product description', 'product_description')->hideFromIndex(),
            Text::make('Product age', 'product_age')->hideFromIndex(),
            Text::make('Common issue text', 'common_issue_text')->hideFromIndex(),
            BelongsToMany::make('Common device type issues', 'commonDeviceTypeIssues'),
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
        return [
            new DeviceTypeFilter()
        ];
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
        return [
            new ExportGuidanceFormLogAction()
        ];
    }
}
