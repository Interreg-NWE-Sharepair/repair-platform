<?php

namespace App\Nova;

use App\Nova\Filters\Repgui\DeviceTypeFilter;
use Eminiarts\Tabs\Tabs;
use Eminiarts\Tabs\TabsOnEdit;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\Text;
use MichielKempen\NovaOrderField\Orderable;
use Spatie\NovaTranslatable\Translatable;
use Titasgailius\SearchRelations\SearchesRelations;

class CommonDeviceTypeIssue extends Resource
{
    //public static $displayInNavigation = false;

    use TabsOnEdit;
    use Orderable;
    use SearchesRelations;

    public static $group = 'Options';

    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\CommonDeviceTypeIssue::class;

    /**
     * Get the value that should be displayed to represent the resource.
     *
     * @return string
     */
    public function title()
    {
        return $this->issue. ' ('. $this->deviceType->name.')';
    }

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'issue',
    ];

    /**
     * The relationship columns that should be searched.
     *
     * @var array
     */
    public static $searchRelations = [
        'deviceType' => [
            'name',
            'uuid',
            'code',
        ],
    ];

    /**
     * Get the fields displayed by the resource.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function fields(Request $request)
    {
        $tabs = [];
        foreach (config('translatable.default_locales') as $locale => $localeName) {
            $tabs[ucfirst($localeName)] = [
                Translatable::make([
                    Text::make('Issue', 'issue')->onlyOnForms(),
                    Text::make('description')->onlyOnForms(),
                ])->locales([$locale]),
            ];
        }
        $tabs['Non translatable fields'] = [
            BelongsTo::make('Device Type', 'deviceType', DeviceType::class)->required(),
        ];

        return [
            Translatable::make([
                Text::make('Issue', 'issue')->exceptOnForms(),
            ]),
            (new Tabs('Tabs', $tabs)),
            HasMany::make('Tutorials', 'repairTutorials', RepairTutorial::class)->nullable(),
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
            new DeviceTypeFilter(),
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
        return [];
    }
}
