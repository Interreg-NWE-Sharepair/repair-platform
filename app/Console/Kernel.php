<?php

namespace App\Console;

use App\Jobs\DeviceEventReminderJob;
use App\Jobs\RepairerDeviceReminderJob;
use App\Jobs\WeeklyRepairerOpenDevicesJob;
use App\Models\Tenant;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Queue\Queue;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [//
    ];

    /**
     * Define the application's command schedule.
     *
     * @param \Illuminate\Console\Scheduling\Schedule $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('replog:cleartempdeviceentries')->dailyAt(23);
        $this->replogScheduler($schedule);

        $schedule->command('sitemap:generate')->monthlyOn('30');

        $schedule->command('restarters:sync')->dailyAt(1);

        $schedule->command('media-library:delete-old-temporary-uploads')->daily();

        //Todo move to supervisor or other worker system
        //Solves the stopping workers for now
        // This is fixed in crontab now
        //$schedule->command('queue:work --queue=default --delay=0 --memory=512 --sleep=3 --tries=3 --stop-when-empty')->everyMinute()->withoutOverlapping();
    }

    /**
     * @See \App\Providers\AppServiceProvider -> Added function to add the TenantID to the payload of the job (makeCurrent() does not work here)
     * @param \Illuminate\Console\Scheduling\Schedule $schedule
     * @return void
     */
    private function replogScheduler(Schedule $schedule): void
    {
        $schedule->job(new WeeklyRepairerOpenDevicesJob())->weeklyOn(1);
        $schedule->job(new RepairerDeviceReminderJob())->dailyAt(10);
        $schedule->job(new DeviceEventReminderJob())->dailyAt(13);
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }

}
