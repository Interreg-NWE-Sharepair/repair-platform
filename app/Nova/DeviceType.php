<?php

namespace App\Nova;

use App\Services\OrdsMappingService;
use Eminiarts\Tabs\Tabs;
use Eminiarts\Tabs\TabsOnEdit;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\DeleteResourceRequest;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Http\Requests\ResourceDetailRequest;
use MichielKempen\NovaOrderField\Orderable;
use Spatie\NovaTranslatable\Translatable;

class DeviceType extends Resource
{
    use TabsOnEdit;
    use Orderable;

    public static $group = 'Options';

    public static $indexDefaultOrder = [
        'parent_id' => 'asc',
    ];

    public static $defaultOrderField = 'parent_id';

    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = 'App\Models\DeviceType';

    public static $with = ['parent'];

    /**
     * Get the value that should be displayed to represent the resource.
     *
     * @return string
     */
    public function title()
    {
        if ($this->model()->hasParent()) {
            return $this->name . ' (' . $this->parent->name . ')';
        }

        return $this->name;
    }

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id',
        'name',
    ];

    public static function indexQuery(NovaRequest $request, $query)
    {
        if (empty($request->get('orderBy'))) {
            $query->getQuery()->orders = [];

            return $query->orderBy(key(static::$indexDefaultOrder), reset(static::$indexDefaultOrder));
        }

        return $query;
    }

    /**
     * Get the URI key for the resource.
     *
     * @return string
     */
    public static function uriKey()
    {
        return 'device-type';
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
                    Text::make('Repair success rate?', 'repair_success_rate')->onlyOnForms(),
                    Text::make('Eco impact?', 'eco_impact')->onlyOnForms()->onlyOnForms(),
                ])->locales([$locale]),
            ];
        }
        $tabs['Non translatable fields'] = [
            Boolean::make('Visible?', 'is_visible'),
            Boolean::make('Show on Guidance tool?', 'show_on_guidance'),
            Boolean::make('Show on Repair Connect?', 'show_on_connects'),
            Boolean::make('Show on Repair Maps?', 'show_on_mapping'),
            BelongsTo::make('Parent category', 'parent', self::class)->nullable()->withoutTrashed(),
            Number::make('Max repair age?', 'max_repair_age')->nullable(),
            Boolean::make('Fixed by repair café?', 'is_fixed_by_repair_cafe'),
            Number::make('Average product weight', 'product_weight_kg')->step(0.00000001)->hideFromIndex(),
            Number::make('Average product pre-use CO2e', 'product_co_kg')->step(0.00000001)->hideFromIndex(),
            Select::make('ORDS value', 'ords_value')->options($this->getOrdsOptions())->hideFromIndex(),
        ];

        return [
            Translatable::make([
                Text::make('Naam', 'name')->exceptOnForms(),
            ]),
            (new Tabs('Tabs', $tabs)),
            HasMany::make('Common Device Issues', 'commonDeviceTypeIssues', CommonDeviceTypeIssue::class),
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

    private function getOrdsOptions()
    {
        $categories = OrdsMappingService::getAllProductCategories();

        $mappedCategories = [];
        foreach($categories ?? [] as $category){
            $mappedCategories[$category['id']] = $category['label'];
        }

        return $mappedCategories;
    }
}
