<?php

namespace App\Console\Commands;

use App\Http\Clients\RestartersApiClient;
use App\Models\Event;
use App\Models\Organisation;
use App\Models\Tenant;
use App\Repository\RestartersRepositoryInterface;
use Illuminate\Console\Command;

class SyncRestartersDataCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'restarters:sync {after?}'; //Since accepts a iso date f.e. 2022-12-13

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Syncs Restarters groups and events since a certain date, with as default 2 days prior';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(RestartersApiClient $apiClient, RestartersRepositoryInterface $restartersRepository)
    {
        $replog = Tenant::whereCode(Tenant::REPLOG)->first();
        $replog->makeCurrent();

        $afterDate = $this->argument('after');
        if(!$afterDate){
            $afterDate = now()->addDays(-2)->format('Y-m-d');
        }

        $groups = $apiClient->getGroups($afterDate);
        foreach ($groups as $groupData) {
            $this->info('Updating restarters group: #'.$groupData['id']);
            $organisation = Organisation::getByRestartersId($groupData['id']);
            if ($organisation) {
                $restartersRepository->fillOrganisationByData($organisation, $groupData);
                $organisation->save();
            }
        }

        $events = $apiClient->getEvents($afterDate);
        foreach ($events as $eventData) {
            $this->info('Updating restarters event: #'.$eventData['id']);
            $event = Event::getByRestartersId($eventData['id']);
            if ($event) {
                $restartersRepository->fillEventByData($event, $eventData);
                $event->save();
            }
        }

        return 1;
    }
}
