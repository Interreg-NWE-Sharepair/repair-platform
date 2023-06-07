<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     title="OrganisationTypeResource",
 *     description="Organisation type resource",
 *     @OA\Xml(
 *         name="OrganisationTypeResource"
 *     ),
 *     @OA\Property(
 *          title="Data",
 *          property="data",
 *          ref="#/components/schemas/OrganisationType",
 *     ),
 * ),
 */
class OrganisationTypeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        /** @var \App\Models\OrganisationType $organisationType */
        $organisationType = $this->resource;

        return [
            'id' => $organisationType->uuid,
            'code' => $organisationType->code,
            'name' => $organisationType->getLimitedTranslations('name'),
        ];
    }
}
