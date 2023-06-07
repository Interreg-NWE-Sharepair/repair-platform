<?php

namespace App\Http\Requests;

use TimeHunter\LaravelGoogleReCaptchaV3\Validations\GoogleReCaptchaV3ValidationRule;

class ContactRequest extends AbstractFormRequest
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
            'name' => 'required|string',
            'email' => 'required|email',
            'repair_organisation' => 'nullable|integer|exists:organisations,id',
            'message' => 'required|string',
            'terms' => 'required|accepted',
            /*'gRecaptchaResponse' => [new GoogleReCaptchaV3ValidationRule('contact_store')],*/
        ];
    }
}
