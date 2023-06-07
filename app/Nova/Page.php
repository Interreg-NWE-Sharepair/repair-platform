<?php

namespace App\Nova;

use App\Nova\Filters\PageTenantFilter;
use App\Nova\Flexible\Layouts\HeaderLayout;
use App\Nova\Flexible\Layouts\ImageLayout;
use App\Nova\Flexible\Layouts\SimpleWysiwygLayout;
use App\Nova\Flexible\Layouts\SourceLayout;
use App\Nova\Flexible\Layouts\VideoLayout;
use App\Nova\Flexible\Layouts\WysiwygImageLayout;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Panel;
use Mcamara\LaravelLocalization\LaravelLocalization;
use NumaxLab\NovaCKEditor5Classic\CKEditor5Classic;
use Whitecube\NovaFlexibleContent\Flexible;

class Page extends Resource
{
    public static $group = 'Content pages';

    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\Page::class;

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

    public static function indexQuery(NovaRequest $request, $query)
    {
        $user = $request->user();
        if ($user->hasRole([
            'statik',
            'admin',
        ])) {
            return $query;
        }

        if ($user->hasRole('guidance-tool')) {
            $query->whereHas('tenant', function ($q) {
                $q->where('code', \App\Models\Tenant::REPGUI);
            });
        } else {
            $query->whereHas('tenant', function ($q) {
                $q->where('code', \App\Models\Tenant::REPLOG);
            });
        }
    }

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
            new Panel('Content', $this->getContentFields($request)),
            new Panel('Old Body Field (This will be removed in a future update)', $this->oldBodyField()),
            new Panel('SEO', $this->seoFields()),
        ];
    }

    public function headerFields()
    {
        app()->setLocale('en');
        return [
            //Text::make('title')->nullable()->translatable(),
            Text::make('Title', 'title')->hideFromDetail()->translatable()->displayUsing(function ($value) {
                return convertSpecialCharToNormalChar($value);
            }),
            Text::make('Intro', 'intro')->nullable()->translatable()->showOnIndex(false),
            Text::make('Slug', 'slug')->hideFromIndex()->translatable()->showOnIndex(false)
                ->help('This will be the slug of the page. Please use - instead of spaces, eg: my-custom-slug. <br/> <strong>Pages that already have a type won\'t use the slug option eg: about page or contact confirmation</strong>'),
            BelongsTo::make('Domain', 'tenant', Tenant::class)->required(),
            Select::make('Static page', 'type')->options([
                'register_device' => 'Register device',
                'register_repairer' => 'Register repairer',
                'contact_confirmation' => 'Contact thank you',
                'about' => 'About initiative',
                'privacy' => 'Privacy policy',
                'cookie' => 'Cookie policy',
                'terms' => 'Terms & conditions',
                'participation' => 'Participation',
                'instructions-and-feedback' => 'Instructions & feedback',
                'contribute' => 'Contribute',
                'recycle' => 'How to recycle',
                'tips' => 'General repair tips',
            ])->hideWhenUpdating()->nullable()->hideFromIndex()
                  ->help('Select a specific type of page (<strong>if it isn\'t already set or give it your own slug in field above.</strong>) <br/> You can only use a type per domain <strong>once!</strong>'),
        ];
    }

    public function seoFields()
    {
        return [
            Text::make('SEO Description', 'seo_description')->nullable()->translatable()->showOnIndex(false)
                ->help('Field will be used for the meta description.'),
            Text::make('SEO keywords', 'seo_keywords')->nullable()->translatable()->showOnIndex(false)
                ->help('Field will be used for the meta keywords, use comma seperation between words.'),
        ];
    }

    public function getContentFields($request)
    {
        return [
            Flexible::make('Content', 'content')/*->addLayout(HeaderLayout::class)*/->addLayout(SimpleWysiwygLayout::class)
                    ->addLayout(ImageLayout::class)->addLayout(WysiwygImageLayout::class)->addLayout(VideoLayout::class)
                    ->addLayout(SourceLayout::class)->fullWidth(),
            Text::make('Page Urls', function () use ($request) {
                if ($this->slug) {
                    $currentLocale = $request->getLocale();
                    $laravelLocalized = new LaravelLocalization();
                    $urls = null;

                    $laravelLocalized->setBaseUrl($this->getDomain($this->tenant));

                    foreach (config('laravellocalization.supportedLocales') as $index => $supportedLocale) {
                        $laravelLocalized->setLocale($index);
                        $url = $laravelLocalized->getURLFromRouteNameTranslated($index, 'routes.static_page', ['page' => $this]);
                        $urls .= '<a href="' . $url . '" target="_blank">' . $this->slug . '</a><br/>';
                    }

                    return $urls;
                }

                return '-';
            })->hideWhenUpdating()->hideWhenCreating()->asHtml(),
        ];
    }

    public function oldBodyField()
    {
        return [
            CKEditor5Classic::make('Body', 'body')->translatable()->help('This field will be removed in the future')
                            ->showOnIndex(false),
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
        $user = $request->user();
        if ($user->hasRole([
            'admin',
            'statik',
        ])) {
            return [
                new PageTenantFilter(),
            ];
        }

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

    private function getDomain(\App\Models\Tenant $tenant)
    {
        $env = config('app.env');
        $domains = $tenant->domains;

        foreach ($domains as $domain) {
            if ($env === 'live') {
                if (!strpos($domain, $env)) {
                    return 'https://' . $domain;
                }

                $domain = array_pop($domains);
                return 'https://' . $domain;
            }

            if (strpos($domain, $env)) {
                return 'https://' .$domain;
            }
        }
    }
}
