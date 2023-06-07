<?php

namespace App\Http\Requests\Repgui;

use Illuminate\Foundation\Http\FormRequest;

class GuidanceStep2Request extends FormRequest
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
            'common_device_issues' => 'sometimes|array|required|exists:common_device_type_issues,id',
            'problem_now' => 'nullable|string|max:255',
        ];
    }
}
