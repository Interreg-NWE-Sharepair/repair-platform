<?php

namespace App\Jobs;

use App\Facades\EmployeeOrganisationRepository;
use App\Facades\OrganisationRepository;
use App\Http\Services\MailService;
use App\Models\Device;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Spatie\Multitenancy\Jobs\TenantAware;

/**
 * @see \App\Providers\AppServiceProvider
 * Class WeeklyRepairerOpenDevicesJob
 */
class WeeklyRepairerOpenDevicesJob implements ShouldQueue, TenantAware
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $organisations = OrganisationRepository::all();

        foreach ($organisations as $organisation) {
            $query = Device::query()->stillBroken()->whereHas('organisation', function ($q) use ($organisation) {
                $q->where('id', $organisation->id);
            })->organisation($organisation->uuid)->orderBy('created_at');
            $deviceAmountQuery = clone $query;
            $openDevices = $query->limit(10)->get();
            $deviceAmount = $deviceAmountQuery->count();

            if ($openDevices) {
                $repairers = EmployeeOrganisationRepository::getRepairersByOrganisation($organisation)->get();

                if (count($repairers) >= 1 && count($openDevices) >= 1) {
                    $this->sendOpenDevicesToRepairers($repairers, $openDevices, $organisation, $deviceAmount);
                }
            }
        }
    }

    /**
     * Send every repairer of a specific organisation all the still broken or new devices with a limit of 10 devices.
     *
     * @param $repairers
     * @param $openDevices
     * @param $organisation
     * @param $deviceAmount
     */
    private function sendOpenDevicesToRepairers($repairers, $openDevices, $organisation, $deviceAmount): void
    {
        $mailService = new MailService();
        foreach ($repairers as $repairer) {
            $person = $repairer->person;
            $mailService->sendOpenDevicesToRepairer($person, $openDevices, $organisation, $deviceAmount);
        }
    }
}
