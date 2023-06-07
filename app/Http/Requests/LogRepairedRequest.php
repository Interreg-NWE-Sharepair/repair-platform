<?php

namespace App\Http\Requests;

class LogRepairedRequest extends AbstractFormRequest
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
            //'repair_status' => 'required|exists:repair_statuses,id',
            'completed_status' => ['required', 'exists:completed_repair_statuses,code'],
            'repair_barrier' => 'required_if:completed_status,end_of_life',
            'archive_reason' => 'required_if:completed_status,repairable',
            'note' => 'nullable', 'string', 'max:2500',
        ];
    }
}
