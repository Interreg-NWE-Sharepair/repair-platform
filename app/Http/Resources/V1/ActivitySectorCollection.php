<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Resources\Json\ResourceCollection;

/**
 * @OA\Schema(
 *     title="ActivitySectorCollection",
 *     description="Activity sector collection",
 *     @OA\Xml(
 *         name="ActivitySectorCollection"
 *     ),
 *     @OA\Property(
 *      title="Data",
 *      property="data",
 *      description="Data wrapper",
 *      type="array",
 *          @OA\Items(
 *              title="data",
 *              ref="#/components/schemas/ActivitySector",
 *          ),
 *      )
 * )
 */
class ActivitySectorCollection extends ResourceCollection
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
