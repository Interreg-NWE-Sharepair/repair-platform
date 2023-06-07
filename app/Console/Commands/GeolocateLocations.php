<?php

namespace App\Console\Commands;

use App\Models\Location;
use App\Services\LocationGeoService;
use Illuminate\Console\Command;

class GeolocateLocations extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'locations:generate-geo {--all}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate the geolocation data for locations. Use --all to regenerate for all locations.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $locations = $this->getLocations();

        $bar = $this->output->createProgressBar(count($locations));

        $bar->start();

        foreach ($locations as $location) {
            $this->updateLocationGeo($location);
            $bar->advance();
        }

        $bar->finish();
    }

    private function getLocations()
    {
        if ($this->option('all')) {
            return Location::all();
        }

        return Location::where(function ($query) {
            $query->whereNull('latitude')->orWhereNull('longitude');
        })->get();
    }

    private function updateLocationGeo(Location $location)
    {
        $longlat = LocationGeoService::geocodeLocation($location);

        if ($longlat) {
            $location->latitude = $longlat['lat'];
            $location->longitude = $longlat['lng'];
        } else {
            $this->info("Not found for location: {$location->id}");
        }

        $location->save();
    }
}
