<?php

namespace App\Mails;

class DeviceReopenedMail extends AbstractMail
{
    public static function name()
    {
        return __('Device Reopened Mail');
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
            'device_brand_name' => __('messages.form_brand_name'),
            'device_model_name' => __('messages.model_name'),
            'device_owner' => __('Device owner'),
            'device_first_name' => __('First name - Device owner'),
            'device_last_name' => __('Last name - Device owner'),

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
            'device_owner' => __('Device owner'),
        ];
    }
}

