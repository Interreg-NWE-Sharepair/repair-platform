<?php

namespace App\Nova;

use App\Models\Role;
use App\Nova\Actions\UserDeleteAction;
use App\Nova\Filters\UserOrganisationType;
use App\Nova\Filters\UserType;
use Illuminate\Http\Request;
use KABBOUCHI\NovaImpersonate\Impersonate;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\HasOne;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Password;
use Laravel\Nova\Fields\Text;
use Maatwebsite\LaravelNovaExcel\Actions\DownloadExcel;
use Titasgailius\SearchRelations\SearchesRelations;
use Vyuldashev\NovaPermission\RoleBooleanGroup;

class User extends Resource
{
    use SearchesRelations;

    public static $group = 'Management';

    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = 'App\Models\User';

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
        'email',
    ];

    public static $searchRelations = [
        'roles' => ['name'],
        'permissions' => ['name'],
    ];

    public static function boot()
    {
        parent::boot();
        /*    static::creating(function ($user) {
                $user->email_verified_at = Carbon::now();
                $user->assignRole('admin');
            });*/
    }

    /**
     * Get the fields displayed by the resource.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function fields(Request $request)
    {
        return [
            ID::make('ID', 'id')->sortable(),
            Text::make('Name')->sortable()->rules('required', 'max:255'),

            Text::make('Email')->sortable()->rules('required', 'email', 'max:254')->creationRules('unique:users,email')
                ->updateRules('unique:users,email,{{resourceId}}'),

            Password::make('Password')->onlyOnForms()->creationRules('required', 'string', 'min:6')
                    ->updateRules('nullable', 'string', 'min:6'),
            Boolean::make('Active?', 'email_verified_at')->falseValue(null)->exceptOnForms(),
            //HasMany::make('User assigned locations', 'UserLocations', UserLocation::class),
            //BelongsTo::make('Repairer')->hideWhenCreating()->hideWhenUpdating()->nullable(),
            HasOne::make('Person')->hideWhenCreating()->hideWhenUpdating()->nullable(),
            HasMany::make('repairLogs'),
            RoleBooleanGroup::make('Roles')->rules('required')->options(Role::getUserOptions())
                            ->canSee(function ($request) {
                                return $request->user()->can('nova-admin');
                            }),
            Boolean::make('Ignore Automated Emails', 'ignore_automated_emails')
                   ->help('Won\'t receive automated mails like weekly new devices and devices still open')->default(0),
            Impersonate::make($this)->withMeta([
                'redirect_to' => '/',
            ]),
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
            new UserType(),
            new UserOrganisationType(),
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
            (new DownloadExcel())->withHeadings(),
            (new UserDeleteAction())
        ];
    }
}
