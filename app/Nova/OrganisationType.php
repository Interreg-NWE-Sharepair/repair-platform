<?php

namespace App\Nova;

use Eminiarts\Tabs\Tabs;
use Eminiarts\Tabs\TabsOnEdit;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Spatie\NovaTranslatable\Translatable;

class OrganisationType extends Resource
{
    use TabsOnEdit;

    public static $group = 'Options';

    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\OrganisationType::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'code';

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
            Text::make('code')->required()->help('F.e. example_organisation_type')
                ->rules('required', 'unique:organisation_types,code', 'max:255')->hideWhenUpdating(),
            //Todo only on create or if admin
            Boolean::make('Visible?', 'is_visible')->help('This organisation is visible.')->nullable(),
        ];

        return [
            ID::make(__('ID'), 'id')->sortable(),
            Translatable::make([
                Text::make('Name', 'name')->exceptOnForms(),
            ]),
            (new Tabs('Tabs', $tabs)),
            HasMany::make('Organisations', 'organisations')->nullable(),
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
