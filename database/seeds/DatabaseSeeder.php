<?php

use App\Models\Device;
use App\Models\DeviceType;
use App\Models\ImageCategory;
use App\Models\LocationOld;
use App\Models\RepairerOld;
use App\Models\RepairLog;
use App\Models\RepairLogLink;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(ImageCategoriesTableSeeder::class);
        $this->call(RolesSeeder::class);
        $this->call(RepairStatusesTableSeeder::class);

        factory(LocationOld::class, 5)->create();
        factory(RepairerOld::class, 10)->create()->each(function ($repairer) {
            $repairer->user->assignRole(\App\Models\Role::REPAIRER);
        });
        factory(DeviceType::class, 5)->create();
        factory(Device::class, 30)->create()->each(function ($device) {
            foreach (ImageCategory::all() as $category) {
                $device->addMediaFromUrl('https://picsum.photos/300/200')->toMediaCollection($category->code);
            }
        });
        factory(RepairLog::class, 15)->create()->each(function ($repairLog) {
            $repairLog->repairLinks()->saveMany(factory(RepairLogLink::class, 3)->make());
        });
    }
}
