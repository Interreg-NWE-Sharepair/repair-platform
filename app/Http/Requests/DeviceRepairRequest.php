<?php

namespace App\Http\Requests;

class DeviceRepairRequest extends AbstractFormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'brand_name' => 'required|string|max:250',
            'model_name' => 'nullable|string|max:500',
            'device_type_id' => 'required|exists:device_types,id',
            'manufacture_year' => 'nullable|integer|min:1901|max:2155',
            'image_general' => 'nullable',
            'images_defect' => 'nullable|max:15',
            'images_defect.*' => 'nullable',
            'images_barcode' => 'nullable|max:15',
            'images_barcode.*' => 'nullable',
            //'fix_description' => ($this->input('draft') === "true" || $this->input('showDescriptionField') === "false" ? 'nullable' : 'required|string|max:2500'),
            //'diagnosis' =>  'nullable|string|max:2500',
            //'root_cause' =>  'nullable|string|max:2500',
            //'used_materials' =>  'nullable|string|max:2500',
            //'used_links' =>  'nullable|string|max:2500',
            //'repair_status_id' => ($this->input('draft') === "true" ? 'nullable|exists:repair_statuses,id' : 'required|exists:repair_statuses,id'),
            //'images_repair.*' => 'nullable|image|max:10240',
            //'fix_links.*' => 'nullable|url'
        ];
    }
}
