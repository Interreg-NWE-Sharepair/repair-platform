<?php

namespace App\Nova;

use Eminiarts\Tabs\Tabs;
use Eminiarts\Tabs\TabsOnEdit;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;
use MichielKempen\NovaOrderField\Orderable;
use MichielKempen\NovaOrderField\OrderField;
use Spatie\NovaTranslatable\Translatable;

class CompletedRepairStatus extends Resource
{
    use Orderable;
    use TabsOnEdit;

    public static $group = 'Options';

    public static $indexDefaultOrder = [
        'id' => 'asc',
    ];

    public static $defaultOrderField = 'order';

    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\CompletedRepairStatus::class;

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
                    Text::make('Tooltip', 'tooltip')->onlyOnForms(),
                ])->locales([$locale]),
            ];
        }

        $tabs['Non translatable fields'] = [
            Text::make('code')->readonly(),
            OrderField::make('Order'),
            Boolean::make('Visible?', 'is_visible'),
            Select::make('ORDS value', 'ords_value')->options($this->getOrdsOptions())->hideFromIndex(),
        ];

        return [
            Translatable::make([
                Text::make('Name', 'name')->exceptOnForms(),
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

    private function getOrdsOptions()
    {
        return [
            0 => 'Unknown',
            1 => 'Fixed',
            2 => 'Repairable',
            3 => 'End of life',
        ];
    }
}
