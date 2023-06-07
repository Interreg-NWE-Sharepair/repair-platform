<?php

namespace App\Mails;

class DeviceReminderMail extends AbstractMail
{
    public static function name()
    {
        return __('Device Reminder Mail');
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
            'device_start_date' => __('Device start date'),
            'device_url' => __('Device url'),
            'device_type' => __('messages.form_device_type'),
            'device_brand_name' => __('messages.form_brand_name'),
            'device_model_name' => __('messages.model_name'),
            'device_description' => __('messages.form_device_description'),
            'device_issue' => __('messages.form_device_issue'),
            'device_owner' => __('Device owner'),
            'device_first_name' => __('First name - Device owner'),
            'device_last_name' => __('Last name - Device owner'),
            'device_email' => __('Email - Device owner'),
            'device_telephone' => __('messages.telephone'),
            'device_postal_code' => __('messages.form_postal_code'),

            'overview_url' => __('Overview url'),
            'contact_url' => __('Contact url'),

            'organisation' => __('messages.form_repair_organisation'),
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
        ];
    }
}

