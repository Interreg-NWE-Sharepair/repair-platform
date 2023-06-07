<?php

namespace App\Jobs;

use App\Http\Services\MailService;
use App\Models\RepairLog;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Spatie\Multitenancy\Jobs\TenantAware;

/**
 * @see \App\Providers\AppServiceProvider
 * Class RepairerDeviceReminderJob
 */
class RepairerDeviceReminderJob implements ShouldQueue, TenantAware
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
        $repairLogs = RepairLog::InProgress()->get();
        $mailService = new MailService();
        foreach ($repairLogs as $log) {
            /** @var RepairLog $log */
            $person = $log->person;
            $device = $log->device;
            $user = $person->user;

            if (!$log->person) {
                continue;
            }

            if (!$user->email_verified_at) {
                continue;
            }
            if (!$log->reminder_mail_sent && $log->created_at <= Carbon::now()->subWeeks(2) && $person && $device) {
                $mailService->sendDeviceReminderToRepairer($person, $device);
                $log->reminder_mail_sent = Carbon::now();
                $log->save();
            }
        }
    }
}
