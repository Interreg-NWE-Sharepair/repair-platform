<?php

namespace App\Console\Commands;

use App\Models\Device;
use App\Scopes\TempScope;
use Carbon\Carbon;
use Illuminate\Console\Command;

class ClearTempDeviceEntriesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'replog:cleartempdeviceentries';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clears all devices with temp = 1 older than 1 day.';

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
     * @return mixed
     */
    public function handle()
    {
        echo "__________                                   \n";
        echo "\______   \__ _________  ____   ___________  \n";
        echo " |     ___/  |  \_  __ \/ ___\_/ __ \_  __ \ \n";
        echo " |    |   |  |  /|  | \/ /_/  >  ___/|  | \/ \n";
        echo " |____|   |____/ |__|  \___  / \___  >__|    \n";
        echo "                      /_____/      \/        \n";
        echo "----------------------------------------------- \n";
        echo "Auto-Purge schedule commencing... \n";
        $deviceCount = Device::withoutGlobalScope(new TempScope())->where([
            [
                'temp',
                '=',
                1,
            ],
            [
                'created_at',
                '<=',
                Carbon::now()->subday(),
            ],
        ])->delete();
        echo "Great success \n";
        echo 'Purged ' . $deviceCount . " device(s)! \n";
        echo "----------------------------------------------- \n";
    }
}
