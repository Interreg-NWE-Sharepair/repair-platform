<?php

namespace App\Nova\Flexible\Layouts;

use DmitryBubyakin\NovaMedialibraryField\Fields\Medialibrary;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;
use NumaxLab\NovaCKEditor5Classic\CKEditor5Classic;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Whitecube\NovaFlexibleContent\Concerns\HasMediaLibrary;
use Whitecube\NovaFlexibleContent\Layouts\Layout;

class WysiwygImageLayout extends Layout implements HasMedia
{
    use HasMediaLibrary;

    /**
     * The layout's unique identifier
     *
     * @var string
     */
    protected $name = 'wysiwyg-image';

    /**
     * The displayed title
     *
     * @var string
     */
    protected $title = 'Wysiwyg + image on side';

    /**
     * Get the fields displayed by the layout.
     *
     * @return array
     */
    public function fields()
    {
        return [

            CKEditor5Classic::make('Body', 'body')->options([
                'link' => [
                    'decorators' => [
                        'openInNewTab' => [
                            'mode' => 'manual',
                            'label' => 'Open in a new tab',
                            'attributes' => [
                                'target' => 'blank',
                                'rel' => 'noopenen noreferrer',
                            ],
                        ],
                        'primaryButton' => [
                            'mode' => 'manual',
                            'label' => 'Primary color button',
                            'attributes' => [
                                'class' => 'btn btn--primary btn--ext mr-2',
                            ],
                        ],
                        'secondaryButton' => [
                            'mode' => 'manual',
                            'label' => 'Secondary color button',
                            'attributes' => [
                                'class' => 'btn btn--secondary btn--ext mr-2',
                            ],
                        ],
                    ],
                ],
            ])->translatable(),
            Medialibrary::make('Image', 'image', 'digitalocean')->single()->previewUsing(function (Media $media) {
                return $media->getTemporaryUrl(now()->addMinutes(5));
            })->downloadUsing(function (Media $media) {
                return $media->getTemporaryUrl(now()->addMinutes(5));
            })->autouploading(),

            Text::make('Image Caption', 'caption')->translatable(),
            Select::make('Image Position', 'position')->options([
                'left' => 'Left',
                'right' => 'Right',
            ]),
        ];
    }
}
