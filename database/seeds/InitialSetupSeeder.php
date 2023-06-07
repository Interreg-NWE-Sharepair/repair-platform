<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

/**
 * This will add the initial data you need to get this project running
 */
class InitialSetupSeeder extends Seeder
{
    public function run()
    {
        ini_set('memory_limit', -1);
        \DB::unprepared(database_path('sql/initial_setup_repair.sql'));
    }
}
