<?php

namespace App\Nova;

use App\Facades\EmployeeRepository;
use App\Nova\Actions\ActivatePerson;
use App\Nova\Actions\DeActivatePerson;
use App\Nova\Filters\EmployeeOrganisationType;
use App\Nova\Filters\PersonRoleFilter;
use App\Nova\Filters\PersonStatusType;
use App\Traits\LimitResultsByEmployees;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use KABBOUCHI\NovaImpersonate\Impersonate;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\MorphMany;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Panel;
use Maatwebsite\LaravelNovaExcel\Actions\DownloadExcel;
use NumaxLab\NovaCKEditor5Classic\CKEditor5Classic;
use Titasgailius\SearchRelations\SearchesRelations;

class Person extends Resource
{
    use SearchesRelations;
    use LimitResultsByEmployees;

    public static $group = 'Management';

    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\Person::class;

    public static $with = ['user'];

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'user.email';

    public function title()
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id',
        'first_name',
        'last_name',
    ];

    /**
     * The relationship columns that should be searched.
     *
     * @var array
     */
    public static $searchRelations = [
        'user' => ['email'],
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
        return [
            ID::make('ID', 'id')->sortable(),
            Text::make('First name', 'first_name')->rules('required')->sortable(true),
            Text::make('Last name', 'last_name')->rules('required')->sortable(true),
            new Panel('User information', $this->userFields()),
            CKEditor5Classic::make('Specialization', 'specialization')->nullable(),
            Text::make('Location', 'location')->hideFromIndex(),
            Text::make('Telephone', 'telephone')->exceptOnForms()->hideFromDetail()->hideFromIndex(),
            HasMany::make('Employees', 'employees'),

            MorphMany::make('Contact details', 'contactDetails'),
            Impersonate::make($this->user)->withMeta([
                'redirect_to' => '/',
            ]),
        ];
    }

    public function userFields()
    {
        return [
            BelongsTo::make('User', 'User', User::class)->rules('required')->searchable(true)->hideWhenUpdating(),
            Text::make('Email', 'user.email')->exceptOnForms(),
            Boolean::make('Active?', 'User.email_verified_at')->falseValue(null)->exceptOnForms(),
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
            new EmployeeOrganisationType(),
            new PersonStatusType(),
            new PersonRoleFilter(),
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
            new ActivatePerson(),
            new DeActivatePerson(),
            (new DownloadExcel())->withHeadings(),
        ];
    }

    public static function indexQuery(NovaRequest $request, $query)
    {
        $user = $request->user();

        return self::hasSameOrganisation($query, $user, [
            'entity-admin',
            'event-organizer',
        ]);
    }

    private static function hasSameOrganisation($query, $user, $roles)
    {
        $linkedEmployees = EmployeeRepository::getByUser($user)->get();

        $canSeeOrganisations = [];
        $isAdmin = false;
        foreach ($linkedEmployees as $linkedEmployee) {
            if ($linkedEmployee->hasRole('admin')) {
                $isAdmin = true;
            }
            if ($linkedEmployee->hasRole($roles)) {
                $canSeeOrganisations[] = $linkedEmployee->organisation->id;
            }
        }

        if ($canSeeOrganisations && !$user->hasRole('statik') && !$isAdmin) {
            $query->selectRaw('people.*')->with('user')->leftJoin('employees', 'employees.person_id', '=', 'people.id')
                  ->whereIn('organisation_id', $canSeeOrganisations)->groupBy('people.id');
        }

        return $query;
    }

    public static function relatableUsers(NovaRequest $request, Builder $query)
    {
        return $query->whereDoesntHave('person');
    }
}
