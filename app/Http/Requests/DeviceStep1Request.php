<?php

namespace App\Http\Requests;

use Spatie\MediaLibraryPro\Rules\Concerns\ValidatesMedia;

class DeviceStep1Request extends AbstractFormRequest
{
    use ValidatesMedia;
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
            'device_type_id' => [
                'required',
                'exists:device_types,id',
            ],
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
            'manufacture_year' => [
                'nullable',
                'integer',
                'min:1901',
                'max:2155',
            ],
            'issue_description' => [
                'required',
                'string',
                'max:2500',
            ],

            'image_general' => [
                'nullable',
                ''
            ],
            'images_defect' => [
                'nullable',
                'max:5',
            ],
            'images_defect.*' => [
                'nullable',
            ],
            'images_barcode' => [
                'nullable',
                'max:5',
            ],
            'images_barcode.*' => [
                'nullable',
            ],
            'eventKey' => [
                'nullable'
            ]
        ];
    }
}
