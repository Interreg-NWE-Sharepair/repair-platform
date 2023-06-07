<?php

namespace App\Models;

use App\Services\OrdpApiService;
use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin IdeHelperRepairGuidanceFormLog
 */
class RepairGuidanceFormLog extends Model
{
    use HasFactory;
    use HasUuid;

    const TYPE_GUIDANCE = 'guide';
    const TYPE_VITO = 'vito';

    protected $fillable = [
        'device_type_id',
        'device_type_name',
        'brand_name',
        'model_name',
        'product_description',
        'product_age',
        'common_device_type_issue_id',
        'common_issue_text',
        'locale',
        'extra_properties',
        'uuid',
        'source'
    ];

    public function deviceType()
    {
        return $this->belongsTo(DeviceType::class);
    }

    public function commonDeviceTypeIssues()
    {
        return $this->belongsToMany(CommonDeviceTypeIssue::class, 'guidance_device_type_issues')->withTimestamps();
    }

    public function needsRecycling()
    {
        $maxRepairAge = $this->deviceType->max_repair_age;
        if ($maxRepairAge) {
            $maxRepairAge = (int) $maxRepairAge;
            $productAge = $this->product_age;
            $productAge = collect(explode('-', $productAge));
            $productAge = (int) $productAge->last();
            if ($productAge >= $maxRepairAge) {
                return true;
            }
        }

        return false;
    }

    public function store(array $data)
    {

        $deviceType = $this->device_type_id;
        $deviceTypeName = $this->device_type_name;
        //Reconfigure based on mobile select of deviceType of regular select device Type.
        // If it changes from the original filled in item -> change to
        if (isset($data['device_type_id']) && $data['device_type_id'] && $this->device_type_id !== (int)$data['device_type_id']) {
            $deviceType = $data['device_type_id'];
            $deviceTypeName = DeviceType::where('id', $deviceType)->first();
        }

        if (isset($data['device_type_mobile_id']) && $data['device_type_mobile_id'] && $this->device_type_id !== (int)$data['device_type_mobile_id']) {
            $deviceType = $data['device_type_mobile_id'];
            $deviceTypeName = DeviceType::where('id', $deviceType)->first()->name;
        }


        $data['device_type_id'] = $deviceType;
        $data['device_type_name'] = $deviceTypeName;

        $this->fill($data);
        $this->save();
        $this->refresh();

        return $this;
    }

    public function getOrdpGuides($page = 1, $perPage = 3)
    {
        $search = $this->getSearchStringForExternalSources();

        return (new OrdpApiService)->getGuidelines($search, $page, $perPage);
    }

    public function getSearchStringForExternalSources()
    {
        $searchArray = [];
        if ($this->brand_name && $this->model_name){
            $searchArray[] = $this->brand_name;
            $searchArray[] = $this->model_name;
        }
        else{
            $searchArray[] = $this->deviceType->getTranslation('name', 'en');
            if($this->brand_name) {
                $searchArray[] = $this->brand_name;
            }
        }

        return implode(' ', $searchArray);
    }
}
