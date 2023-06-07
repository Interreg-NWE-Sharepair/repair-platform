<?php

namespace App\Http\Requests;

use App\Models\Device;
use Illuminate\Validation\Rule;
use TimeHunter\LaravelGoogleReCaptchaV3\Validations\GoogleReCaptchaV3ValidationRule;

class DeviceStep2Request extends AbstractFormRequest
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
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'email' => 'required|email|max:250',
            'telephone' => 'nullable|max:100',
            'postal_code' => 'nullable|max:10',
            'terms' => 'required|accepted',
            'register_type' => [
                Rule::in([
                    Device::REGISTER_TYPE_PERSON,
                    Device::REGISTER_TYPE_EVENT,
                ]),
            ],
            'event' => 'required_if:register_type,event',
            /*'gRecaptchaResponse' => [new GoogleReCaptchaV3ValidationRule('device_step_2_store')],*/
        ];
    }
}
