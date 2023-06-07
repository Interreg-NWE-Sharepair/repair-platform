<?php

namespace App\Nova;

use App\Nova\Filters\ContactOrganisation;
use App\Nova\Filters\PageTenantFilter;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Http\Requests\NovaRequest;
use Titasgailius\SearchRelations\SearchesRelations;

class Contact extends Resource
{
    use SearchesRelations;

    public static $group = 'General';

    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = 'App\Models\Contact';

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
        'name',
        'email',
        'message',
    ];

    public static function indexQuery(NovaRequest $request, $query)
    {
        $user = $request->user();

        if ($user->hasRole('statik')) {
            return $query;
        }

        if ($user->hasRole('guidance-tool')) {
            $query->whereHas('tenant', function ($q) {
                $q->where('code', \App\Models\Tenant::REPGUI);
            });
        } else {
            $query->whereHas('tenant', function ($q) {
                $q->where('code', \App\Models\Tenant::REPLOG);
            });
        }
    }

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
            Text::make('Name', 'name')->readonly(),
            Text::make('Email', 'email')->readonly(),
            BelongsTo::make('Domain', 'tenant', Tenant::class)->required()->onlyOnIndex(),
            BelongsTo::make('Organisation', 'organisation', Organisation::class)->readonly()->searchable(true),
            Textarea::make('Message', 'message')->readonly(),
            DateTime::make('Datum inzending', 'created_at')->readonly(),
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
            new PageTenantFilter(),
            new ContactOrganisation(),
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

    public static function label()
    {
        return 'Contact form';
    }

    public static function singularLabel()
    {
        return 'Contact entry';
    }
}
