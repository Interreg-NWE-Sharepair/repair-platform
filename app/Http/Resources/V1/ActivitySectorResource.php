<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     title="ActivitySectorResource",
 *     description="Activity sector resource",
 *     @OA\Xml(
 *         name="ActivitySectorResource"
 *     ),
 *     @OA\Property(
 *          title="Data",
 *          property="data",
 *          ref="#/components/schemas/ActivitySector",
 *     ),
 * ),
 */
class ActivitySectorResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        /** @var \App\Models\ActivitySector $activitySector */
        $activitySector = $this->resource;

        return [
            'id' => $activitySector->uuid,
            'code' => $activitySector->code,
            'name' => $activitySector->getLimitedTranslations('name'),
        ];
    }
}
