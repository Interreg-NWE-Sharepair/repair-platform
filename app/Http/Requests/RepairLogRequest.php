<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RepairLogRequest extends FormRequest
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
            'diagnosis' => [
                'nullable',
                'string',
                'max:2500',
            ],
            'root_cause' => [
                'nullable',
                'string',
                'max:2500',
            ],
            'used_materials' => [
                'nullable',
                'string',
                'max:2500',
            ],
            'used_links' => [
                'nullable',
                'string',
                'max:2500',
            ],
            'images_repair' => [
                'nullable',
                'max:15',
            ],
            'images_repair.*' => ['nullable'],
            'notes.*' => ['nullable'],
        ];
    }
}
