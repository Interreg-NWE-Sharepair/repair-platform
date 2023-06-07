<?php

namespace App\Nova;

use Eminiarts\Tabs\Tabs;
use Eminiarts\Tabs\TabsOnEdit;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\DeleteResourceRequest;
use Laravel\Nova\Http\Requests\ResourceDetailRequest;
use Spatie\NovaTranslatable\Translatable;

class ActivitySector extends Resource
{
    use TabsOnEdit;

    public static $group = 'Options';

    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\ActivitySector::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'name';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id',
        'name',
    ];

    /**
     * Get the URI key for the resource.
     *
     * @return string
     */
    public static function uriKey()
    {
        return 'activity-sector';
    }

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
                    Text::make('Name', 'name')->onlyOnForms(),
                ])->locales([$locale]),
            ];
        }
        $tabs['Non translatable fields'] = [
            Boolean::make('Visible?', 'is_visible'),
        ];

        return [
            Translatable::make([
                Text::make('Naam', 'name')->exceptOnForms(),
            ]),
            (new Tabs('Tabs', $tabs)),
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

    public function authorizedToDelete(Request $request)
    {
        return $request instanceof ResourceDetailRequest || $request instanceof DeleteResourceRequest;
    }
}
