<?php

namespace App\Services;

use App\Models\Device;
use App\Models\RepairBarrier;
use App\Models\RepairLog;
use Exception;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class OrdsMappingService
{
    static public function getAllProductCategories()
    {
        try {
            $productCategories = Cache::remember('ords.product_categories', 2 * 60 * 60, function () {
                $productCategories = (new OrdpApiService())->getProductCategories();
                if(!$productCategories){
                    throw new Exception('ORDP not returning ORDS product categories.');
                }
                Cache::forever('ords.product_categories.fallback', $productCategories);

                return $productCategories;
            });
        } catch (\Throwable $exception) {
            report($exception);
        } finally {
            return $productCategories ?? Cache::get('ords.product_categories.fallback');
        }
    }
}
