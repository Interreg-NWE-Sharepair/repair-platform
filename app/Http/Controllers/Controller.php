<?php

namespace App\Http\Controllers;

use App\Facades\EventRepository;
use App\Models\Device;
use App\Models\DeviceType;
use App\Models\Page;
use App\Models\RepairLog;
use Artesaos\SEOTools\Facades\SEOMeta;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected function setMetaTags(Page $page)
    {

        if ($page->title || $page->seo_description) {
            SEOMeta::setTitle($page->title)->setDescription($page->seo_description)->setKeywords($page->seo_keywords);
        }
    }

    //Return the frontend statuses
    public function getStatusOptions(): array
    {
        return $this->getFrontendStatuses();
    }

    /**
     * Get all the device types sorted by parent
     *
     * @return array
     */
    public function getDeviceTypeOptions()
    {
        $deviceTypes = DeviceType::visible()->showOnRepair()->with('parent')->orderBy('id')->get();

        $deviceTypeOptions = [];
        //Add all parents to array
        foreach ($deviceTypes as $deviceType) {
            if (!$deviceType->parent && !isset($deviceTypeOptions[$deviceType->id])) {
                $deviceTypeOptions[$deviceType->id]['name'] = $deviceType->name;
                $deviceTypeOptions[$deviceType->id]['value'] = $deviceType->id;
                $deviceTypeOptions[$deviceType->id]['options'] = [];
            }
        }
        //Add all children to parent array
        foreach ($deviceTypes as $deviceType) {
            if ($deviceType->parent) {
                $deviceTypeOption = [
                    'name' => $deviceType->name,
                    'value' => $deviceType->id,
                ];

                if ($deviceTypeOptions[$deviceType->parent->id]) {
                    $deviceTypeOptions[$deviceType->parent->id]['children'][] = $deviceTypeOption;
                }
            }
        }

        foreach ($deviceTypeOptions as $index => $parentOptions) {
            $options = collect($parentOptions['children'])->sortBy('parent')->values()->all();
            $parentOptions['children'] = $options;
            $deviceTypeOptions[$index] = $parentOptions;
        }

        //sort by key
        ksort($deviceTypeOptions);

        $orderedOptions = [];
        //Keep same order but with other index keys (0-4)
        $deviceTypeOptions = array_merge($orderedOptions, $deviceTypeOptions);

        return $deviceTypeOptions;
    }

    /**
     * For now show device types for mobile without parent category block (just like device edit modal does), because Niels can't figure it out on his last day
     * REPLOG-587 Mobile view shows json values.
     *
     * @return array
     */
    public function getDeviceTypesMobile()
    {
        $deviceTypes = DeviceType::visible()->showOnRepair()->with('parent')->orderBy('id')->whereNotNull('parent_id')->get();

        $deviceTypeOptions = [];

        foreach ($deviceTypes as $deviceType) {
            $deviceTypeOptions[$deviceType->id]['name'] = $deviceType->name;
            $deviceTypeOptions[$deviceType->id]['value'] = $deviceType->id;
            $deviceTypeOptions[$deviceType->id]['options'] = [];
        }

        ksort($deviceTypeOptions);

        $orderedOptions = [];
        //Keep same order but with other index keys (0-4)
        $deviceTypeOptions = array_merge($orderedOptions, $deviceTypeOptions);

        return $deviceTypeOptions;
    }

    /**
     * Returns the frontend filter options
     *
     * @param null $organisation
     * @return array
     */
    public function getDeviceFilterOptions($organisation = null): array
    {
        $filters = [
            'deviceType' => $this->getDeviceTypeOptions(),
            'status' => $this->getStatusOptions(),
            'order' => [
                'asc' => trans('messages.devices_sort_date_asc'),
                'desc' => trans('messages.devices_sort_date_desc'),
                'new' => trans('messages.devices_sort_status'),
            ],
        ];

        $events = $organisation ? EventRepository::getAllForOrganisation($organisation->uuid)
                                                 ->orderBy('date_start', 'desc')->get() : null;
        if ($events) {
            $eventArray = [];
            foreach ($events as $event) {
                $eventArray[] = [
                    'text' => $event->date_start->format('d/m/Y') . ' - ' . $event->name,
                    'value' => $event->id,
                ];
            }
            $filters['events'] = $eventArray;
        }

        return $filters;
    }

    public function getFrontendStatuses(): array
    {
        return [
            [
                'value' => RepairLog::STATUS_OPEN,
                'color' => Device::STATUS_COLORS[RepairLog::STATUS_OPEN],
                'status' => RepairLog::STATUS_OPEN,
                'text' => trans('messages.status_open'),
            ],
            [
                'value' => RepairLog::STATUS_REOPENED,
                'color' => Device::STATUS_COLORS[RepairLog::STATUS_REOPENED],
                'status' => RepairLog::STATUS_REOPENED,
                'text' => trans('messages.status_reopened'),
            ],
            [
                'value' => RepairLog::STATUS_IN_REPAIR,
                'color' => Device::STATUS_COLORS[RepairLog::STATUS_IN_REPAIR],
                'status' => RepairLog::STATUS_IN_REPAIR,
                'text' => trans('messages.status_in_repair'),
            ],
            [
                'value' => RepairLog::STATUS_COMPLETED,
                'color' => Device::STATUS_COLORS[RepairLog::STATUS_COMPLETED],
                'status' => RepairLog::STATUS_COMPLETED,
                'text' => trans('messages.status_completed'),
            ],
        ];
    }
}
