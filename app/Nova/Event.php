<?php

namespace App\Nova;

use App\Nova\Filters\OrganisationType;
use Eminiarts\Tabs\Tabs;
use Eminiarts\Tabs\TabsOnEdit;
use Illuminate\Http\Request;
use Laraning\NovaTimeField\TimeField;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Timezone;
use Laravel\Nova\Http\Requests\NovaRequest;
use NumaxLab\NovaCKEditor5Classic\CKEditor5Classic;
use Spatie\NovaTranslatable\Translatable;
use Titasgailius\SearchRelations\SearchesRelations;
use function auth;

class Event extends Resource
{
    use TabsOnEdit;
    use SearchesRelations;

    public static $group = 'General';

    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\Event::class;

    public static $with = ['organisation'];

    /**
     * The relationship columns that should be searched.
     *
     * @var array
     */
    public static $searchRelations = [
        'organisation' => ['name'],
    ];

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
                    CKEditor5Classic::make('Description', 'description')->onlyOnForms()->hideFromIndex(),
                ])->locales([$locale]),
            ];
        }
        $tabs['Non translatable fields'] = [
            BelongsTo::make('Organisation', 'organisation', Organisation::class)->rules('required')->searchable(true)
                     ->withoutTrashed()
                     ->help('Type in the name of your organisation in Repair Connects, and then select it from the list. Watch out: the autocomplete search is case-sensitive'),
            Date::make('Date Start', 'date_start')->rules('required'),
            TimeField::make('Time Start', 'time_start')->rules('required'),
            TimeField::make('Time Stop', 'time_stop')->rules('required'),
            Timezone::make('Timezone', 'timezone')->rules('required'),
            Text::make('Address', 'address'),
            Boolean::make('Virtual?', 'is_online')->nullable(),
            Text::make('Organizer', 'organizer')->nullable(),
            Number::make('Max amount devices', 'max_devices')->nullable()
                  ->help('The maximum amount of devices this event can handle.'),
        ];

        return [
            Translatable::make([
                Text::make('Name', 'name')->exceptOnForms(),
                CKEditor5Classic::make('Description', 'description')->exceptOnForms()->hideFromIndex(),
            ]),
            (new Tabs('Tabs', $tabs)),
            HasMany::make('People', 'people', EventPeople::class)->hideWhenCreating()->hideWhenUpdating()
                   ->hideFromIndex(),
            HasMany::make('Devices', 'devices', Device::class)->hideWhenCreating()->hideWhenUpdating()->hideFromIndex(),
        ];
    }

    public static function relatableOrganisations(NovaRequest $request, $query)
    {
        $user = auth()->user();
        $employees = $user->employees;
        $locationIds = [];
        if ($employees) {
            foreach ($employees as $employee) {
                if ($employee->hasRole([
                    'entity-admin',
                    'event-organizer',
                ])) {
                    $locationIds[] = $employee->organisation->id;
                }
            }
        }

        if ($locationIds && !$user->hasRole([
                'statik',
                'admin',
            ])) {
            $query->whereIn('id', $locationIds);
        }

        return $query;
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

    public static function indexQuery(NovaRequest $request, $query)
    {
        $user = $request->user();

        return self::hasSameLocation($query, $user, [
            'entity-admin',
            'event-organizer',
        ]);
    }

    private static function hasSameLocation($query, $user, $roles)
    {
        $employees = $user->employees;

        $locationPermissions = [];
        foreach ($employees as $employee) {
            if ($employee->hasRole($roles)) {
                $locationPermissions[] = $employee->organisation->id;
            }
        }

        if ($locationPermissions && !$user->hasRole([
                'statik',
                'admin',
                'entity-admin',
                'event-organizer',
            ])) {
            $query->whereIn('organisation_id', $locationPermissions);
        }

        return $query;
    }
}
