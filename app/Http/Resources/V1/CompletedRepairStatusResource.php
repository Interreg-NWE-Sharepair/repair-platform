<?php

namespace App\Http\Resources\V1;

use App\Services\OrdsMappingService;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     title="CompletedRepairStatusResource",
 *     description="Completed repair status resource",
 *     @OA\Xml(
 *         name="CompletedRepairStatusResource"
 *     ),
 *     @OA\Property(
 *          title="Data",
 *          property="data",
 *          ref="#/components/schemas/CompletedRepairStatus",
 *     ),
 * ),
 */
class CompletedRepairStatusResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        $completedStatus = $this->resource;

        return [
            'code' => $completedStatus->code,
            'ords_code' => $completedStatus->ords_value,
        ];
    }
}
