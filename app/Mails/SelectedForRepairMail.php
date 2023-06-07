<?php

namespace App\Mails;

class SelectedForRepairMail extends AbstractMail
{
    public static function name()
    {
        return __('Selected for repair email');
    }

    /**
     * This array lists the possible variable data
     * included in the content of the mail.
     *
     * @return array
     */
    public static function getContentVariables()
    {
        return [
            'repairer_first_name' => __('First name - Repairer'),
            'repairer_last_name' => __('Last name - Repairer'),
            'repairer_email' => __('Email - Repairer'),

            'device_id' => __('messages.device_follow_up_nr'),
            'device_url' => __('Device url'),
            'device_type' => __('messages.form_device_type'),
            'device_brand_name' => __('messages.form_brand_name'),
            'device_model_name' => __('messages.model_name'),
            'device_description' => __('messages.form_device_description'),
            'device_issue' => __('messages.form_device_issue'),
            'device_first_name' => __('First name - Device'),
            'device_last_name' => __('Last name - Device'),
            'device_email' => __('Email - Device'),
            'device_telephone' => __('messages.telephone'),
            'device_postal_code' => __('messages.form_postal_code'),

            'organisation' => __('messages.form_repair_organisation'),
            'event' => __('messages.form_event'),
        ];
    }

    /**
     * This array lists the possible variable
     * recipients that can receive this mail.
     *
     * @return array
     */
    public static function getRecipientVariables()
    {
        return [
            'repairer' => __('Repairer'),
            'device_owner' => __('Device owner'),
        ];
    }
}

