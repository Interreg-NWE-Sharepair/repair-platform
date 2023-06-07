<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Resources\Json\ResourceCollection;

/**
 * @OA\Schema(
 *     title="CompletedRepairStatusCollection",
 *     description="Completed repair status collection",
 *     @OA\Xml(
 *         name="CompletedRepairStatusCollection"
 *     ),
 *     @OA\Property(
 *      title="Data",
 *      property="data",
 *      description="Data wrapper",
 *      type="array",
 *          @OA\Items(
 *              title="data",
 *              ref="#/components/schemas/CompletedRepairStatus",
 *          ),
 *      )
 * )
 */
class CompletedRepairStatusCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return parent::toArray($request);
    }
}
