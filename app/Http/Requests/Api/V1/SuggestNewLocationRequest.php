<?php

namespace App\Http\Requests\Api\V1;

use App\Models\ContactDetail;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\In;

class SuggestNewLocationRequest extends FormRequest
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

    protected function prepareForValidation()
    {
        $this->sanitiseImages();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'address' => [
                'required',
                'array',
            ],
            'address.street' => [
                'nullable',
                'string',
            ],
            'address.number' => [
                'nullable',
                'string',
            ],
            'address.bus' => [
                'nullable',
                'string',
            ],
            'address.postal_code' => [
                'nullable',
                'string',
            ],
            'address.city' => [
                'nullable',
                'string',
            ],
            'address.country' => [
                'nullable',
                'string',
            ],

            'has_warranty' => [
                'required',
                'boolean',
            ],

            'organisation_type' => [
                'required',
                'exists:App\Models\OrganisationType,uuid',
            ],

            'device_types' => [
                'nullable',
                'array',
            ],
            'device_types.*' => [
                'required',
                'exists:App\Models\DeviceType,uuid',
            ],

            'activity_sectors' => [
                'nullable',
                'array',
            ],
            'activity_sectors.*' => [
                'required',
                'exists:App\Models\ActivitySector,uuid',
            ],

            'contacts' => [
                'nullable',
                'array',
            ],

            'locales' => ['required',],
            'locales.*' => [
                'required',
                new In(array_keys(config('translatable.default_locales'))),
            ],

            'logo' => [
                'nullable',
                'base64image',
                'base64max:20000', //max 20mb
            ],
            'images' => [
                'nullable',
                'array',
            ],
            'images.*' => [
                'nullable',
                'base64image',
                'base64max:20000', //max 20mb
            ],

            'submitter_email' => [
                'required',
                'email',
            ],
            'submitter_relation' => [
                'nullable',
                'string',
            ],

            'accepted_privacy' => [
                'required',
                'boolean',
                'accepted',
            ],
            'accepted_general_terms' => [
                'required',
                'boolean',
                'accepted',
            ],
        ];

        $this->addTranslatableRules($rules);

        foreach (ContactDetail::TYPES as $type) {
            $key = "contacts.{$type}";
            $rules[$key] = [
                'nullable',
                'array',
            ];
            $rules[$key . '.*.name'] = [
                'nullable',
                'string',
            ];
            $rules[$key . '.*.value'] = $this->getContactValueRules($type);
        }

        return $rules;
    }

    private function addTranslatableRules(array &$rules)
    {
        $rules += $this->addTranslatableRulesForField('name', true);
        $rules += $this->addTranslatableRulesForField('description', false);
        $rules += $this->addTranslatableRulesForField('product_description', false);
        $rules += $this->addTranslatableRulesForField('warranty_info', false);
    }

    private function addTranslatableRulesForField(string $string, $required)
    {
        $required = ($required ? 'required' : 'nullable');
        $rules[$string] = [
            $required,
            'array',
        ];

        foreach (array_keys(config('translatable.default_locales')) as $locale) {
            $rules[$string . '.' . $locale] = [
                'nullable',
                'string',
            ];
        }

        return $rules;
    }

    private function getContactValueRules($type)
    {
        $valueRules = ['nullable'];

        switch ($type) {
            case ContactDetail::TYPE_WEBSITE:
                $valueRules[] = 'url';
                break;
            case ContactDetail::TYPE_EMAIL:
                $valueRules[] = 'email';
                break;
            default:
                $valueRules[] = 'string';
                break;
        }

        return $valueRules;
    }

    private function sanitiseImages()
    {
        //Empty images if they are arrays
        $logo = $this->logo;
        if (is_array($this->logo)){
            $logo = null;
        }

        $images = $this->images;
        foreach ($this->images as $key => $image){
            if (is_array($image)){
                $images[$key] = null;
            }
        }

        $this->merge([
            'logo' => $logo,
            'images' => $images,
        ]);
    }
}
