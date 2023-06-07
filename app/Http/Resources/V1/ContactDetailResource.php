<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     title="LocationContactResource",
 *     description="Location contact resource",
 *     @OA\Xml(
 *         name="LocationContactResource"
 *     ),
 *     @OA\Property(
 *          title="Data",
 *          property="data",
 *          ref="#/components/schemas/LocationContact",
 *     ),
 * ),
 */
class ContactDetailResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        /** @var \App\Models\ContactDetail $locationContact */
        $locationContact = $this->resource;

        return [
            'name' => $locationContact->name,
            'value' => $locationContact->value,
            'type' => $locationContact->type,
        ];
    }
}
