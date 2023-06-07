<?php

namespace App\Mails;

class DeviceRegisteredMail extends AbstractMail
{
    public static function name()
    {
        return __('Device registered email');
    }

    public static function getContentVariables()
    {
        return [
            'device_id' => __('messages.device_follow_up_nr'),
            'device_type' => __('messages.form_device_type'),
            'device_brand_name' => __('messages.form_brand_name'),
            'device_model_name' => __('messages.model_name'),
            'device_description' => __('messages.form_device_description'),
            'device_issue' => __('messages.form_device_issue'),
            'first_name' => __('messages.form_first_name'),
            'last_name' => __('messages.form_last_name'),
            'email' => __('messages.form_email'),
            'telephone' => __('messages.telephone'),
            'postal_code' => __('messages.form_postal_code'),
            'organisation' => __('messages.form_repair_organisation'),
            'event' => __('messages.form_event'),
        ];
    }

    public static function getRecipientVariables()
    {
        return [
            'device_owner' => __('Device owner'),
        ];
    }
}
