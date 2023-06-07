<?php

namespace App\Nova;

use App\Nova\Actions\AddFromRestarters;
use App\Nova\Actions\LinkToRestarters;
use App\Nova\Actions\SyncDataFromRestarters;
use App\Nova\Actions\UnlinkFromRestarters;
use App\Nova\Filters\OrganisationRepairConnectActiveFilter;
use App\Nova\Filters\OrganisationRepairConnectVisibleFilter;
use App\Nova\Filters\OrganisationTypeFilter;
use App\Nova\Lenses\DataFromRestarters;
use App\Traits\LimitResultsByEmployees;
use DmitryBubyakin\NovaMedialibraryField\Fields\Medialibrary;
use Eminiarts\Tabs\Tabs;
use Eminiarts\Tabs\TabsOnEdit;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\BelongsToMany;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\MorphMany;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Http\Requests\ActionRequest;
use Laravel\Nova\Http\Requests\NovaRequest;
use NumaxLab\NovaCKEditor5Classic\CKEditor5Classic;
use Pdmfc\NovaCards\Info;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\NovaTranslatable\Translatable;
use Titasgailius\SearchRelations\SearchesRelations;

class Organisation extends Resource
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
    public static $model = \App\Models\Organisation::class;

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
        'uuid',
    ];

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
                    Text::make('Name', 'name')->onlyOnForms(),
                    CKEditor5Classic::make('Description', 'description')->onlyOnForms()->hideFromIndex(),
                    CKEditor5Classic::make('Product description', 'product_description')->onlyOnForms()
                                    ->hideFromIndex(),
                    Textarea::make('Warranty description', 'warranty_description')->onlyOnForms()
                                    ->hideFromIndex(),
                ])->locales([$locale]),
            ];
        }
        $tabs['Non translatable fields'] = [
            Medialibrary::make('logo', 'logo', 'digitalocean')->single()->previewUsing(function (Media $media) {
                    return $media->getTemporaryUrl(now()->addMinutes(5));
                })->downloadUsing(function (Media $media) {
                    return $media->getTemporaryUrl(now()->addMinutes(5));
                })->autouploading()->hideFromIndex(),
            BelongsTo::make('Organisation type', 'organisationType')->required()->withoutTrashed(),
            Boolean::make('Visible?', 'is_visible')
                   ->help('This organisation is visible on web pages in repair connects.')->nullable(),
            Boolean::make('Is active in repairconnects?', 'is_rc_active')
                   ->help('This organisation is active in repair connects.')->nullable(),
            Boolean::make('Online?', 'is_virtual')->help('This organisation is online only.')->nullable(),
            Boolean::make('Has warranty?', 'has_warranty')->help('This organisation has warranties.')->nullable()->hideFromIndex(),
            Boolean::make('Show employee info?', 'show_employee_info')
                   ->help('Employee info like telephone and email will be shown to the repairers of the organisation.')
                   ->nullable()->hideFromIndex(),
            Text::make('responsible_group')->hideFromIndex(),
        ];

        return [
            ID::make('ID', 'id')->sortable(),
            ID::make('UUID', 'uuid')->onlyOnDetail(),
            Translatable::make([
                Text::make('Name', 'name')->exceptOnForms(),
                CKEditor5Classic::make('Description', 'description')->exceptOnForms()->hideFromIndex(),
                CKEditor5Classic::make('Product description', 'product_description')->exceptOnForms()->hideFromIndex(),
                Textarea::make('Warranty description', 'warranty_description')->exceptOnForms()
                                ->hideFromIndex(),
            ]),
            (new Tabs('Tabs', $tabs)),
            MorphMany::make('Contact details', 'contactDetails'),
            HasMany::make('Locations', 'locations'),
            HasMany::make('OrganisationLocales', 'OrganisationLocales'),
            BelongsToMany::make('Device Types', 'deviceTypes'),
            BelongsToMany::make('ActivitySectors'),
            HasMany::make('Events', 'events')->hideWhenUpdating()->hideWhenCreating()->hideFromIndex(),
            Text::make('Has locations?', function () {
                return view('partials.organisation-locations', [
                    'amount_locations' => $this->locations()->count(),
                ])->render();
            })->asHtml(),
            Text::make('Restarters ID', 'restarters_id')->help('If this is filled in, this organisation is using data from restarters.')->onlyOnDetail()->nullable(),
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
        return [
            (new Info())->warning(trans('nova.warning_add_location_organisation')),
        ];
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
            new OrganisationTypeFilter(),
            new OrganisationRepairConnectActiveFilter(),
            new OrganisationRepairConnectVisibleFilter(),
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
        return [new DataFromRestarters];
    }

    /**
     * Get the actions available for the resource.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function actions(Request $request)
    {
        if ($request instanceof ActionRequest){
            $organisationQuery = $request->findModelQuery($request->resources);
        } else {
            $organisationQuery = $request->findModelQuery();
        }
        if ($organisationQuery){
            $organisation = $organisationQuery->fromRestarters()->first();
        }

        $isFromRestarters = $organisation ? true : false;

        return [
            LinkToRestarters::make()->canSee(function (Request $request) use ($isFromRestarters){
                return !$isFromRestarters;
            }),
            UnlinkFromRestarters::make()->exceptOnIndex()->canSee(function (Request $request) use ($isFromRestarters){
                return $isFromRestarters;
            }),
            SyncDataFromRestarters::make()->exceptOnIndex()->canSee(function (Request $request) use ($isFromRestarters){
                return $isFromRestarters;
            }),
            AddFromRestarters::make()->onlyOnIndex(),
        ];
    }

    public static function relatableDeviceTypes(NovaRequest $request, Builder $query)
    {
        return $query->whereNotNull('parent_id');
    }
}
