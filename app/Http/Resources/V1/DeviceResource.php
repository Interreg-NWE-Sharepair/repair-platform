<?php

namespace App\Http\Resources\V1;

use App\Services\OrdsMappingService;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     title="DeviceResource",
 *     description="Device resource",
 *     @OA\Xml(
 *         name="DeviceResource"
 *     ),
 *     @OA\Property(
 *          title="Data",
 *          property="data",
 *          ref="#/components/schemas/Device",
 *     ),
 * ),
 */
class DeviceResource extends JsonResource
{
    public function toArray($request)
    {
        /** @var \App\Models\Device $device */
        $device = $this->resource;
        $repairLog = $device->repairLog;
        $organisation = $device->organisation;
        $locations = $organisation->locations;
        //Default the first location, or the first headquarters location
        $primaryLocation = $locations->first();
        foreach ($locations as $location) {
            if ($location->is_headquarters) {
                $primaryLocation = $location;
                break;
            }
        }

        return [
            'uuid' => $device->uuid,
            'brand_name' => $device->brand_name,
            'model_name' => $device->model_name,
            'manufacture_year' => $device->manufacture_year,
            'device_type' => DeviceTypeResource::make($device->deviceType),
            'device_description' => $device->device_description,
            'issue_description' => $device->issue_description,
            'organisation' => [
                'uuid' => $organisation->uuid,
                'name' => $organisation->getLimitedTranslations('name'),
                'type' => OrganisationTypeResource::make($organisation->organisationType),
                'country' => $primaryLocation->country ?? null,
                'country_code' => $primaryLocation->country_code ?? null,
                'postal_code' => $primaryLocation->postal_code ?? null,
            ],
            'owner_postal_code' => $device->postal_code,
            'locale' => $device->locale,
            'fix_description' => $repairLog->fix_description,
            'diagnosis' => $repairLog->diagnosis,
            'root_cause' => $repairLog->root_cause,
            'completed_status' => $device->completedRepairStatus->code,
            'barrier' => $repairLog->repairBarrier->code ?? null,
            'ords' => [
                'repair_status' => $device->completedRepairStatus->ords_value ?? null,
                'repair_barrier_if_end_of_life' => $repairLog->repairBarrier->ords_value ?? null,
                'product_category' => $device->deviceType->ords_value ?? null,
            ],
            'public_url' => route('device_show_repaired', ['slug' => $device->slug]),
            'completed_at' => $device->isCompleted() ? $device->completed_at : null,
            'updated_at' => ($device->updated_at > $repairLog->updated_at) ? $device->updated_at : $repairLog->updated_at,
            'created_at' => $device->created_at,
        ];
    }
}
