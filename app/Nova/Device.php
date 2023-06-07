<?php

namespace App\Nova;

use App\Nova\Actions\ExportDevicesAction;
use App\Nova\Filters\DeviceEventFilter;
use App\Nova\Filters\DeviceStatusFilter;
use App\Nova\Filters\OrganisationType;
use App\Traits\LimitResultsByEmployees;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Titasgailius\SearchRelations\SearchesRelations;

class Device extends Resource
{
    use SearchesRelations;
    use LimitResultsByEmployees;

    public static $group = 'Management';

    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\Device::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'id';

    public function title()
    {
        return $this->brand_name . ' ' . $this->model_name;
    }

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id',
        'brand_name',
        'model_name',
        'first_name',
        'email',
    ];

    /**
     * The relationship columns that should be searched.
     *
     * @var array
     */
    public static $searchRelations = [
        'organisation' => ['name'],
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
            ID::make('ID', 'id')->sortable(),
            Text::make('Brand name', 'brand_name')->rules('required'),
            Text::make('Model name', 'model_name')->nullable(),
            Text::make('Latest status', 'latest_status')->readonly(),
            BelongsTo::make('Device type', 'deviceType', DeviceType::class)->withoutTrashed(),
            Text::make('Issue description', 'issue_description')->rules('required')->onlyOnDetail(),
            BelongsTo::make('Organisation', 'organisation', Organisation::class)->rules('required')->searchable(true)
                     ->withoutTrashed(),
            Text::make('First name', 'first_name')->rules('required'),
            Text::make('Last name', 'last_name')->rules('required'),
            Text::make('Email', 'email')->rules([
                'email',
                'required',
            ]),
            Text::make('Telephone', 'telephone')->nullable(),
            HasMany::make('Repair logs', 'repairLogs', RepairLog::class)->hideWhenUpdating()->hideWhenCreating(),
            BelongsTo::make('Event', 'event', Event::class)->nullable(),
            BelongsTo::make('Completed status', 'completedRepairStatus', CompletedRepairStatus::class)->readonly()->hideFromIndex(),
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
            new OrganisationType(),
            new DeviceStatusFilter(),
            new DeviceEventFilter(),
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
            new ExportDevicesAction(),
        ];
    }
}
