<?php

namespace App\Nova;

use App\Models\Location as ModelLocation;
use App\Nova\Actions\ExportLocationDataAction;
use App\Nova\Filters\HasGeodata;
use App\Nova\Filters\OrganisationsForLocationsFilter;
use App\Traits\LimitResultsByEmployees;
use DmitryBubyakin\NovaMedialibraryField\Fields\Medialibrary;
use Eminiarts\Tabs\Tabs;
use Eminiarts\Tabs\TabsOnEdit;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\MorphMany;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Http\Requests\ActionRequest;
use Laravel\Nova\Http\Requests\NovaRequest;
use Maatwebsite\LaravelNovaExcel\Actions\DownloadExcel;
use NumaxLab\NovaCKEditor5Classic\CKEditor5Classic;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\NovaTranslatable\Translatable;
use Titasgailius\SearchRelations\SearchesRelations;

class Location extends Resource
{
    use TabsOnEdit;
    use SearchesRelations;
    use LimitResultsByEmployees;

    public static $group = 'General';

    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = ModelLocation::class;

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
        'city',
        'uuid',
    ];

    /**
     * Get the URI key for the resource.
     *
     * @return string
     */
    public static function uriKey()
    {
        return 'location';
    }

    public static function softDeletes()
    {
        return false;
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
                    Text::make('Name', 'name')->onlyOnForms()
                        ->help('Repeat the name of your organisation and add the specific name of the location.'),
                    Textarea::make('Description', 'description')->onlyOnForms()->hideFromIndex(),
                ])->locales([$locale]),
            ];
        }
        $tabs['Non translatable fields'] = [
            BelongsTo::make('Organisation', 'Organisation')->searchable(true)->withoutTrashed(),
            Text::make('Street', 'street')->nullable()->hideFromIndex(),
            Text::make('Number', 'number')->nullable()->hideFromIndex(),
            Text::make('Bus', 'bus')->nullable()->hideFromIndex(),
            Text::make('Postal code', 'postal_code')->nullable()->hideFromIndex(),
            Text::make('City', 'city')->nullable(),
            Text::make('Country', 'country')->nullable(),
            Text::make('Country code', 'country_code')->nullable()->hideFromIndex(),
            Medialibrary::make('logo', 'logo', 'digitalocean')->single()->previewUsing(function (Media $media) {
                    return $media->getTemporaryUrl(now()->addMinutes(5));
                })->downloadUsing(function (Media $media) {
                    return $media->getTemporaryUrl(now()->addMinutes(5));
                })->autouploading(),
            Medialibrary::make('images', 'images', 'digitalocean')->previewUsing(function (Media $media) {
                    return $media->getTemporaryUrl(now()->addMinutes(5));
                })->downloadUsing(function (Media $media) {
                    return $media->getTemporaryUrl(now()->addMinutes(5));
                })->autouploading(),
            Number::make('Latitude', 'latitude')->nullable()->step(0.00000001)->placeholder('f.e. 51.2493')
                  ->help(trans('nova.automaticaly_calculated')),
            Number::make('Longitude', 'longitude')->nullable()->step(0.00000001)->placeholder('f.e. 3.28114')
                  ->help(trans('nova.automaticaly_calculated')),
            Boolean::make('Visible?', 'is_visible')->help('This location is visible.')->nullable(),
            Boolean::make('Headquarter location?', 'is_headquarters')->help('This is the main location for your organisation.')->nullable(),
        ];

        return [
            ID::make('ID', 'id')->sortable(),
            ID::make('UUID', 'uuid')->onlyOnDetail(),
            BelongsTo::make('Organisation', 'Organisation')->searchable(true)->withoutTrashed()->onlyOnDetail(),
            Translatable::make([
                Text::make('Name', 'name')->exceptOnForms(),
                Textarea::make('Description', 'description')->exceptOnForms()->hideFromIndex(),
            ]),
            (new Tabs('Tabs', $tabs)),
            MorphMany::make('Contact details', 'contactDetails'),
        ];
    }

    public static function relatableOrganisations(NovaRequest $request, $query)
    {
        $user = auth()->user();
        if ($user->hasRole([
            'statik',
            'admin',
        ])) {
            return $query;
        }

        $employees = $user->employees;
        $organisationIds = [];
        if ($employees) {
            foreach ($employees as $employee) {
                if ($employee->hasRole(['entity-admin'])) {
                    $organisationIds[] = $employee->organisation->id;
                }
            }
        }

        if ($organisationIds) {
            $query->whereIn('id', $organisationIds);
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
            //new \App\Nova\Filters\OrganisationType(),
            new OrganisationsForLocationsFilter(),
            new HasGeodata,
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
            (new DownloadExcel())->withHeadings()
        ];
    }
}
