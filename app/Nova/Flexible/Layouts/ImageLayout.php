<?php

namespace App\Nova\Flexible\Layouts;

use DmitryBubyakin\NovaMedialibraryField\Fields\Medialibrary;
use Laravel\Nova\Fields\Text;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Whitecube\NovaFlexibleContent\Concerns\HasMediaLibrary;
use Whitecube\NovaFlexibleContent\Layouts\Layout;

class ImageLayout extends Layout implements HasMedia
{
    use HasMediaLibrary;

    /**
     * The layout's unique identifier
     *
     * @var string
     */
    protected $name = 'image';

    /**
     * The displayed title
     *
     * @var string
     */
    protected $title = 'Single image';

    /**
     * Get the fields displayed by the layout.
     *
     * @return array
     */
    public function fields()
    {
        return [
            Medialibrary::make('image', 'image', 'digitalocean')->single()->previewUsing(function (Media $media) {
                    return $media->getTemporaryUrl(now()->addMinutes(5));
                })->downloadUsing(function (Media $media) {
                    return $media->getTemporaryUrl(now()->addMinutes(5));
                })->autouploading(),
            Text::make('Image Caption', 'caption')->translatable(),
        ];
    }
}
