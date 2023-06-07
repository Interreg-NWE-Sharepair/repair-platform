<?php

namespace App\Nova;

use App\Models\Role;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\MorphMany;
use Laravel\Nova\Fields\Select;
use Titasgailius\SearchRelations\SearchesRelations;
use Vyuldashev\NovaPermission\RoleBooleanGroup;

class Employee extends Resource
{
    public static $displayInNavigation = false;

    use SearchesRelations;

    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\Employee::class;

    public static $with = ['person'];

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
        return [
            ID::make('ID', 'id')->sortable(),
            BelongsTo::make('Person', 'person'),
            BelongsTo::make('Organisation', 'organisation')->searchable(true)->withoutTrashed(),
            Select::make('Employee type', 'employee_type')->options($this->getTypes())
                  ->default(\App\Models\Employee::TYPE_REPAIRER)->readonly(true),
            RoleBooleanGroup::make('Roles')->rules('required')->options(Role::getEmployeeOptions()),
            MorphMany::make('Contact details', 'contactDetails'),
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

    private function getTypes()
    {
        $types = \App\Models\Employee::TYPES;

        return array_combine($types, $types);
    }

    public static function label()
    {
        return 'Employee organisation';
    }

    public static function singularLabel()
    {
        return 'employee for organisation';
    }
}
