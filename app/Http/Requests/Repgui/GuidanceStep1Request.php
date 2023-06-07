<?php

namespace App\Http\Requests\Repgui;

use Illuminate\Foundation\Http\FormRequest;

class GuidanceStep1Request extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {

        return [
            'device_type_id' => 'required_if:device_type_mobile_id,=,null|nullable|exists:device_types,id',
            'device_type_mobile_id' => 'required_if:device_type_id,=,null|nullable|exists:device_types,id',
            'brand_name' => 'nullable|string|max:250',
            'model_name' => 'nullable|string|max:500',
            'product_description' => 'nullable|string|max:250',
            'product_age' => 'nullable|string',

        ];
    }
}
