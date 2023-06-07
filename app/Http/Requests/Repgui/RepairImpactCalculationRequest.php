<?php

namespace App\Http\Requests\Repgui;

use Illuminate\Foundation\Http\FormRequest;

class RepairImpactCalculationRequest extends FormRequest
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
            'device_category' => ['required'],
            'device_age' => ['required', 'integer'],
        ];
    }
}
