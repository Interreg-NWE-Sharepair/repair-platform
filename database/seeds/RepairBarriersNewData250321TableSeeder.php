<?php

use Illuminate\Database\Seeder;

class RepairBarriersNewData250321TableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            ['name' => ['nl' => 'Geen reactie van eigenaar', 'en' => 'No response from owner'], 'code' => 'NO_RESPONSE_FROM_OWNER', 'is_visible' => true],
            ['name' => ['nl' => 'Type product niet geschikt voor RC', 'en' => 'Product type not suitable for RC'], 'code' => 'UNSUITABLE_PRODUCT_TYPE', 'is_visible' => true],
        ];

        foreach ($data as $barrier) {
            $status = \App\Models\RepairBarrier::firstOrCreate($barrier);
        }
    }
}
