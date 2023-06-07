<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Resources\Json\ResourceCollection;

/**
 * @OA\Schema(
 *     title="DeviceCollection",
 *     description="Device collection",
 *     @OA\Xml(
 *         name="DeviceCollection"
 *     ),
 *     @OA\Property(
 *      title="Data",
 *      property="data",
 *      description="Data wrapper",
 *      type="array",
 *          @OA\Items(
 *              title="data",
 *              ref="#/components/schemas/Device",
 *          ),
 *      )
 * )
 */
class DeviceCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return parent::toArray($request);
    }
}
