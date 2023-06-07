<?php

return [

    /**
     * The locales which the `translatable` wrapper will use by default.
     *
     * Can be a:
     *  - Keyed array (['en' => 'English])
     *  - Callable that returns a keyed array
     */
    'locales' => ['en' => 'English', 'nl' => 'Nederlands', 'fr' => 'Français', 'de' => 'Deutch'],

    /**
     * If enabled, the Nova's current locale will always be displayed as
     * the first locale in the tabs arrangement.
     *
     * If disabled, the locales array order is respected.
     */
    'prioritize_nova_locale' => false,

    /**
     * Defines the display type for the translatable tabs.
     *
     * Available options are: 'row', 'column'
     */
    'display_type' => 'row',

];
