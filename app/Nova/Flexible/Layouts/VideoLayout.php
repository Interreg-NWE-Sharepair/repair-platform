<?php

namespace App\Nova\Flexible\Layouts;

use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Whitecube\NovaFlexibleContent\Layouts\Layout;

class VideoLayout extends Layout
{
    /**
     * The layout's unique identifier
     *
     * @var string
     */
    protected $name = 'video';

    /**
     * The displayed title
     *
     * @var string
     */
    protected $title = 'Video embed';

    /**
     * Get the fields displayed by the layout.
     *
     * @return array
     */
    public function fields()
    {
        return [
            Text::make('Title')->translatable(),
            Text::make('Video URL', 'video')->help('Be sure you use the embed eg. https://www.youtube.com/embed/')
                ->nullable()->translatable(),
            Textarea::make('Video IFRAME', 'iframe')
                    ->help('You can add an iframe if the video source is <strong>not from YouTube</strong>. Paste the complete iframe script.')
                    ->nullable()->translatable(),
            Text::make('Video Caption', 'caption')->translatable(),
        ];
    }
}
