<?php

namespace App\Http\Requests;

class DeviceCloseRequest extends AbstractFormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'completed_status' => ['required', 'exists:completed_repair_statuses,code'],
            'repair_barrier' => 'required_if:completed_status,end_of_life',
            'archive_reason' => 'required_if:completed_status,repairable',
            'note' => 'nullable', 'string', 'max:2500',
        ];
    }
}
