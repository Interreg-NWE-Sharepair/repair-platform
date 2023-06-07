<?php

namespace App\Nova;

use App\Nova\Filters\Repgui\DeviceTypeFilter;
use App\Nova\Flexible\Layouts\HeaderLayout;
use App\Nova\Flexible\Layouts\ImageLayout;
use App\Nova\Flexible\Layouts\ImagesLayout;
use App\Nova\Flexible\Layouts\SimpleWysiwygLayout;
use App\Nova\Flexible\Layouts\SourceLayout;
use App\Nova\Flexible\Layouts\VideoLayout;
use App\Nova\Flexible\Layouts\WysiwygImageLayout;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Panel;
use Whitecube\NovaFlexibleContent\Flexible;

class RepairTutorial extends Resource
{
    public static $group = 'Content pages';

    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\RepairTutorial::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'title';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id',
        'title',
        'slug',
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
            new Panel('Header', $this->headerFields()),
            new Panel ('content', $this->getContentFields()),
        ];
    }

    public function headerFields()
    {
        return [
            Text::make('Title', 'title')->translatable()->displayUsing(function ($value) {
                return substr($value, 0, 50) . '...';
            }),
            Text::make('Description', 'description')->hideFromIndex()->translatable(),
            Text::make('Overview Intro', 'intro')->hideFromIndex()->translatable()->help('This intro will be used on the overview page of repair tutorials.'),
            BelongsTo::make('Device Type', 'deviceType', DeviceType::class)->nullable()->required()->withoutTrashed(),
            BelongsTo::make('Common Device type issue', 'commonDeviceTypeIssue', CommonDeviceTypeIssue::class)
                     ->nullable()->withoutTrashed(),
            Boolean::make('Highlighted on homepage?', 'is_highlight')->nullable()->help('This tutorial will be shown on the homepage. Within the "in the picture" block.'),
            Boolean::make('Show disclaimer?', 'has_disclaimer')->nullable()->help('This will show following disclaimer on screen: </br> <strong>' . trans('messages.tutorial_disclaimer') . '</strong>')
        ];
    }

    public function getContentFields()
    {
        return [
            Flexible::make('Content', 'content')->addLayout(HeaderLayout::class)->addLayout(SimpleWysiwygLayout::class)
                    ->addLayout(ImageLayout::class)->addLayout(ImagesLayout::class)
                    ->addLayout(WysiwygImageLayout::class)->addLayout(VideoLayout::class)->addLayout(SourceLayout::class)->fullWidth(),
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
            new DeviceTypeFilter(),
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
}
