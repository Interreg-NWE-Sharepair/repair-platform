<?php

namespace App\Console\Commands;

use App\Facades\OrganisationRepository;
use App\Models\Page;
use App\Models\RepairTutorial;
use App\Models\Tenant;
use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Mcamara\LaravelLocalization\LaravelLocalization;
use Spatie\Sitemap\SitemapIndex;
use Spatie\Sitemap\Tags\Sitemap;

class GenerateSitemap extends Command
{

    const REPLOG_URL = 'https://repairconnects.org/';
    const REPGUI_URL = 'https://guidance.sharerepair.org/';
    /**
     * The console command name.
     *
     * @var string
     */
    protected $signature = 'sitemap:generate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate the sitemap.';

    /**
     * @var \Spatie\Sitemap\SitemapIndex
     */
    private SitemapIndex $sitemapIndex;

    /**
     * @TODO Rewrite this to work with multiple tenants!
     * Execute the console command.
     *
     * @return void
     */
    public function handle(): void
    {
        $file = public_path('sitemap.xml');

        $this->sitemapIndex = SitemapIndex::create();

        $tenants = Tenant::all();

        foreach ($tenants as $tenant) {
            /** @var Tenant $tenant */
            foreach (config('app.supported_locales') as $locale) {
                if ($tenant->code === Tenant::REPLOG) {
                    $tenant->makeCurrent();
                    $this->parseRepairConnects($tenant, $locale);
                    $tenant->forget();
                }
                if ($tenant->code === Tenant::REPGUI) {
                    $tenant->makeCurrent();
                    $this->parseGuidance($tenant, $locale);
                    $tenant->forget();
                }
            }
        }

        $this->sitemapIndex->writeToFile($file);
    }

    private function parseRepairConnects(Tenant $tenant, $locale)
    {
        app()->setLocale($locale);
        $sitemap = \Spatie\Sitemap\Sitemap::create();
        $sitemapName = Str::slug($tenant->name) . '_sitemap_' . $locale . '.xml';
        $localeFile = public_path($sitemapName);
        $laravelLocalized = new LaravelLocalization();
        $laravelLocalized->setLocale($locale);
        $laravelLocalized->setBaseUrl(self::REPLOG_URL);
        $sitemap->add($laravelLocalized->getURLFromRouteNameTranslated($locale, 'routes.home_index'))
                ->add($laravelLocalized->getURLFromRouteNameTranslated($locale, 'routes.about'))
                ->add($laravelLocalized->getURLFromRouteNameTranslated($locale, 'routes.privacy'))
                ->add($laravelLocalized->getURLFromRouteNameTranslated($locale, 'routes.cookies'))
                ->add($laravelLocalized->getURLFromRouteNameTranslated($locale, 'routes.terms_conditions'))
                ->add($laravelLocalized->getURLFromRouteNameTranslated($locale, 'routes.participation'))
                ->add($laravelLocalized->getURLFromRouteNameTranslated($locale, 'routes.instructions'))
                ->add($laravelLocalized->getURLFromRouteNameTranslated($locale, 'routes.repairer_login_index'))
                ->add($laravelLocalized->getURLFromRouteNameTranslated($locale, 'routes.repairer_register_index'))
                ->add($laravelLocalized->getURLFromRouteNameTranslated($locale, 'routes.device_create'))
                ->add($laravelLocalized->getURLFromRouteNameTranslated($locale, 'routes.contact_index'))
                ->add($laravelLocalized->getURLFromRouteNameTranslated($locale, 'routes.location_index'))
                ->add($laravelLocalized->getURLFromRouteNameTranslated($locale, 'routes.location_create'));

        $pages = Page::query()->whereNotNull("slug->$locale")->tenant($tenant->code)->whereNull('type')->get();

        if ($pages) {
            foreach ($pages as $page) {
                $sitemap->add($laravelLocalized->getURLFromRouteNameTranslated($locale, 'routes.static_page', ['page' => $page]));
            }
        }

        //Get Locations that are visible
        $organisations = OrganisationRepository::getAvailable($locale, null, true)->get();
        if ($organisations) {
            foreach ($organisations as $organisation) {
                $sitemap->add($laravelLocalized->getURLFromRouteNameTranslated($locale, 'routes.location_show', ['organisation' => $organisation]));
            }
        }

        $sitemap->writeToFile($localeFile);
        $this->sitemapIndex->add(Sitemap::create(self::REPLOG_URL . $sitemapName));
    }

    private function parseGuidance(Tenant $tenant, $locale)
    {
        app()->setLocale($locale);
        $sitemap = \Spatie\Sitemap\Sitemap::create();
        $sitemapName = Str::slug($tenant->name) . '_sitemap_' . $locale . '.xml';
        $localeFile = public_path($sitemapName);
        $laravelLocalized = new LaravelLocalization();
        $laravelLocalized->setLocale($locale);
        $laravelLocalized->setBaseUrl(self::REPGUI_URL);
        $sitemap->add($laravelLocalized->getURLFromRouteNameTranslated($locale, 'routes.home_index'))
        ->add($laravelLocalized->getURLFromRouteNameTranslated($locale, 'routes.about_project'))
        ->add($laravelLocalized->getURLFromRouteNameTranslated($locale, 'routes.privacy'))
        ->add($laravelLocalized->getURLFromRouteNameTranslated($locale, 'routes.cookies'))
        ->add($laravelLocalized->getURLFromRouteNameTranslated($locale, 'routes.terms_conditions'))
        ->add($laravelLocalized->getURLFromRouteNameTranslated($locale, 'routes.recycle'))
        ->add($laravelLocalized->getURLFromRouteNameTranslated($locale, 'routes.contact_index'))
        ->add($laravelLocalized->getURLFromRouteNameTranslated($locale, 'routes.tutorial_index'))
        ->add($laravelLocalized->getURLFromRouteNameTranslated($locale, 'routes.repair_impact_calculation_index'));

        $tutorials = RepairTutorial::all();
        if ($tutorials) {
            foreach ($tutorials as $tutorial) {
                $sitemap->add($laravelLocalized->getURLFromRouteNameTranslated($locale, 'routes.tutorial_show', ['repairTutorial:slug' => $tutorial->slug]));
            }
        }

        $sitemap->add($laravelLocalized->getURLFromRouteNameTranslated($locale, 'routes.tips_index'))
        ->add($laravelLocalized->getURLFromRouteNameTranslated($locale, 'routes.contribute_index'))
        ->add($laravelLocalized->getURLFromRouteNameTranslated($locale, 'routes.repair_map_index'))
        ->add($laravelLocalized->getURLFromRouteNameTranslated($locale, 'routes.guide_step_1'));


        $pages = Page::query()->whereNotNull("slug->$locale")->tenant($tenant->code)->whereNull('type')->get();

        if ($pages) {
            foreach ($pages as $page) {
                $sitemap->add($laravelLocalized->getURLFromRouteNameTranslated($locale, 'routes.static_page', ['page' => $page]));
            }
        }


        $sitemap->writeToFile($localeFile);
        $this->sitemapIndex->add(Sitemap::create(self::REPGUI_URL . $sitemapName));

    }
}
