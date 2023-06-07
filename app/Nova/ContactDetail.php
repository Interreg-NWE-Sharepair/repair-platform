<?php

namespace App\Nova;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\UpdateResourceRequest;

class ContactDetail extends Resource
{
    public static $displayInNavigation = false;

    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\ContactDetail::class;

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
            Text::make('name')->help('Visable name')->displayUsing(function ($text) {
                return Str::limit($text, 100);
            })->required(),
            Text::make('value')->help('Value used for links and other logic')->hideFromIndex()
                ->rules($this->getValueRules($request)),
            Select::make('type')->options($this->getTypes()),
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
        $types = \App\Models\ContactDetail::TYPES;

        return array_combine($types, $types);
    }

    private function getValueRules(Request $request)
    {
        $valueRules = ['required'];

        if ($request instanceof UpdateResourceRequest) {

            switch ($request->get('type')) {
                case \App\Models\ContactDetail::TYPE_WEBSITE:
                    $valueRules[] = 'url';
                    break;
                case \App\Models\ContactDetail::TYPE_EMAIL:
                    $valueRules[] = 'email';
                    break;
            }
        }

        return $valueRules;
    }
}
