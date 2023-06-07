<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     title="DeviceTypeResource",
 *     description="Device type resource",
 *     @OA\Xml(
 *         name="DeviceTypeResource"
 *     ),
 *     @OA\Property(
 *          title="Data",
 *          property="data",
 *          ref="#/components/schemas/DeviceType",
 *     ),
 * ),
 */
class DeviceTypeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        /** @var \App\Models\DeviceType $deviceType */
        $deviceType = $this->resource;

        return [
            'id' => $deviceType->uuid,
            'code' => $deviceType->code,
            'name' => $deviceType->getLimitedTranslations('name'),
            'parent_category' => $this->when($deviceType->parent, self::make($deviceType->parent)),
            'ords_code' => $deviceType->ords_value,
        ];
    }
}
