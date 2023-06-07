<?php

namespace App\Nova\Flexible\Layouts;

use NumaxLab\NovaCKEditor5Classic\CKEditor5Classic;
use Whitecube\NovaFlexibleContent\Layouts\Layout;

class SimpleWysiwygLayout extends Layout
{
    /**
     * The layout's unique identifier
     *
     * @var string
     */
    protected $name = 'wysiwyg';

    /**
     * The displayed title
     *
     * @var string
     */
    protected $title = 'Simple wysiwyg';

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

        ];
    }
}
