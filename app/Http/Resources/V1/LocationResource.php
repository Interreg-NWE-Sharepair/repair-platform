<?php

namespace App\Http\Resources\V1;

use App\Facades\DeviceRepository;
use App\Facades\EmployeeRepository;
use App\Models\Device;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     title="LocationResource",
 *     description="Location resource",
 *     @OA\Xml(
 *         name="LocationResource"
 *     ),
 *     @OA\Property(
 *          title="Data",
 *          property="data",
 *          ref="#/components/schemas/Location",
 *     ),
 * ),
 */
class LocationResource extends JsonResource
{
    public function toArray($request)
    {
        /** @var \App\Models\Location $location */
        $location = $this->resource;

        //$contactDetails = collect([]);
        //
        //if ($location->organisation && $location->organisation->contactDetails){
        //    $contactDetails = $contactDetails->merge($location->organisation->contactDetails);
        //}
        //
        //if ($location->contactDetails){
        //    $contactDetails = $contactDetails->merge($location->contactDetails);
        //}

        return [
            'id' => $location->uuid,
            'slug' => $location->getLimitedTranslations('slug'),
            'name' => $location->getLimitedTranslations('name'),
            'description' => $location->getLimitedTranslations('description'),
            'organisation_description' => optional($location->organisation)->getLimitedTranslations('description'),
            'product_description' => optional($location->organisation)->getLimitedTranslations('product_description'),
            'address' => [
                'street' => $location->street,
                'number' => $location->number,
                'bus' => $location->bus,
                'postal_code' => $location->postal_code,
                'city' => $location->city,
                'country' => $location->country,
                'country_code' => $location->country_code,
            ],
            'address_formatted' => $location->address,
            'geometry' => [
                'latitude' => $location->latitude,
                'longitude' => $location->longitude,
            ],
            'has_warranty' => optional($location->organisation)->has_warranty,
            'warranty_description' => $this->when(optional($location->organisation)->has_warranty, optional($location->organisation)->getLimitedTranslations('warranty_description')),
            'organisation_type' => OrganisationTypeResource::make($location->organisationType),
            //Deprecated
            'product_categories' => DeviceTypeCollection::make(optional($location->organisation)->deviceTypes ?? []),

            'device_types' => DeviceTypeCollection::make(optional($location->organisation)->deviceTypes ?? []),
            'activity_sectors' => ActivitySectorCollection::make(optional($location->organisation)->activitySectors ?? []),
            //'contacts' => ContactDetailCollection::make($contactDetails),
            'contacts' => ContactDetailCollection::make(optional($location->organisation)->contactDetails ?? []),
            'locales' => optional($location->organisation)->locales,
            'logo' => MediaResource::make($location->getFirstMedia('logo')),
            'images' => MediaCollection::make($location->getMedia('images')),
            'active_on_repair_connects' => $location->organisation ? $location->organisation->is_rc_active : false,
            'active_repairers_count' => $location->organisation ? EmployeeRepository::countByOrganisation($location->organisation) : null,
            'repaired_devices_count' => $location->organisation ?
                DeviceRepository::returnCount(DeviceRepository::queryByOrganisationAndStatus([$location->organisation->uuid], 'repaired')) :
                null,
        ];
    }
}
