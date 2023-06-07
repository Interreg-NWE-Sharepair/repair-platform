<?php

namespace App\Nova;

use App\Nova\Actions\ApproveLocationSuggestion;
use App\Nova\Filters\IsApproved;
use DmitryBubyakin\NovaMedialibraryField\Fields\Medialibrary;
use Eminiarts\Tabs\Tabs;
use Eminiarts\Tabs\TabsOnEdit;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Code;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Http\Requests\ActionRequest;
use NumaxLab\NovaCKEditor5Classic\CKEditor5Classic;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\NovaTranslatable\Translatable;

class LocationSuggestion extends Resource
{
    use TabsOnEdit;

    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\LocationSuggestion::class;

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
                    CKEditor5Classic::make('Description', 'description')->onlyOnForms(),
                    CKEditor5Classic::make('Product description', 'product_description')->onlyOnForms(),
                    CKEditor5Classic::make('Warranty info', 'warranty_info')->onlyOnForms(),
                ])->locales([$locale]),
            ];
        }
        $tabs['Non translatable fields'] = [
            BelongsTo::make('OrganisationType', 'OrganisationType')->withoutTrashed(),
            Code::make('address')->json(),
            Code::make('contacts')->json(),
            Code::make('locales')->json(),
            Code::make('device_types')->json(),
            Code::make('activity_sectors')->json(),
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
        ];
        $tabs['Submit details'] = [
            Text::make('submitter_email'),
            Textarea::make('submitter_relation'),
            //Code::make('original_details')->json()->readonly()->exceptOnForms()->hideFromIndex(),
            Date::make('Approved at', 'approved_at')->help('When was this suggestion approved?')->nullable(),
        ];

        return [
            ID::make('ID', 'id')->sortable(),
            BelongsTo::make('Suggested for location', 'Location', Location::class)->exceptOnForms(),
            Translatable::make([
                Text::make('Name', 'name')->exceptOnForms(),
                CKEditor5Classic::make('Description', 'description')->exceptOnForms()->hideFromIndex(),
                CKEditor5Classic::make('Product description', 'product_description')->exceptOnForms()->hideFromIndex(),
                CKEditor5Classic::make('Warranty info', 'warranty_info')->exceptOnForms()->hideFromIndex(),
            ]),
            (new Tabs('Tabs', $tabs)),
            HasMany::make('DeviceTypes'),
            HasMany::make('ActivitySectors')->readonly(),
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
            new IsApproved,
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
            (new ApproveLocationSuggestion)->canSee(function ($request) {
                if ($request instanceof ActionRequest) {
                    return true;
                }

                return $this->resource instanceof \App\Models\LocationSuggestion && !$this->resource->approved_at;
            }),
        ];
    }

    public function addDeviceType()
    {
        return false;
    }

    public function addActivitySector()
    {
        return false;
    }
}
