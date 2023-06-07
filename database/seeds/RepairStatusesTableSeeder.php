<?php

use App\Models\RepairStatus;
use Illuminate\Database\Seeder;

class RepairStatusesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            ['name' => 'Opgelost (hersteld)', 'code' => 'FIXED', 'order' => 1, 'is_visible' => true],
            ['name' => 'End of life (niet te herstellen)', 'code' => 'END_OF_LIFE', 'order' => 2, 'is_visible' => true],
            ['name' => 'Toestel was niet defect', 'code' => 'NEVER_DEFECT', 'order' => 3, 'is_visible' => true],
            ['name' => 'Nog steeds defect', 'code' => 'DEFECT', 'order' => 4, 'is_visible' => true],
        ];

        foreach ($data as $status) {
            $status = RepairStatus::firstOrCreate($status);
        }
    }
}
