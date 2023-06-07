<?php

namespace App\Jobs;

use App\Http\Services\MailService;
use App\Models\Device;
use App\Models\Event;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Spatie\Multitenancy\Jobs\TenantAware;

/**
 * * @see \App\Providers\AppServiceProvider
 */
class DeviceEventReminderJob implements ShouldQueue, TenantAware
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct()
    {
    }

    public function handle()
    {
        $events = Event::query()->whereBetween('date_start', [
            Carbon::tomorrow()->setTime(0, 0),
            Carbon::tomorrow()->setTime(23, 59),
        ])->get();

        if ($events) {
            foreach ($events as $event) {
                $devices = Device::query()->whereHas('event', function (Builder $q) use ($event) {
                    $q->where('id', $event->id);
                })->stillBroken()->get();

                if ($devices) {
                    foreach ($devices as $device) {
                        $mailService = new MailService();
                        $mailService->sendEventReminderMail($device, $event);
                    }
                }
            }
        }
    }
}
