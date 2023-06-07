<?php

namespace App\Http\Requests;

use TimeHunter\LaravelGoogleReCaptchaV3\Validations\GoogleReCaptchaV3ValidationRule;

class OrganisationRequestRequest extends AbstractFormRequest
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
            'organisation_name' => 'required|string|max:200',
            'postal_code' => 'required|string|max:200',
            'municipality' => 'required|string|max:200',
            'email' => 'required|email',
            'gRecaptchaResponse' => [new GoogleReCaptchaV3ValidationRule('location_store')],
        ];
    }
}
