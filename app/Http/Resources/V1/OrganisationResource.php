<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Resources\Json\JsonResource;

class OrganisationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        /** @var \App\Models\Organisation $organisation */
        $organisation = $this->resource;

        return [
            'id' => $organisation->uuid,
            'slug' => $organisation->getLimitedTranslations('slug'),
            'name' => $organisation->getLimitedTranslations('name'),
            'organisation_description' => $organisation->getLimitedTranslations('description'),
            'product_description' => $organisation->getLimitedTranslations('product_description'),
            'has_warranty' => $organisation->has_warranty,
            'warranty_description' => $this->when($organisation->has_warranty, $organisation->getLimitedTranslations('warranty_description')),

            'organisation_type' => OrganisationTypeResource::make($organisation->organisationType),
            'product_categories' => DeviceTypeCollection::make($organisation->deviceTypes),
            'activity_sectors' => ActivitySectorCollection::make($organisation->activitySectors),
            'contacts' => ContactDetailCollection::make($organisation->contactDetails),
            'locales' => $organisation->locales,
        ];
    }
}
