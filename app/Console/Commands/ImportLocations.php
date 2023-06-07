<?php

namespace App\Console\Commands;

use App\Imports\LocationImport;
use Illuminate\Console\Command;
use Maatwebsite\Excel\Facades\Excel;

class ImportLocations extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:locations {file : The location of the excel file in /storage} {responsible? : If you would like to overwrite the responsible} {countryCode? : give a specific countryCode} {--locale=nl}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Imports locations by provided excel file.';

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
        $locale = $this->option('locale');
        $responsible = $this->argument('responsible');
        $file = storage_path($this->argument('file'));
        $countryCode = $this->argument('countryCode');

        $import = new LocationImport($locale, $responsible, $countryCode);

        Excel::import($import, $file);

        return 0;
    }
}
