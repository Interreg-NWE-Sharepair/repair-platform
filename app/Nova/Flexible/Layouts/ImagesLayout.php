<?php

namespace App\Nova\Flexible\Layouts;

use DmitryBubyakin\NovaMedialibraryField\Fields\Medialibrary;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Whitecube\NovaFlexibleContent\Concerns\HasMediaLibrary;
use Whitecube\NovaFlexibleContent\Layouts\Layout;

class ImagesLayout extends Layout implements HasMedia
{
    use HasMediaLibrary;

    /**
     * The layout's unique identifier
     *
     * @var string
     */
    protected $name = 'images';

    /**
     * The displayed title
     *
     * @var string
     */
    protected $title = 'Multiple images';

    /**
     * Get the fields displayed by the layout.
     *
     * @return array
     */
    public function fields()
    {
        return [
            Medialibrary::make('images', 'images', 'digitalocean')->previewUsing(function (Media $media) {
                    return $media->getTemporaryUrl(now()->addMinutes(5));
                })->downloadUsing(function (Media $media) {
                    return $media->getTemporaryUrl(now()->addMinutes(5));
                })->autouploading(),
        ];
    }
}
