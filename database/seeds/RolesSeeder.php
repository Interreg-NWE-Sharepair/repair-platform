<?php

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //Create roles
        $adminRole = Role::firstOrCreate(['name' => \App\Models\Role::ADMIN]);
        $repairerRole = Role::firstOrCreate(['name' => \App\Models\Role::REPAIRER]);
        $entityAdmin = Role::firstOrCreate(['name' => \App\Models\Role::ENTITY_ADMIN]);
        $eventOrganizer = Role::firstOrCreate(['name' => \App\Models\Role::EVENT_ORGANIZER]);
    }
}
