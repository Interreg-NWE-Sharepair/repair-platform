<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Resources\Json\ResourceCollection;

/**
 * @OA\Schema(
 *     title="OrganisationTypeCollection",
 *     description="Organisation Type collection",
 *     @OA\Xml(
 *         name="OrganisationTypeCollection"
 *     ),
 *     @OA\Property(
 *      title="Data",
 *      property="data",
 *      description="Data wrapper",
 *      type="array",
 *          @OA\Items(
 *              title="data",
 *              ref="#/components/schemas/OrganisationType",
 *          ),
 *      )
 * )
 */
class OrganisationTypeCollection extends ResourceCollection
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return parent::toArray($request);
    }
}
