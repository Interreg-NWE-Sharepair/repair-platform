<?php

namespace App\Http\Requests;

use TimeHunter\LaravelGoogleReCaptchaV3\Validations\GoogleReCaptchaV3ValidationRule;

class RepairerRegisterRequest extends AbstractFormRequest
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
            'first_name' => 'required|min:2|max:30',
            'last_name' => 'required|min:2|max:30',
            'email' => 'required|email|unique:users,email',
            'telephone' => 'required',
            'password' => 'required|string|min:8|required_with:password_confirmation|confirmed',
            'password_confirmation' => 'required|string|min:8',
            'terms' => 'required|accepted',
            'gRecaptchaResponse' => [new GoogleReCaptchaV3ValidationRule('repairer_register_step_1_store')],
        ];
    }
}
