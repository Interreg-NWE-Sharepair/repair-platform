<?php

use App\Models\RepairBarrier;
use Illuminate\Database\Seeder;

class RepairBarriersRemoveNewData250321TableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            'NO_RESPONSE_FROM_OWNER',
            'UNSUITABLE_PRODUCT_TYPE',
        ];

        foreach ($data as $code) {
            /** @var RepairBarrier $barrier */
            $barrier = RepairBarrier::where('code', $code)->first();
            $barrier->delete();
        }
    }
}
