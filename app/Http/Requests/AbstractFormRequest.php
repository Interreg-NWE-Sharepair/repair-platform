<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AbstractFormRequest extends FormRequest
{
    public function attributes()
    {
        $attributes = parent::attributes();
        $attributes += [
            'location_id' => trans('messages.form_location'),
            'first_name' => trans('messages.form_first_name'),
            'last_name' => trans('messages.form_last_name'),
            'email' => trans('messages.form_email'),
            'password' => trans('messages.form_password'),
            'password_confirmation' => trans('messages.form_password_repeat'),
            'terms' => trans('messages.form_terms'),
            'device_type_id' => trans('messages.form_device_type'),
            'brand_name' => trans('messages.form_brand_name'),
            'model_name' => trans('messages.form_model_name'),
            'fix_description' => trans('messages.form_device_fix'),
            'repair_status_id' => trans('messages.repair_log_aides'),
            'images_repair.*' => trans('messages.form_images_repair'),
            'fix_links.*' => trans('messages.form_fix_links'),
            'name' => trans('messages.form_name'),
            'message' => trans('messages.form_message'),
            'manufacture_year' => trans('messages.form_manufacture_year'),
            'note_content' => trans('messages.form_note_edit'),
            'organisation_name' => trans('messages.form_organisation_name'),
            'organisation_postal_code' => trans('messages.form_organisation_postal_code'),
            'organisation_municipality' => trans('messages.form_organisation_municipality'),
            'image_general' => trans('messages.form_image_general'),
            'images_defect' => trans('messages.form_images_defect'),
            'images_defect.*' => trans('messages.form_images_defect'),
            'images_barcode' => trans('messages.form_images_barcode'),
            'images_barcode.*' => trans('messages.form_images_barcode'),
            'issue_description' => trans('messages.form_device_issue'),
            'gRecaptchaResponse' => trans('messages.captcha'),
            'telephone' => trans('messages.telephone'),
            'repair_barrier' => trans('messages.form_repair_barriers'),
            'repair_status' => trans('messages.form_solution_select_title'),
            'archive_reason' => trans('messages.form_archive_option'),
        ];

        return $attributes;
    }
}
