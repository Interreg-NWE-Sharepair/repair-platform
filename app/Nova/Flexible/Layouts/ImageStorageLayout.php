<?php

namespace App\Nova\Flexible\Layouts;

use Laravel\Nova\Fields\Image;
use Laravel\Nova\Fields\Text;
use Storage;
use Whitecube\NovaFlexibleContent\Layouts\Layout;

class ImageStorageLayout extends Layout
{
    /**
     * The layout's unique identifier
     *
     * @var string
     */
    protected $name = 'image-old';

    /**
     * The displayed title
     *
     * @var string
     */
    protected $title = 'Single image Local storage';

    /**
     * Get the fields displayed by the layout.
     *
     * @return array
     */
    public function fields()
    {
        return [
            Image::make('Image', 'image')->disk('public')->thumbnail(function ($value) {
                return Storage::disk('public')->url($value);
            }),
            Text::make('Image Caption', 'caption')->translatable(),
        ];
    }
}
