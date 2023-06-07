<?php

use App\Models\ImageCategory;
use Illuminate\Database\Seeder;

class ImageCategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            ['name' => 'general', 'code' => 'GENERAL'],
            ['name' => 'defect', 'code' => 'DEFECT'],
            ['name' => 'barcode', 'code' => 'BARCODE'],
            ['name' => 'repair', 'code' => 'REPAIR'],
        ];

        foreach ($data as $category) {
            $category = ImageCategory::firstOrCreate($category);
        }
    }
}
