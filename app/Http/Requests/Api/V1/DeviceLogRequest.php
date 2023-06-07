<?php

namespace App\Http\Requests\Api\V1;

use App\Models\Device;
use App\Models\DeviceType;
use App\Models\Location;
use App\Models\RepairLog;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class DeviceLogRequest extends FormRequest
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
            'brand_name' => [
                'required',
                'string',
                'max:250',
            ],
            'model_name' => [
                'nullable',
                'string',
                'max:500',
            ],
            'device_description' => [
                'nullable',
                'string',
                'max:250',
            ],

            'device_type' => [
                'required',
                Rule::exists(DeviceType::class, 'uuid'),
            ],

            'issue_description' => [
                'nullable',
                'string',
                'max:2500',
            ],
            'fix_description' => [
                'nullable',
                'string',
                'max:2500',
            ],
            'used_materials' => [
                'nullable',
                'string',
                'max:2500',
            ],
            'is_using_spare_parts' => [
                'nullable',
                'boolean',
            ],

            'location' => [
                'required',
                Rule::exists(Location::class, 'uuid'),
            ],

            'first_name' => [
                'nullable',
                'string',
            ],
            'last_name' => [
                'nullable',
                'string',
            ],
            'email' => [
                'nullable',
                'email',
            ],
            'telephone' => [
                'nullable',
                'string',
            ],
            'postal_code' => [
                'nullable',
                'string',
            ],
            'locale' => [
                'required',
                'min:2',
                'max:2',
            ],
            'manufacture_year' => [
                'nullable',
                'integer',
                'min:1901',
                'max:2155',
            ],
            'closed_at' => [
                'nullable',
                'date',
            ],
            'completed_status' => [
                'required',
                'exists:completed_repair_statuses,code'
            ],
        ];
    }
}
