<?php

namespace App\Console\Commands;

use App\Models\Location;
use App\Models\Organisation;
use Illuminate\Console\Command;

/**
 * @deprecated
 * @see REPMAP-125
 * @todo Remove after release
 */
class ClearHtmlEntitiesInLocationDescription extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'locations:clear-description-html';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clears the html of the location descriptions';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $locations = \App\Models\Location::with('organisation')->whereNotNull('description')
            ->where('updated_at', '<=', \Carbon\Carbon::now()->minutes(-30))->orderBy('updated_at')->get();
        /** @var \App\Models\Location $location */
        foreach ($locations as $location){
            $this->fixDescription($location, 'en');
            $this->fixDescription($location, 'nl');
            $this->fixDescription($location, 'fr');
            $this->fixDescription($location, 'de');
            $location->save();

            $organisation = $location->organisation;
            if ($organisation) {
                $this->fixWarrentyDescription($organisation, 'en');
                $this->fixWarrentyDescription($organisation, 'nl');
                $this->fixWarrentyDescription($organisation, 'fr');
                $this->fixWarrentyDescription($organisation, 'de');
                $organisation->save();
            }
        }
    }

    public function fixDescription(Location $location, $locale)
    {
        if ($description = $location->getTranslation('description', $locale, false)) {
            $location->setTranslation('description', $locale, strip_tags($description));
        }
    }

    public function fixWarrentyDescription(Organisation $organisation, $locale)
    {
        if ($description = $organisation->getTranslation('warranty_description', $locale, false)) {
            $organisation->setTranslation('warranty_description', $locale, strip_tags($description));
        }
    }
}
