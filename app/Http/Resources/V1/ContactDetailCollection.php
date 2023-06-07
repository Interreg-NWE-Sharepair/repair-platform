<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Resources\Json\ResourceCollection;

/**
 * @OA\Schema(
 *     title="LocationContactCollection",
 *     description="Location contact collection",
 *     @OA\Xml(
 *         name="LocationContactCollection"
 *     ),
 *     @OA\Property(
 *      title="Data",
 *      property="data",
 *      description="Data wrapper",
 *      type="array",
 *          @OA\Items(
 *              @OA\Property(
 *                  title="Type",
 *                  property="website",
 *                  type="array",
 *                  @OA\Items(
 *                      title="data",
 *                      ref="#/components/schemas/LocationContact",
 *                  ),
 *              ),
 *          ),
 *      )
 * )
 */
class ContactDetailCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return $this->map->toArray($request)->groupBy('type')->map->toArray()->all();
    }
}
